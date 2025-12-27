<?php

namespace App\Console\Commands;

use App\Models\Kevikepan;
use App\Models\Paroki;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ImportParoki extends Command
{
    protected $signature = 'paroki:import
                            {file : Path ke file CSV yang akan diimport}
                            {--dry-run : Hanya validasi tanpa menyimpan ke database}';

    protected $description = 'Import data paroki dari file CSV';

    private array $errors = [];
    private array $warnings = [];
    private int $successCount = 0;
    private int $skipCount = 0;
    private array $kevikepanCache = [];

    public function handle(): int
    {
        $filePath = $this->argument('file');
        $isDryRun = $this->option('dry-run');

        if (!file_exists($filePath)) {
            $this->error("File tidak ditemukan: {$filePath}");
            return 1;
        }

        $this->loadCaches();

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

        $lineNumber = 0;
        $headers = [];

        DB::beginTransaction();

        try {
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
                $result = $this->processRow($lineNumber, $data, $isDryRun);

                if ($result === true) {
                    $this->successCount++;
                } elseif ($result === 'skip') {
                    $this->skipCount++;
                }
            }

            if (!$isDryRun && empty($this->errors)) {
                DB::commit();
            } else {
                DB::rollBack();
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error: " . $e->getMessage());
            return 1;
        }

        fclose($handle);
        $this->displayResults($isDryRun);

        return empty($this->errors) ? 0 : 1;
    }

    private function loadCaches(): void
    {
        $kevikepans = Kevikepan::all();

        foreach ($kevikepans as $kev) {
            $nama = strtolower($kev->nama);
            $this->kevikepanCache[$nama] = $kev->id;

            // Add alternative name mappings
            // "Kevikepan Semarang" -> also match "KEV. SEMARANG", "kev semarang", etc.
            $shortName = str_replace('kevikepan ', '', $nama);
            $this->kevikepanCache['kev. ' . $shortName] = $kev->id;
            $this->kevikepanCache['kev ' . $shortName] = $kev->id;

            // Handle "jogja" vs "yogyakarta"
            if (str_contains($nama, 'jogja')) {
                $yogyaName = str_replace('jogja', 'yogyakarta', $nama);
                $this->kevikepanCache[$yogyaName] = $kev->id;
                $yogyaShort = str_replace('jogja', 'yogyakarta', $shortName);
                $this->kevikepanCache['kev. ' . $yogyaShort] = $kev->id;
                $this->kevikepanCache['kev ' . $yogyaShort] = $kev->id;
            }

            // Handle "magelang" -> "kedu"
            if (str_contains($nama, 'magelang')) {
                $this->kevikepanCache['kevikepan kedu'] = $kev->id;
                $this->kevikepanCache['kev. kedu'] = $kev->id;
                $this->kevikepanCache['kev kedu'] = $kev->id;
            }
        }
    }

    private function mapRowToData(array $headers, array $row): array
    {
        $data = [];
        foreach ($headers as $index => $header) {
            $data[$header] = isset($row[$index]) ? trim($row[$index]) : null;
        }
        return $data;
    }

    private function processRow(int $lineNumber, array $data, bool $isDryRun): bool|string
    {
        $validator = Validator::make($data, [
            'nama' => 'required|string|max:255',
            'kevikepan' => 'required|string',
        ]);

        if ($validator->fails()) {
            $this->errors[$lineNumber] = $validator->errors()->all();
            return false;
        }

        // Check duplicate
        $existing = Paroki::where('nama', $data['nama'])->first();
        if ($existing) {
            $this->warnings[$lineNumber] = "Paroki '{$data['nama']}' sudah ada (ID: {$existing->id}), dilewati";
            return 'skip';
        }

        // Resolve kevikepan_id
        $kevikepanId = $this->kevikepanCache[strtolower($data['kevikepan'])] ?? null;
        if ($kevikepanId === null) {
            $this->errors[$lineNumber] = ["Kevikepan '{$data['kevikepan']}' tidak ditemukan"];
            return false;
        }

        $parokiData = [
            'nama' => $data['nama'],
            'kevikepan_id' => $kevikepanId,
            'alamat' => $data['alamat'] ?? null,
            'kota' => $data['kota'] ?? null,
            'telepon' => $data['telepon'] ?? null,
            'email' => $data['email'] ?? null,
            'is_active' => $this->parseBoolean($data['is_active'] ?? 'ya'),
        ];

        if (!$isDryRun) {
            Paroki::create($parokiData);
        }

        return true;
    }

    private function parseBoolean(?string $value): bool
    {
        $trueValues = ['ya', 'yes', 'true', '1', 'aktif', 'active'];
        return in_array(strtolower($value ?? 'ya'), $trueValues);
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
                foreach ($msgs as $msg) {
                    $this->error("  Baris {$line}: {$msg}");
                }
            }
        }
    }
}
