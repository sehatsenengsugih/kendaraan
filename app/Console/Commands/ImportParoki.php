<?php

namespace App\Console\Commands;

use App\Models\Kevikepan;
use App\Models\Paroki;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportParoki extends Command
{
    protected $signature = 'paroki:import
                            {file : Path ke file CSV yang akan diimport}
                            {--dry-run : Hanya validasi tanpa menyimpan ke database}
                            {--truncate : Hapus semua data paroki sebelum import}';

    protected $description = 'Import data paroki dari file CSV (format baru)';

    private array $errors = [];
    private array $warnings = [];
    private int $successCount = 0;
    private int $skipCount = 0;
    private array $kevikepanIds = [];
    private array $parokiIdMap = []; // Map old ID to new ID for parent relationships

    public function handle(): int
    {
        $filePath = $this->argument('file');
        $isDryRun = $this->option('dry-run');
        $truncate = $this->option('truncate');

        if (!file_exists($filePath)) {
            $this->error("File tidak ditemukan: {$filePath}");
            return 1;
        }

        // Load valid kevikepan IDs
        $this->kevikepanIds = Kevikepan::pluck('id')->toArray();

        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            $this->error("Gagal membuka file: {$filePath}");
            return 1;
        }

        $this->info("Memproses file: {$filePath}");
        if ($isDryRun) {
            $this->warn("Mode DRY RUN - tidak ada data yang disimpan");
        }
        $this->newLine();

        // Read all rows first
        $rows = [];
        $lineNumber = 0;
        $headers = [];

        while (($row = fgetcsv($handle)) !== false) {
            $lineNumber++;

            if (empty(array_filter($row))) {
                continue;
            }

            if ($lineNumber === 1) {
                $headers = array_map('trim', $row);
                continue;
            }

            $data = $this->mapRowToData($headers, $row);
            $rows[] = ['line' => $lineNumber, 'data' => $data];
        }
        fclose($handle);

        $this->info("Total baris data: " . count($rows));

        DB::beginTransaction();

        try {
            // Truncate if requested
            if ($truncate && !$isDryRun) {
                $this->warn("Menghapus semua data paroki...");
                DB::table('kendaraan')->update(['dipinjam_paroki_id' => null, 'tarikan_paroki_id' => null]);
                DB::table('riwayat_pemakai')->update(['paroki_id' => null]);
                DB::statement('TRUNCATE TABLE paroki RESTART IDENTITY CASCADE');
            }

            // First pass: insert all paroki without parent_id
            $this->info("Pass 1: Menyimpan data paroki...");
            $progressBar = $this->output->createProgressBar(count($rows));

            foreach ($rows as $item) {
                $result = $this->processRow($item['line'], $item['data'], $isDryRun, false);

                if ($result === true) {
                    $this->successCount++;
                } elseif ($result === 'skip') {
                    $this->skipCount++;
                }

                $progressBar->advance();
            }
            $progressBar->finish();
            $this->newLine();

            // Second pass: update parent_id relationships
            $this->info("Pass 2: Mengupdate relasi parent...");
            $parentUpdates = 0;

            foreach ($rows as $item) {
                $data = $item['data'];
                $oldId = $this->cleanNumeric($data['paroki_id'] ?? null);
                $parentOldId = $this->cleanNumeric($data['paroki_parent'] ?? null);

                if ($oldId && $parentOldId && isset($this->parokiIdMap[$oldId]) && isset($this->parokiIdMap[$parentOldId])) {
                    $newId = $this->parokiIdMap[$oldId];
                    $newParentId = $this->parokiIdMap[$parentOldId];

                    if (!$isDryRun) {
                        Paroki::where('id', $newId)->update(['parent_id' => $newParentId]);
                    }
                    $parentUpdates++;
                }
            }

            $this->info("Parent relationships updated: {$parentUpdates}");

            if (!$isDryRun && empty($this->errors)) {
                DB::commit();
                $this->info("Data berhasil disimpan!");
            } else {
                DB::rollBack();
                if ($isDryRun) {
                    $this->warn("Rollback karena mode dry-run");
                }
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error: " . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }

        $this->displayResults($isDryRun);

        return empty($this->errors) ? 0 : 1;
    }

    private function mapRowToData(array $headers, array $row): array
    {
        $data = [];
        foreach ($headers as $index => $header) {
            $data[$header] = isset($row[$index]) ? trim($row[$index]) : null;
        }
        return $data;
    }

    private function processRow(int $lineNumber, array $data, bool $isDryRun, bool $updateParent): bool|string
    {
        $oldId = $this->cleanNumeric($data['paroki_id'] ?? null);
        $nama = trim($data['paroki_nama'] ?? '');

        if (empty($nama)) {
            $this->errors[$lineNumber] = ["Nama paroki kosong"];
            return false;
        }

        // Resolve kevikepan_id
        $kevikepanId = $this->cleanNumeric($data['kevikepan_id'] ?? null);
        if ($kevikepanId && !in_array($kevikepanId, $this->kevikepanIds)) {
            // Map kevikepan_id from CSV to our kevikepan table
            // CSV uses 1-5, our table might have different IDs
            // Let's assume 1=Semarang, 2=Surakarta, 3=Jogja Timur, 4=Magelang, 5=Jogja Barat
            $kevikepanMapping = [
                1 => 1, // Semarang
                2 => 2, // Surakarta
                3 => 3, // Jogja Timur
                4 => 4, // Magelang (Kedu)
                5 => 5, // Jogja Barat
            ];
            $kevikepanId = $kevikepanMapping[$kevikepanId] ?? null;
        }

        // Parse coordinates
        $latitude = $this->cleanDecimal($data['paroki_lat'] ?? null);
        $longitude = $this->cleanDecimal($data['paroki_lon'] ?? null);

        // Parse status: paroki_status = 0 means active (!)
        $isActive = ($data['paroki_status'] ?? '0') === '0';

        $parokiData = [
            'kode' => $data['paroki_kode'] ?: null,
            'kevikepan_id' => $kevikepanId,
            'nama_gereja' => $data['paroki_gereja'] ? trim($data['paroki_gereja']) : null,
            'nama' => $nama,
            'alamat' => $data['paroki_alamat'] ?: null,
            'kota_id' => $this->cleanNumeric($data['paroki_kota'] ?? null),
            'telepon' => $data['paroki_telepon'] ?: null,
            'email' => $data['paroki_email'] ?: null,
            'fax' => $data['paroki_fax'] ?: null,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'status_paroki_id' => $this->cleanNumeric($data['status_paroki_id'] ?? 1) ?: 1,
            'kecamatan_id' => $this->cleanNumeric($data['paroki_kecamatan'] ?? null),
            'kelurahan_id' => $this->cleanNumeric($data['paroki_kelurahan'] ?? null),
            'gambar' => $data['paroki_img'] ?: null,
            'is_active' => $isActive,
            // parent_id will be set in second pass
        ];

        if (!$isDryRun) {
            $paroki = Paroki::create($parokiData);
            // Store mapping of old ID to new ID
            if ($oldId) {
                $this->parokiIdMap[$oldId] = $paroki->id;
            }
        } else {
            // For dry run, simulate ID mapping
            if ($oldId) {
                $this->parokiIdMap[$oldId] = $oldId;
            }
        }

        return true;
    }

    private function cleanNumeric(?string $value): ?int
    {
        if ($value === null || $value === '' || $value === '0') {
            return null;
        }
        $cleaned = preg_replace('/[^0-9]/', '', $value);
        return $cleaned ? (int)$cleaned : null;
    }

    private function cleanDecimal(?string $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }
        $cleaned = preg_replace('/[^0-9.\-]/', '', $value);
        return $cleaned ? (float)$cleaned : null;
    }

    private function displayResults(bool $isDryRun): void
    {
        $this->newLine();
        $this->info("=====================================");
        $this->info("      HASIL IMPORT PAROKI");
        $this->info("=====================================");

        if ($isDryRun) {
            $this->warn("Mode: DRY RUN");
        }

        $this->info("Berhasil : {$this->successCount}");
        $this->info("Dilewati : {$this->skipCount}");
        $this->info("Gagal    : " . count($this->errors));

        if (!empty($this->warnings)) {
            $this->newLine();
            $this->warn("Peringatan:");
            foreach ($this->warnings as $line => $msg) {
                $this->warn("  Baris {$line}: {$msg}");
            }
        }

        if (!empty($this->errors)) {
            $this->newLine();
            $this->error("Error:");
            foreach ($this->errors as $line => $msgs) {
                foreach ((array)$msgs as $msg) {
                    $this->error("  Baris {$line}: {$msg}");
                }
            }
        }
    }
}
