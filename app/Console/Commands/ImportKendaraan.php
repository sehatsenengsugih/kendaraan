<?php

namespace App\Console\Commands;

use App\Models\Garasi;
use App\Models\Kendaraan;
use App\Models\Merk;
use App\Models\Pajak;
use App\Models\Pengguna;
use App\Models\StatusBpkb;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ImportKendaraan extends Command
{
    protected $signature = 'kendaraan:import
                            {file : Path ke file CSV yang akan diimport}
                            {--dry-run : Hanya validasi tanpa menyimpan ke database}
                            {--skip-header : Skip baris pertama (header)}';

    protected $description = 'Import data kendaraan dari file CSV';

    private array $errors = [];
    private array $warnings = [];
    private int $successCount = 0;
    private int $skipCount = 0;

    private array $merkCache = [];
    private array $garasiCache = [];
    private array $statusBpkbCache = [];
    private array $penggunaCache = [];

    public function handle(): int
    {
        $filePath = $this->argument('file');
        $isDryRun = $this->option('dry-run');
        $skipHeader = $this->option('skip-header');

        // Validate file exists
        if (!file_exists($filePath)) {
            $this->error("File tidak ditemukan: {$filePath}");
            return 1;
        }

        // Load caches
        $this->loadCaches();

        // Read CSV
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

        // Start progress
        $this->output->progressStart();

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($handle)) !== false) {
                $lineNumber++;

                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }

                // First row is header
                if ($lineNumber === 1) {
                    $headers = array_map('trim', $row);
                    if ($skipHeader) {
                        continue;
                    }
                    // Validate headers
                    if (!$this->validateHeaders($headers)) {
                        $this->error("Header tidak valid!");
                        return 1;
                    }
                    continue;
                }

                // Process data row
                $data = $this->mapRowToData($headers, $row);
                $result = $this->processRow($lineNumber, $data, $isDryRun);

                if ($result === true) {
                    $this->successCount++;
                } elseif ($result === 'skip') {
                    $this->skipCount++;
                }

                $this->output->progressAdvance();
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

        $this->output->progressFinish();
        $this->newLine();

        // Display results
        $this->displayResults($isDryRun);

        return empty($this->errors) ? 0 : 1;
    }

    private function loadCaches(): void
    {
        // Load merk cache (nama => id)
        $this->merkCache = Merk::pluck('id', 'nama')->mapWithKeys(function ($id, $nama) {
            return [strtolower($nama) => $id];
        })->toArray();

        // Load garasi cache (nama => id)
        $this->garasiCache = Garasi::pluck('id', 'nama')->mapWithKeys(function ($id, $nama) {
            return [strtolower($nama) => $id];
        })->toArray();

        // Load status BPKB cache (nama => id)
        $this->statusBpkbCache = StatusBpkb::pluck('id', 'nama')->mapWithKeys(function ($id, $nama) {
            return [strtolower($nama) => $id];
        })->toArray();

        // Load pengguna cache (name => id)
        $this->penggunaCache = Pengguna::pluck('id', 'name')->mapWithKeys(function ($id, $name) {
            return [strtolower($name) => $id];
        })->toArray();
    }

    private function validateHeaders(array $headers): bool
    {
        $required = ['plat_nomor', 'merk', 'nama_model', 'tahun_pembuatan', 'jenis'];
        $missing = array_diff($required, $headers);

        if (!empty($missing)) {
            $this->error("Kolom wajib tidak ditemukan: " . implode(', ', $missing));
            return false;
        }

        return true;
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
        // Validate required fields
        $validator = Validator::make($data, [
            'plat_nomor' => 'required|string|max:50',
            'merk' => 'required|string',
            'nama_model' => 'required|string|max:255',
            'jenis' => 'required|in:mobil,motor,Mobil,Motor',
        ], [
            'plat_nomor.required' => 'Plat nomor wajib diisi',
            'merk.required' => 'Merk wajib diisi',
            'nama_model.required' => 'Nama model wajib diisi',
            'jenis.required' => 'Jenis wajib diisi',
            'jenis.in' => 'Jenis harus mobil atau motor',
        ]);

        if ($validator->fails()) {
            $this->errors[$lineNumber] = $validator->errors()->all();
            return false;
        }

        // Check for duplicate plat_nomor
        $existingKendaraan = Kendaraan::where('plat_nomor', $data['plat_nomor'])->first();
        if ($existingKendaraan) {
            $this->warnings[$lineNumber] = "Plat nomor {$data['plat_nomor']} sudah ada (ID: {$existingKendaraan->id}), dilewati";
            return 'skip';
        }

        // Resolve merk_id
        $jenis = strtolower($data['jenis']);
        $merkId = $this->resolveMerkId($data['merk'], $jenis, $isDryRun);
        if ($merkId === null) {
            $this->errors[$lineNumber] = ["Gagal membuat merk: {$data['merk']}"];
            return false;
        }

        // Resolve garasi_id (optional)
        $garasiId = null;
        if (!empty($data['garasi'])) {
            $garasiId = $this->resolveGarasiId($data['garasi']);
            if ($garasiId === null) {
                $this->warnings[$lineNumber] = "Garasi '{$data['garasi']}' tidak ditemukan, dikosongkan";
            }
        }

        // Resolve status_bpkb_id (optional)
        $statusBpkbId = null;
        if (!empty($data['status_bpkb'])) {
            $statusBpkbId = $this->resolveStatusBpkbId($data['status_bpkb']);
            if ($statusBpkbId === null) {
                $this->warnings[$lineNumber] = "Status BPKB '{$data['status_bpkb']}' tidak ditemukan, dikosongkan";
            }
        }

        // Get pemegang nama (text field)
        $pemegangNama = !empty($data['pemegang']) ? trim($data['pemegang']) : null;

        // Extract year from nama_model if tahun_pembuatan is empty
        $tahunPembuatan = $this->extractYear($data['tahun_pembuatan'] ?? null, $data['nama_model'] ?? '');

        // Prepare kendaraan data
        $kendaraanData = [
            'plat_nomor' => strtoupper(trim($data['plat_nomor'])),
            'merk_id' => $merkId,
            'nama_model' => trim($data['nama_model']),
            'tahun_pembuatan' => $tahunPembuatan,
            'jenis' => strtolower($data['jenis']),
            'warna' => !empty($data['warna']) ? trim($data['warna']) : null,
            'status_bpkb_id' => $statusBpkbId,
            'nomor_bpkb' => !empty($data['nomor_bpkb']) ? trim($data['nomor_bpkb']) : null,
            'nomor_rangka' => !empty($data['nomor_rangka']) ? trim($data['nomor_rangka']) : null,
            'nomor_mesin' => !empty($data['nomor_mesin']) ? trim($data['nomor_mesin']) : null,
            'garasi_id' => $garasiId,
            'pemegang_nama' => $pemegangNama,
            'status' => $this->resolveStatus($data['status'] ?? 'aktif'),
            'status_kepemilikan' => $this->resolveKepemilikan($data['status_kepemilikan'] ?? 'milik_kas'),
            'tanggal_perolehan' => $this->parseDate($data['tanggal_perolehan'] ?? null),
            'harga_beli' => $this->parseDecimal($data['harga_beli'] ?? null),
            'catatan' => !empty($data['catatan']) ? trim($data['catatan']) : null,
        ];

        // Create kendaraan
        if (!$isDryRun) {
            $kendaraan = Kendaraan::create($kendaraanData);

            // Create pajak tahunan if provided
            $pajakTahunan = $this->parseDate($data['pajak_jatuh_tempo'] ?? null);
            if ($pajakTahunan) {
                Pajak::create([
                    'kendaraan_id' => $kendaraan->id,
                    'jenis' => Pajak::JENIS_TAHUNAN,
                    'tanggal_jatuh_tempo' => $pajakTahunan,
                    'status' => Pajak::STATUS_BELUM_BAYAR,
                ]);
            }

            // Create pajak 5 tahunan if provided
            $pajak5Tahunan = $this->parseDate($data['pajak_5tahunan_jatuh_tempo'] ?? null);
            if ($pajak5Tahunan) {
                Pajak::create([
                    'kendaraan_id' => $kendaraan->id,
                    'jenis' => Pajak::JENIS_LIMA_TAHUNAN,
                    'tanggal_jatuh_tempo' => $pajak5Tahunan,
                    'status' => Pajak::STATUS_BELUM_BAYAR,
                ]);
            }
        }

        return true;
    }

    private function resolveMerkId(string $merkName, string $jenis, bool $isDryRun): ?int
    {
        $key = strtolower($merkName);

        if (isset($this->merkCache[$key])) {
            return $this->merkCache[$key];
        }

        // Create new merk if not dry run
        if (!$isDryRun) {
            $merk = Merk::create([
                'nama' => $merkName,
                'jenis' => $jenis,
            ]);
            $this->merkCache[$key] = $merk->id;
            return $merk->id;
        }

        // For dry run, return a fake ID
        return -1;
    }

    private function resolveGarasiId(string $garasiName): ?int
    {
        $key = strtolower($garasiName);
        return $this->garasiCache[$key] ?? null;
    }

    private function resolveStatusBpkbId(string $statusName): ?int
    {
        $key = strtolower($statusName);
        return $this->statusBpkbCache[$key] ?? null;
    }

    private function resolvePemegangId(string $pemegangName): ?int
    {
        $key = strtolower($pemegangName);
        return $this->penggunaCache[$key] ?? null;
    }

    private function extractYear(?string $tahunPembuatan, string $namaModel): ?int
    {
        // If tahun_pembuatan is provided and valid, use it
        if (!empty($tahunPembuatan)) {
            $year = (int) $tahunPembuatan;
            if ($year >= 1900 && $year <= date('Y') + 1) {
                return $year;
            }
        }

        // Try to extract year from nama_model (e.g., "Avanza 1.5 G M/T/2019" or "/2016" pattern)
        if (preg_match('/\/(\d{4})/', $namaModel, $matches)) {
            $year = (int) $matches[1];
            if ($year >= 1900 && $year <= date('Y') + 1) {
                return $year;
            }
        }

        // Try pattern with dash or space before year
        if (preg_match('/[-\s](\d{4})(?:\s|$|\))/', $namaModel, $matches)) {
            $year = (int) $matches[1];
            if ($year >= 1900 && $year <= date('Y') + 1) {
                return $year;
            }
        }

        return null;
    }

    private function resolveStatus(?string $status): string
    {
        $map = [
            'aktif' => 'aktif',
            'nonaktif' => 'nonaktif',
            'non-aktif' => 'nonaktif',
            'dihibahkan' => 'dihibahkan',
            'dijual' => 'dijual',
        ];

        return $map[strtolower($status ?? 'aktif')] ?? 'aktif';
    }

    private function resolveKepemilikan(?string $kepemilikan): string
    {
        $map = [
            'milik_kas' => 'milik_kas',
            'milik kas' => 'milik_kas',
            'kas' => 'milik_kas',
            'milik_lembaga_lain' => 'milik_lembaga_lain',
            'milik lembaga lain' => 'milik_lembaga_lain',
            'lembaga lain' => 'milik_lembaga_lain',
        ];

        return $map[strtolower($kepemilikan ?? 'milik_kas')] ?? 'milik_kas';
    }

    private function parseDate(?string $date): ?string
    {
        if (empty($date)) {
            return null;
        }

        $date = trim($date);

        // Handle DD/MM/YY format (e.g., 13/01/27 means 2027-01-13)
        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{2})$/', $date, $matches)) {
            $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
            $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
            $year = (int) $matches[3];
            // Assume 20xx for years 00-99
            $year = $year < 50 ? 2000 + $year : 1900 + $year;
            return "{$year}-{$month}-{$day}";
        }

        // Try various date formats
        $formats = ['Y-m-d', 'd/m/Y', 'd-m-Y', 'Y/m/d'];
        foreach ($formats as $format) {
            $parsed = \DateTime::createFromFormat($format, $date);
            if ($parsed !== false) {
                return $parsed->format('Y-m-d');
            }
        }

        return null;
    }

    private function parseDecimal(?string $value): ?float
    {
        if (empty($value)) {
            return null;
        }

        $value = trim($value);

        // Check if dots are used as thousand separators (e.g., 194.100.000)
        // If there are multiple dots, they're thousand separators
        if (substr_count($value, '.') > 1) {
            // Remove dots (thousand separators)
            $clean = str_replace('.', '', $value);
            // Remove any other non-numeric characters
            $clean = preg_replace('/[^0-9]/', '', $clean);
        } else {
            // Single dot might be decimal point or thousand separator
            // If format is like "194.100" (3 digits after dot), it's thousand separator
            if (preg_match('/\.(\d{3})$/', $value)) {
                $clean = str_replace('.', '', $value);
                $clean = preg_replace('/[^0-9]/', '', $clean);
            } else {
                // Remove non-numeric characters except decimal point
                $clean = preg_replace('/[^0-9.]/', '', $value);
            }
        }

        return $clean !== '' ? (float) $clean : null;
    }

    private function displayResults(bool $isDryRun): void
    {
        $this->info("=====================================");
        $this->info("         HASIL IMPORT");
        $this->info("=====================================");
        $this->newLine();

        if ($isDryRun) {
            $this->warn("Mode: DRY RUN (tidak ada data yang disimpan)");
            $this->newLine();
        }

        $this->info("Berhasil    : {$this->successCount} kendaraan");
        $this->info("Dilewati    : {$this->skipCount} kendaraan (duplikat)");
        $this->info("Gagal       : " . count($this->errors) . " kendaraan");

        if (!empty($this->warnings)) {
            $this->newLine();
            $this->warn("Peringatan:");
            foreach ($this->warnings as $line => $message) {
                $this->warn("  Baris {$line}: {$message}");
            }
        }

        if (!empty($this->errors)) {
            $this->newLine();
            $this->error("Error:");
            foreach ($this->errors as $line => $messages) {
                $this->error("  Baris {$line}:");
                foreach ($messages as $message) {
                    $this->error("    - {$message}");
                }
            }
        }

        $this->newLine();
        if (empty($this->errors) && !$isDryRun) {
            $this->info("Import selesai dengan sukses!");
        } elseif (!empty($this->errors)) {
            $this->error("Import selesai dengan error. Perbaiki file CSV dan coba lagi.");
        } else {
            $this->info("Validasi selesai. Jalankan ulang tanpa --dry-run untuk import data.");
        }
    }
}
