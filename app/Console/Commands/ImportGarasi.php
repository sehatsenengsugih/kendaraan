<?php

namespace App\Console\Commands;

use App\Models\Garasi;
use App\Models\Kevikepan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ImportGarasi extends Command
{
    protected $signature = 'garasi:import
                            {file : Path ke file CSV yang akan diimport}
                            {--dry-run : Hanya validasi tanpa menyimpan ke database}';

    protected $description = 'Import data garasi dari file CSV';

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
        $this->kevikepanCache = Kevikepan::pluck('id', 'nama')->mapWithKeys(function ($id, $nama) {
            return [strtolower($nama) => $id];
        })->toArray();
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
            'kota' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            $this->errors[$lineNumber] = $validator->errors()->all();
            return false;
        }

        // Check duplicate
        $existing = Garasi::where('nama', $data['nama'])->first();
        if ($existing) {
            $this->warnings[$lineNumber] = "Garasi '{$data['nama']}' sudah ada (ID: {$existing->id}), dilewati";
            return 'skip';
        }

        // Resolve kevikepan_id
        $kevikepanId = null;
        if (!empty($data['kevikepan'])) {
            $kevikepanId = $this->kevikepanCache[strtolower($data['kevikepan'])] ?? null;
            if ($kevikepanId === null) {
                $this->warnings[$lineNumber] = "Kevikepan '{$data['kevikepan']}' tidak ditemukan";
            }
        }

        $garasiData = [
            'nama' => $data['nama'],
            'alamat' => $data['alamat'] ?? null,
            'kota' => $data['kota'],
            'kode_pos' => $data['kode_pos'] ?? null,
            'kevikepan_id' => $kevikepanId,
            'pic_name' => $data['pic_name'] ?? null,
            'pic_phone' => $data['pic_phone'] ?? null,
        ];

        if (!$isDryRun) {
            Garasi::create($garasiData);
        }

        return true;
    }

    private function displayResults(bool $isDryRun): void
    {
        $this->newLine();
        $this->info("=====================================");
        $this->info("      HASIL IMPORT GARASI");
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
