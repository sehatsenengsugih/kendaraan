<?php

namespace App\Http\Controllers;

use App\Models\Garasi;
use App\Models\GambarKendaraan;
use App\Models\Kendaraan;
use App\Models\Merk;
use App\Models\Pengguna;
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

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('plat_nomor', 'ilike', "%{$search}%")
                    ->orWhere('nama_model', 'ilike', "%{$search}%")
                    ->orWhere('nomor_bpkb', 'ilike', "%{$search}%")
                    ->orWhereHas('merk', function ($q) use ($search) {
                        $q->where('nama', 'ilike', "%{$search}%");
                    });
            });
        }

        // Jenis filter
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Merk filter
        if ($request->filled('merk_id')) {
            $query->where('merk_id', $request->merk_id);
        }

        // Garasi filter
        if ($request->filled('garasi_id')) {
            $query->where('garasi_id', $request->garasi_id);
        }

        $kendaraan = $query->orderBy('plat_nomor')->paginate(15)->withQueryString();

        $merk = Merk::orderBy('nama')->get();
        $garasi = Garasi::orderBy('nama')->get();

        return view('kendaraan.index', compact('kendaraan', 'merk', 'garasi'));
    }

    /**
     * Show the form for creating a new kendaraan.
     */
    public function create(Request $request)
    {
        $merk = Merk::orderBy('nama')->get();
        $garasi = Garasi::with('kevikepan')->orderBy('nama')->get();
        $pemegang = Pengguna::where('role', 'user')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        // Pre-selected values from query params
        $selectedMerkId = $request->merk_id;
        $selectedGarasiId = $request->garasi_id;

        return view('kendaraan.create', compact('merk', 'garasi', 'pemegang', 'selectedMerkId', 'selectedGarasiId'));
    }

    /**
     * Store a newly created kendaraan in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plat_nomor' => 'required|string|max:20|unique:kendaraan,plat_nomor',
            'nomor_bpkb' => 'required|string|max:50|unique:kendaraan,nomor_bpkb',
            'merk_id' => 'required|exists:merk,id',
            'nama_model' => 'required|string|max:100',
            'tahun_pembuatan' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'warna' => 'required|string|max:50',
            'jenis' => 'required|in:mobil,motor',
            'garasi_id' => 'required|exists:garasi,id',
            'pemegang_id' => 'nullable|exists:pengguna,id',
            'status' => 'required|in:aktif,nonaktif,dihibahkan',
            'tanggal_perolehan' => 'required|date',
            'tanggal_hibah' => 'nullable|date|after_or_equal:tanggal_perolehan',
            'catatan' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gambar.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                $validated['avatar_path'] = $this->uploadImage($request->file('avatar'), 'kendaraan/avatars');
            }

            // Remove non-model fields
            unset($validated['avatar'], $validated['gambar']);

            $kendaraan = Kendaraan::create($validated);

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
        $kendaraan->load(['merk', 'garasi.kevikepan', 'pemegang', 'gambar']);

        return view('kendaraan.show', compact('kendaraan'));
    }

    /**
     * Show the form for editing the specified kendaraan.
     */
    public function edit(Kendaraan $kendaraan)
    {
        $kendaraan->load('gambar');

        $merk = Merk::orderBy('nama')->get();
        $garasi = Garasi::with('kevikepan')->orderBy('nama')->get();
        $pemegang = Pengguna::where('role', 'user')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('kendaraan.edit', compact('kendaraan', 'merk', 'garasi', 'pemegang'));
    }

    /**
     * Update the specified kendaraan in storage.
     */
    public function update(Request $request, Kendaraan $kendaraan)
    {
        $validated = $request->validate([
            'plat_nomor' => ['required', 'string', 'max:20', Rule::unique('kendaraan')->ignore($kendaraan->id)],
            'nomor_bpkb' => ['required', 'string', 'max:50', Rule::unique('kendaraan')->ignore($kendaraan->id)],
            'merk_id' => 'required|exists:merk,id',
            'nama_model' => 'required|string|max:100',
            'tahun_pembuatan' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'warna' => 'required|string|max:50',
            'jenis' => 'required|in:mobil,motor',
            'garasi_id' => 'required|exists:garasi,id',
            'pemegang_id' => 'nullable|exists:pengguna,id',
            'status' => 'required|in:aktif,nonaktif,dihibahkan',
            'tanggal_perolehan' => 'required|date',
            'tanggal_hibah' => 'nullable|date|after_or_equal:tanggal_perolehan',
            'catatan' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gambar.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'delete_gambar' => 'nullable|array',
            'delete_gambar.*' => 'exists:gambar_kendaraan,id',
        ]);

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

            // Remove non-model fields
            unset($validated['avatar'], $validated['gambar'], $validated['delete_gambar']);

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
     * Upload image helper.
     */
    private function uploadImage($file, string $directory): string
    {
        $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($directory, $filename, 'public');
    }
}
