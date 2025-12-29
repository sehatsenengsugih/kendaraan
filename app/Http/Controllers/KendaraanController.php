<?php

namespace App\Http\Controllers;

use App\Models\Garasi;
use App\Models\GambarKendaraan;
use App\Models\Kendaraan;
use App\Models\Lembaga;
use App\Models\Merk;
use App\Models\Paroki;
use App\Models\Pengguna;
use App\Models\RiwayatPemakai;
use App\Models\StatusBpkb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class KendaraanController extends Controller
{
    /**
     * Display a listing of kendaraan.
     */
    public function index(Request $request)
    {
        $query = Kendaraan::with(['merk', 'garasi', 'pemegang']);

        // Role 'user' hanya bisa melihat kendaraan yang dia pakai (pemegang_id = user id)
        $user = auth()->user();
        if ($user->role === 'user') {
            $query->where('pemegang_id', $user->id);
        }

        // Search filter - cari di semua field
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                // Field utama kendaraan
                $q->where('plat_nomor', 'ilike', "%{$search}%")
                    ->orWhere('nama_model', 'ilike', "%{$search}%")
                    ->orWhere('warna', 'ilike', "%{$search}%")
                    // Dokumen
                    ->orWhere('nomor_bpkb', 'ilike', "%{$search}%")
                    ->orWhere('nomor_rangka', 'ilike', "%{$search}%")
                    ->orWhere('nomor_mesin', 'ilike', "%{$search}%")
                    // Kepemilikan & Pemegang
                    ->orWhere('pemegang_nama', 'ilike', "%{$search}%")
                    ->orWhere('nama_pemilik_lembaga', 'ilike', "%{$search}%")
                    // Pinjam
                    ->orWhere('dipinjam_oleh', 'ilike', "%{$search}%")
                    // Tarikan
                    ->orWhere('tarikan_dari', 'ilike', "%{$search}%")
                    ->orWhere('tarikan_pemakai', 'ilike', "%{$search}%")
                    // Jual/Hibah
                    ->orWhere('nama_pembeli', 'ilike', "%{$search}%")
                    ->orWhere('nama_penerima_hibah', 'ilike', "%{$search}%")
                    // Catatan
                    ->orWhere('catatan', 'ilike', "%{$search}%")
                    // Relasi: Merk
                    ->orWhereHas('merk', function ($mq) use ($search) {
                        $mq->where('nama', 'ilike', "%{$search}%");
                    })
                    // Relasi: Garasi
                    ->orWhereHas('garasi', function ($gq) use ($search) {
                        $gq->where('nama', 'ilike', "%{$search}%")
                            ->orWhere('alamat', 'ilike', "%{$search}%")
                            ->orWhere('kota', 'ilike', "%{$search}%")
                            ->orWhere('pic_name', 'ilike', "%{$search}%");
                    })
                    // Relasi: Riwayat Pemakai
                    ->orWhereHas('riwayatPemakai', function ($rq) use ($search) {
                        $rq->where('nama_pemakai', 'ilike', "%{$search}%");
                    })
                    // Relasi: Paroki Pinjam/Tarikan
                    ->orWhereHas('dipinjamParoki', function ($pq) use ($search) {
                        $pq->where('nama', 'ilike', "%{$search}%");
                    })
                    ->orWhereHas('tarikanParoki', function ($tq) use ($search) {
                        $tq->where('nama', 'ilike', "%{$search}%");
                    });
            });
        }

        // Jenis filter
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Status filter - default ke 'aktif' jika tidak ada parameter
        $status = $request->input('status', 'aktif');
        if ($status && $status !== 'semua') {
            $query->where('status', $status);
        }

        // Merk filter
        if ($request->filled('merk_id')) {
            $query->where('merk_id', $request->merk_id);
        }

        // Garasi filter
        if ($request->filled('garasi_id')) {
            $query->where('garasi_id', $request->garasi_id);
        }

        // Sorting
        $sortColumn = $request->input('sort', 'plat_nomor');
        $sortDirection = $request->input('direction', 'asc');

        $allowedSorts = ['plat_nomor', 'jenis', 'tahun_pembuatan', 'status', 'nama_model'];
        if (!in_array($sortColumn, $allowedSorts)) {
            $sortColumn = 'plat_nomor';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        $kendaraan = $query->orderBy($sortColumn, $sortDirection)->paginate(15)->withQueryString();

        $merk = Merk::orderBy('nama')->get();
        $garasi = Garasi::orderBy('nama')->get();

        return view('kendaraan.index', compact('kendaraan', 'merk', 'garasi', 'sortColumn', 'sortDirection'));
    }

    /**
     * Show the form for creating a new kendaraan.
     */
    public function create(Request $request)
    {
        $merk = Merk::orderBy('nama')->get();
        $garasi = Garasi::with('kevikepan')->orderBy('nama')->get();
        $paroki = Paroki::with('kevikepan')->where('is_active', true)->orderBy('nama')->get();
        $lembaga = Lembaga::where('is_active', true)->orderBy('nama')->get();
        $statusBpkb = StatusBpkb::active()->ordered()->get();

        // Pre-selected values from query params
        $selectedMerkId = $request->merk_id;
        $selectedGarasiId = $request->garasi_id;

        return view('kendaraan.create', compact('merk', 'garasi', 'paroki', 'lembaga', 'statusBpkb', 'selectedMerkId', 'selectedGarasiId'));
    }

    /**
     * Store a newly created kendaraan in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plat_nomor' => 'required|string|max:20|unique:kendaraan,plat_nomor',
            'status_bpkb_id' => 'nullable|exists:status_bpkb,id',
            'nomor_bpkb' => 'nullable|string|max:50|unique:kendaraan,nomor_bpkb',
            'nomor_rangka' => 'nullable|string|max:50',
            'nomor_mesin' => 'nullable|string|max:50',
            'merk_id' => 'required|exists:merk,id',
            'nama_model' => 'required|string|max:100',
            'tahun_pembuatan' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'warna' => 'required|string|max:50',
            'jenis' => 'required|in:mobil,motor',
            'garasi_id' => 'nullable|exists:garasi,id',
            'pemegang_nama' => 'nullable|string|max:255',
            'status' => 'required|in:aktif,nonaktif,dihibahkan,dijual',
            'status_kepemilikan' => 'required|in:milik_kas,milik_paroki,milik_lembaga_lain',
            'pemilik_paroki_id' => 'nullable|exists:paroki,id',
            'pemilik_lembaga_id' => 'nullable|exists:lembaga,id',
            'nama_pemilik_lembaga' => 'nullable|string|max:255',
            'dokumen_bpkb' => 'nullable|file|mimes:pdf|max:5120',
            'dokumen_stnk' => 'nullable|file|mimes:pdf|max:5120',
            'tanggal_perolehan' => 'nullable|date',
            'tanggal_beli' => 'nullable|date',
            'harga_beli' => 'nullable|numeric|min:0',
            'tanggal_hibah' => 'nullable|date|after_or_equal:tanggal_perolehan',
            'nama_penerima_hibah' => 'nullable|required_if:status,dihibahkan|string|max:255',
            'tanggal_jual' => 'nullable|date|after_or_equal:tanggal_perolehan',
            'harga_jual' => 'nullable|numeric|min:0',
            'nama_pembeli' => 'nullable|required_if:status,dijual|string|max:255',
            'is_dipinjam' => 'nullable|boolean',
            'dipinjam_paroki_id' => 'nullable|exists:paroki,id',
            'dipinjam_oleh' => 'nullable|string|max:255',
            'tanggal_pinjam' => 'nullable|date',
            'is_tarikan' => 'nullable|boolean',
            'tarikan_paroki_id' => 'nullable|exists:paroki,id',
            'tarikan_lembaga_id' => 'nullable|exists:lembaga,id',
            'tarikan_dari' => 'nullable|string|max:255',
            'tarikan_pemakai' => 'nullable|string|max:255',
            'tarikan_kondisi' => 'nullable|string',
            'catatan' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gambar.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            // Riwayat Pemakai
            'riwayat_pemakai' => 'nullable|array',
            'riwayat_pemakai.*.jenis_pemakai' => 'nullable|in:paroki,lembaga,pribadi',
            'riwayat_pemakai.*.paroki_id' => 'nullable|exists:paroki,id',
            'riwayat_pemakai.*.lembaga_id' => 'nullable|exists:lembaga,id',
            'riwayat_pemakai.*.nama_pemakai' => 'nullable|string|max:255',
            'riwayat_pemakai.*.tanggal_mulai' => 'nullable|date',
            'riwayat_pemakai.*.tanggal_selesai' => 'nullable|date|after_or_equal:riwayat_pemakai.*.tanggal_mulai',
            'riwayat_pemakai.*.catatan' => 'nullable|string',
            'riwayat_pemakai.*.dokumen_serah_terima' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        // Convert checkbox values
        $validated['is_dipinjam'] = $request->boolean('is_dipinjam');
        $validated['is_tarikan'] = $request->boolean('is_tarikan');

        // Resolve dropdown values to text fields
        if (!empty($validated['pemilik_lembaga_id'])) {
            $lembaga = Lembaga::find($validated['pemilik_lembaga_id']);
            $validated['nama_pemilik_lembaga'] = $lembaga?->nama;
        }

        if (!empty($validated['dipinjam_paroki_id'])) {
            $paroki = Paroki::find($validated['dipinjam_paroki_id']);
            $validated['dipinjam_oleh'] = $paroki?->nama;
        }

        // Resolve tarikan dari dropdown values
        if (!empty($validated['tarikan_paroki_id']) || !empty($validated['tarikan_lembaga_id'])) {
            $tarikDari = [];
            if (!empty($validated['tarikan_paroki_id'])) {
                $paroki = Paroki::find($validated['tarikan_paroki_id']);
                if ($paroki) $tarikDari[] = $paroki->nama;
            }
            if (!empty($validated['tarikan_lembaga_id'])) {
                $lembaga = Lembaga::find($validated['tarikan_lembaga_id']);
                if ($lembaga) $tarikDari[] = $lembaga->nama;
            }
            if (!empty($tarikDari)) {
                $validated['tarikan_dari'] = implode(', ', $tarikDari);
            }
        }

        DB::beginTransaction();
        try {
            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                $validated['avatar_path'] = $this->uploadImage($request->file('avatar'), 'kendaraan/avatars');
            }

            // Handle dokumen BPKB upload
            if ($request->hasFile('dokumen_bpkb')) {
                $validated['dokumen_bpkb_path'] = $this->uploadDocument($request->file('dokumen_bpkb'), 'kendaraan/dokumen/bpkb');
            }

            // Handle dokumen STNK upload
            if ($request->hasFile('dokumen_stnk')) {
                $validated['dokumen_stnk_path'] = $this->uploadDocument($request->file('dokumen_stnk'), 'kendaraan/dokumen/stnk');
            }

            // Remove non-model fields
            $riwayatPemakaiData = $validated['riwayat_pemakai'] ?? [];
            unset($validated['avatar'], $validated['gambar'], $validated['riwayat_pemakai'], $validated['dokumen_bpkb'], $validated['dokumen_stnk']);

            $kendaraan = Kendaraan::create($validated);

            // Auto-create initial riwayat if pengguna (pemegang_nama) is set
            if (!empty($validated['pemegang_nama'])) {
                RiwayatPemakai::create([
                    'kendaraan_id' => $kendaraan->id,
                    'nama_pemakai' => $validated['pemegang_nama'],
                    'jenis_pemakai' => 'pribadi',
                    'tanggal_mulai' => $validated['tanggal_perolehan'] ?? now()->toDateString(),
                    'tanggal_selesai' => null,
                    'catatan' => 'Pengguna awal saat kendaraan didaftarkan',
                ]);
            }

            // Handle multiple images
            if ($request->hasFile('gambar')) {
                $urutan = 0;
                foreach ($request->file('gambar') as $file) {
                    $path = $this->uploadImage($file, 'kendaraan/gambar');
                    GambarKendaraan::create([
                        'kendaraan_id' => $kendaraan->id,
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                        'urutan' => $urutan++,
                    ]);
                }
            }

            // Handle riwayat pemakai
            foreach ($riwayatPemakaiData as $index => $riwayat) {
                $jenisPemakai = $riwayat['jenis_pemakai'] ?? 'paroki';
                $namaPemakai = null;
                $parokiId = null;
                $lembagaId = null;

                // Resolve nama_pemakai based on jenis
                if ($jenisPemakai === 'paroki' && !empty($riwayat['paroki_id'])) {
                    $paroki = Paroki::find($riwayat['paroki_id']);
                    $namaPemakai = $paroki?->nama;
                    $parokiId = $riwayat['paroki_id'];
                } elseif ($jenisPemakai === 'lembaga' && !empty($riwayat['lembaga_id'])) {
                    $lembaga = Lembaga::find($riwayat['lembaga_id']);
                    $namaPemakai = $lembaga?->nama;
                    $lembagaId = $riwayat['lembaga_id'];
                } elseif ($jenisPemakai === 'pribadi' && !empty($riwayat['nama_pemakai'])) {
                    $namaPemakai = $riwayat['nama_pemakai'];
                }

                if (!empty($namaPemakai) && !empty($riwayat['tanggal_mulai'])) {
                    $tanggalSelesai = $riwayat['tanggal_selesai'] ?? null;

                    // Jika riwayat baru ini aktif (tanggal_selesai null),
                    // tutup semua riwayat aktif sebelumnya
                    if (empty($tanggalSelesai)) {
                        // Set tanggal_selesai = sehari sebelum tanggal_mulai riwayat baru
                        $tanggalClose = date('Y-m-d', strtotime($riwayat['tanggal_mulai'] . ' -1 day'));
                        $this->closeActiveRiwayat($kendaraan->id, $tanggalClose);
                    }

                    // Handle dokumen serah terima upload
                    $dokumenPath = null;
                    if ($request->hasFile("riwayat_pemakai.{$index}.dokumen_serah_terima")) {
                        $dokumenPath = $this->uploadDocument(
                            $request->file("riwayat_pemakai.{$index}.dokumen_serah_terima"),
                            'kendaraan/dokumen/serah-terima'
                        );
                    }

                    RiwayatPemakai::create([
                        'kendaraan_id' => $kendaraan->id,
                        'nama_pemakai' => $namaPemakai,
                        'jenis_pemakai' => $jenisPemakai,
                        'paroki_id' => $parokiId,
                        'lembaga_id' => $lembagaId,
                        'tanggal_mulai' => $riwayat['tanggal_mulai'],
                        'tanggal_selesai' => $tanggalSelesai,
                        'catatan' => $riwayat['catatan'] ?? null,
                        'dokumen_serah_terima_path' => $dokumenPath,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('kendaraan.show', $kendaraan)
                ->with('success', 'Kendaraan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Clean up uploaded files if any
            if (isset($validated['avatar_path'])) {
                Storage::disk('public')->delete($validated['avatar_path']);
            }

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified kendaraan.
     */
    public function show(Kendaraan $kendaraan)
    {
        // Role 'user' hanya bisa melihat kendaraan miliknya
        $user = auth()->user();
        if ($user->role === 'user' && $kendaraan->pemegang_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke kendaraan ini.');
        }

        $kendaraan->load(['merk', 'garasi.kevikepan', 'pemegang', 'gambar', 'riwayatPemakai', 'statusBpkb', 'pemakaiSaatIni.paroki', 'pemakaiSaatIni.lembaga']);

        return view('kendaraan.show', compact('kendaraan'));
    }

    /**
     * Show the form for editing the specified kendaraan.
     */
    public function edit(Kendaraan $kendaraan)
    {
        // Role 'user' hanya bisa edit kendaraan miliknya
        $user = auth()->user();
        if ($user->role === 'user' && $kendaraan->pemegang_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke kendaraan ini.');
        }

        $kendaraan->load(['gambar', 'riwayatPemakai', 'statusBpkb', 'pemakaiSaatIni.paroki', 'pemakaiSaatIni.lembaga']);

        $merk = Merk::orderBy('nama')->get();
        $garasi = Garasi::with('kevikepan')->orderBy('nama')->get();
        $paroki = Paroki::with('kevikepan')->where('is_active', true)->orderBy('nama')->get();
        $lembaga = Lembaga::where('is_active', true)->orderBy('nama')->get();
        $statusBpkb = StatusBpkb::active()->ordered()->get();

        // List pengguna untuk dropdown assign pemegang (hanya untuk admin/super_admin)
        $pengguna = [];
        if (in_array(auth()->user()->role, ['super_admin', 'admin'])) {
            $pengguna = Pengguna::where('role', 'user')->orderBy('name')->get();
        }

        return view('kendaraan.edit', compact('kendaraan', 'merk', 'garasi', 'paroki', 'lembaga', 'statusBpkb', 'pengguna'));
    }

    /**
     * Update the specified kendaraan in storage.
     */
    public function update(Request $request, Kendaraan $kendaraan)
    {
        // Role 'user' hanya bisa update kendaraan miliknya
        $user = auth()->user();
        if ($user->role === 'user' && $kendaraan->pemegang_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke kendaraan ini.');
        }

        $validated = $request->validate([
            'plat_nomor' => ['required', 'string', 'max:20', Rule::unique('kendaraan')->ignore($kendaraan->id)],
            'status_bpkb_id' => 'nullable|exists:status_bpkb,id',
            'nomor_bpkb' => ['nullable', 'string', 'max:50', Rule::unique('kendaraan')->ignore($kendaraan->id)],
            'nomor_rangka' => 'nullable|string|max:50',
            'nomor_mesin' => 'nullable|string|max:50',
            'merk_id' => 'required|exists:merk,id',
            'nama_model' => 'required|string|max:100',
            'tahun_pembuatan' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'warna' => 'required|string|max:50',
            'jenis' => 'required|in:mobil,motor',
            'garasi_id' => 'nullable|exists:garasi,id',
            'pemegang_id' => 'nullable|exists:pengguna,id',
            'pemegang_nama' => 'nullable|string|max:255',
            'status' => 'required|in:aktif,nonaktif,dihibahkan,dijual',
            'status_kepemilikan' => 'required|in:milik_kas,milik_paroki,milik_lembaga_lain',
            'pemilik_paroki_id' => 'nullable|exists:paroki,id',
            'pemilik_lembaga_id' => 'nullable|exists:lembaga,id',
            'nama_pemilik_lembaga' => 'nullable|string|max:255',
            'dokumen_bpkb' => 'nullable|file|mimes:pdf|max:5120',
            'dokumen_stnk' => 'nullable|file|mimes:pdf|max:5120',
            'tanggal_perolehan' => 'nullable|date',
            'tanggal_beli' => 'nullable|date',
            'harga_beli' => 'nullable|numeric|min:0',
            'tanggal_hibah' => 'nullable|date|after_or_equal:tanggal_perolehan',
            'nama_penerima_hibah' => 'nullable|required_if:status,dihibahkan|string|max:255',
            'tanggal_jual' => 'nullable|date|after_or_equal:tanggal_perolehan',
            'harga_jual' => 'nullable|numeric|min:0',
            'nama_pembeli' => 'nullable|required_if:status,dijual|string|max:255',
            'is_dipinjam' => 'nullable|boolean',
            'dipinjam_paroki_id' => 'nullable|exists:paroki,id',
            'dipinjam_oleh' => 'nullable|string|max:255',
            'tanggal_pinjam' => 'nullable|date',
            'is_tarikan' => 'nullable|boolean',
            'tarikan_paroki_id' => 'nullable|exists:paroki,id',
            'tarikan_lembaga_id' => 'nullable|exists:lembaga,id',
            'tarikan_dari' => 'nullable|string|max:255',
            'tarikan_pemakai' => 'nullable|string|max:255',
            'tarikan_kondisi' => 'nullable|string',
            'catatan' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gambar.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'delete_gambar' => 'nullable|array',
            'delete_gambar.*' => 'exists:gambar_kendaraan,id',
            // Riwayat Pemakai
            'riwayat_pemakai' => 'nullable|array',
            'riwayat_pemakai.*.id' => 'nullable|exists:riwayat_pemakai,id',
            'riwayat_pemakai.*.jenis_pemakai' => 'nullable|in:paroki,lembaga,pribadi',
            'riwayat_pemakai.*.paroki_id' => 'nullable|exists:paroki,id',
            'riwayat_pemakai.*.lembaga_id' => 'nullable|exists:lembaga,id',
            'riwayat_pemakai.*.nama_pemakai' => 'nullable|string|max:255',
            'riwayat_pemakai.*.tanggal_mulai' => 'nullable|date',
            'riwayat_pemakai.*.tanggal_selesai' => 'nullable|date|after_or_equal:riwayat_pemakai.*.tanggal_mulai',
            'riwayat_pemakai.*.catatan' => 'nullable|string',
            'riwayat_pemakai.*.dokumen_serah_terima' => 'nullable|file|mimes:pdf|max:5120',
            'delete_riwayat' => 'nullable|array',
            'delete_riwayat.*' => 'exists:riwayat_pemakai,id',
        ]);

        // Convert checkbox values
        $validated['is_dipinjam'] = $request->boolean('is_dipinjam');
        $validated['is_tarikan'] = $request->boolean('is_tarikan');

        // Resolve dropdown values to text fields
        if (!empty($validated['pemilik_lembaga_id'])) {
            $lembaga = Lembaga::find($validated['pemilik_lembaga_id']);
            $validated['nama_pemilik_lembaga'] = $lembaga?->nama;
        }

        if (!empty($validated['dipinjam_paroki_id'])) {
            $paroki = Paroki::find($validated['dipinjam_paroki_id']);
            $validated['dipinjam_oleh'] = $paroki?->nama;
        }

        // Resolve tarikan dari dropdown values
        if (!empty($validated['tarikan_paroki_id']) || !empty($validated['tarikan_lembaga_id'])) {
            $tarikDari = [];
            if (!empty($validated['tarikan_paroki_id'])) {
                $paroki = Paroki::find($validated['tarikan_paroki_id']);
                if ($paroki) $tarikDari[] = $paroki->nama;
            }
            if (!empty($validated['tarikan_lembaga_id'])) {
                $lembaga = Lembaga::find($validated['tarikan_lembaga_id']);
                if ($lembaga) $tarikDari[] = $lembaga->nama;
            }
            if (!empty($tarikDari)) {
                $validated['tarikan_dari'] = implode(', ', $tarikDari);
            }
        }

        DB::beginTransaction();
        try {
            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                // Delete old avatar
                if ($kendaraan->avatar_path) {
                    Storage::disk('public')->delete($kendaraan->avatar_path);
                }
                $validated['avatar_path'] = $this->uploadImage($request->file('avatar'), 'kendaraan/avatars');
            }

            // Handle dokumen BPKB upload
            if ($request->hasFile('dokumen_bpkb')) {
                // Delete old dokumen
                if ($kendaraan->dokumen_bpkb_path) {
                    Storage::disk('public')->delete($kendaraan->dokumen_bpkb_path);
                }
                $validated['dokumen_bpkb_path'] = $this->uploadDocument($request->file('dokumen_bpkb'), 'kendaraan/dokumen/bpkb');
            }

            // Handle dokumen STNK upload
            if ($request->hasFile('dokumen_stnk')) {
                // Delete old dokumen
                if ($kendaraan->dokumen_stnk_path) {
                    Storage::disk('public')->delete($kendaraan->dokumen_stnk_path);
                }
                $validated['dokumen_stnk_path'] = $this->uploadDocument($request->file('dokumen_stnk'), 'kendaraan/dokumen/stnk');
            }

            // Remove non-model fields
            $riwayatPemakaiData = $validated['riwayat_pemakai'] ?? [];
            unset($validated['avatar'], $validated['gambar'], $validated['delete_gambar'], $validated['riwayat_pemakai'], $validated['delete_riwayat'], $validated['dokumen_bpkb'], $validated['dokumen_stnk']);

            // Auto-create history when pengguna (pemegang_nama) changes
            $oldPengguna = $kendaraan->pemegang_nama;
            $newPengguna = $validated['pemegang_nama'] ?? null;

            if ($oldPengguna !== $newPengguna && !empty($oldPengguna)) {
                // Close all active riwayat for old pengguna
                RiwayatPemakai::where('kendaraan_id', $kendaraan->id)
                    ->whereNull('tanggal_selesai')
                    ->update(['tanggal_selesai' => now()->toDateString()]);

                // Create new riwayat for new pengguna (if not empty)
                if (!empty($newPengguna)) {
                    RiwayatPemakai::create([
                        'kendaraan_id' => $kendaraan->id,
                        'nama_pemakai' => $newPengguna,
                        'jenis_pemakai' => 'pribadi',
                        'tanggal_mulai' => now()->toDateString(),
                        'tanggal_selesai' => null,
                        'catatan' => 'Tercatat otomatis saat pergantian pengguna',
                    ]);
                }
            }

            $kendaraan->update($validated);

            // Delete selected images
            if ($request->has('delete_gambar')) {
                $toDelete = GambarKendaraan::whereIn('id', $request->delete_gambar)
                    ->where('kendaraan_id', $kendaraan->id)
                    ->get();

                foreach ($toDelete as $gambar) {
                    $gambar->delete(); // This triggers the deleting event to remove file
                }
            }

            // Handle new images
            if ($request->hasFile('gambar')) {
                $maxUrutan = $kendaraan->gambar()->max('urutan') ?? -1;
                $urutan = $maxUrutan + 1;

                foreach ($request->file('gambar') as $file) {
                    $path = $this->uploadImage($file, 'kendaraan/gambar');
                    GambarKendaraan::create([
                        'kendaraan_id' => $kendaraan->id,
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                        'urutan' => $urutan++,
                    ]);
                }
            }

            // Delete selected riwayat pemakai
            if ($request->has('delete_riwayat')) {
                RiwayatPemakai::whereIn('id', $request->delete_riwayat)
                    ->where('kendaraan_id', $kendaraan->id)
                    ->delete();
            }

            // Handle riwayat pemakai (update existing or create new)
            foreach ($riwayatPemakaiData as $index => $riwayat) {
                $jenisPemakai = $riwayat['jenis_pemakai'] ?? 'paroki';
                $namaPemakai = null;
                $parokiId = null;
                $lembagaId = null;

                // Resolve nama_pemakai based on jenis
                if ($jenisPemakai === 'paroki' && !empty($riwayat['paroki_id'])) {
                    $paroki = Paroki::find($riwayat['paroki_id']);
                    $namaPemakai = $paroki?->nama;
                    $parokiId = $riwayat['paroki_id'];
                } elseif ($jenisPemakai === 'lembaga' && !empty($riwayat['lembaga_id'])) {
                    $lembaga = Lembaga::find($riwayat['lembaga_id']);
                    $namaPemakai = $lembaga?->nama;
                    $lembagaId = $riwayat['lembaga_id'];
                } elseif ($jenisPemakai === 'pribadi' && !empty($riwayat['nama_pemakai'])) {
                    $namaPemakai = $riwayat['nama_pemakai'];
                }

                if (!empty($namaPemakai) && !empty($riwayat['tanggal_mulai'])) {
                    $tanggalSelesai = $riwayat['tanggal_selesai'] ?? null;
                    $isNewRecord = empty($riwayat['id']);
                    $isActive = empty($tanggalSelesai);

                    // Jika ini adalah record baru yang aktif, tutup riwayat aktif lainnya
                    if ($isNewRecord && $isActive) {
                        $tanggalClose = date('Y-m-d', strtotime($riwayat['tanggal_mulai'] . ' -1 day'));
                        $this->closeActiveRiwayat($kendaraan->id, $tanggalClose);
                    }
                    // Jika ini adalah update record existing menjadi aktif
                    elseif (!$isNewRecord && $isActive) {
                        // Tutup riwayat aktif lain, kecuali record ini sendiri
                        $tanggalClose = date('Y-m-d', strtotime($riwayat['tanggal_mulai'] . ' -1 day'));
                        $this->closeActiveRiwayat($kendaraan->id, $tanggalClose, (int) $riwayat['id']);
                    }

                    // Handle dokumen serah terima upload
                    $dokumenPath = null;
                    if ($request->hasFile("riwayat_pemakai.{$index}.dokumen_serah_terima")) {
                        // Delete old dokumen if updating existing riwayat
                        if (!empty($riwayat['id'])) {
                            $existingRiwayat = RiwayatPemakai::find($riwayat['id']);
                            if ($existingRiwayat && $existingRiwayat->dokumen_serah_terima_path) {
                                Storage::disk('public')->delete($existingRiwayat->dokumen_serah_terima_path);
                            }
                        }
                        $dokumenPath = $this->uploadDocument(
                            $request->file("riwayat_pemakai.{$index}.dokumen_serah_terima"),
                            'kendaraan/dokumen/serah-terima'
                        );
                    }

                    $riwayatData = [
                        'kendaraan_id' => $kendaraan->id,
                        'nama_pemakai' => $namaPemakai,
                        'jenis_pemakai' => $jenisPemakai,
                        'paroki_id' => $parokiId,
                        'lembaga_id' => $lembagaId,
                        'tanggal_mulai' => $riwayat['tanggal_mulai'],
                        'tanggal_selesai' => $tanggalSelesai,
                        'catatan' => $riwayat['catatan'] ?? null,
                    ];

                    // Only update dokumen_serah_terima_path if new file uploaded
                    if ($dokumenPath) {
                        $riwayatData['dokumen_serah_terima_path'] = $dokumenPath;
                    }

                    if (!$isNewRecord) {
                        // Update existing
                        RiwayatPemakai::where('id', $riwayat['id'])
                            ->where('kendaraan_id', $kendaraan->id)
                            ->update($riwayatData);
                    } else {
                        // Create new
                        RiwayatPemakai::create($riwayatData);
                    }
                }
            }

            DB::commit();

            return redirect()
                ->route('kendaraan.show', $kendaraan)
                ->with('success', 'Kendaraan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified kendaraan from storage.
     */
    public function destroy(Kendaraan $kendaraan)
    {
        // Role 'user' hanya bisa hapus kendaraan miliknya
        $user = auth()->user();
        if ($user->role === 'user' && $kendaraan->pemegang_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke kendaraan ini.');
        }

        DB::beginTransaction();
        try {
            // Delete avatar
            if ($kendaraan->avatar_path) {
                Storage::disk('public')->delete($kendaraan->avatar_path);
            }

            // Delete all images (model event will handle file deletion)
            foreach ($kendaraan->gambar as $gambar) {
                $gambar->delete();
            }

            $platNomor = $kendaraan->plat_nomor;
            $kendaraan->delete();

            DB::commit();

            return redirect()
                ->route('kendaraan.index')
                ->with('success', 'Kendaraan "' . $platNomor . '" berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reorder gallery images.
     */
    public function reorderGambar(Request $request, Kendaraan $kendaraan)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:gambar_kendaraan,id',
        ]);

        $order = $request->input('order');

        foreach ($order as $index => $gambarId) {
            GambarKendaraan::where('id', $gambarId)
                ->where('kendaraan_id', $kendaraan->id)
                ->update(['urutan' => $index]);
        }

        return response()->json(['success' => true, 'message' => 'Urutan gambar berhasil diperbarui']);
    }

    /**
     * Set a gallery image as the main avatar.
     */
    public function setGambarAsAvatar(Kendaraan $kendaraan, GambarKendaraan $gambar)
    {
        // Verify the image belongs to this kendaraan
        if ($gambar->kendaraan_id !== $kendaraan->id) {
            return response()->json(['success' => false, 'message' => 'Gambar tidak ditemukan'], 404);
        }

        // Delete old avatar if exists
        if ($kendaraan->avatar_path && Storage::disk('public')->exists($kendaraan->avatar_path)) {
            Storage::disk('public')->delete($kendaraan->avatar_path);
        }

        // Copy the gallery image to avatars folder
        $newAvatarPath = 'kendaraan/avatars/' . uniqid() . '_' . time() . '.' . pathinfo($gambar->file_path, PATHINFO_EXTENSION);
        Storage::disk('public')->copy($gambar->file_path, $newAvatarPath);

        // Update kendaraan avatar
        $kendaraan->update(['avatar_path' => $newAvatarPath]);

        return response()->json([
            'success' => true,
            'message' => 'Avatar berhasil diperbarui',
            'avatar_url' => asset('storage/' . $newAvatarPath),
        ]);
    }

    /**
     * Close all active riwayat pemakai for a kendaraan.
     * Called when a new active riwayat is created.
     *
     * @param int $kendaraanId
     * @param string|null $tanggalSelesai Date to set as tanggal_selesai (defaults to today)
     * @param int|null $excludeId Riwayat ID to exclude from closing (for update scenarios)
     */
    private function closeActiveRiwayat(int $kendaraanId, ?string $tanggalSelesai = null, ?int $excludeId = null): void
    {
        $query = RiwayatPemakai::where('kendaraan_id', $kendaraanId)
            ->whereNull('tanggal_selesai');

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $query->update([
            'tanggal_selesai' => $tanggalSelesai ?? now()->toDateString(),
        ]);
    }

    /**
     * Upload image helper.
     */
    private function uploadImage($file, string $directory): string
    {
        $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($directory, $filename, 'public');
    }

    /**
     * Upload document (PDF) helper.
     */
    private function uploadDocument($file, string $directory): string
    {
        $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($directory, $filename, 'public');
    }
}
