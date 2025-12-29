<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Servis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ServisController extends Controller
{
    /**
     * Check if current user is a regular user (pemegang).
     */
    private function isUserPemegang(): bool
    {
        $user = Auth::user();
        return $user->role === 'user';
    }

    /**
     * Get kendaraan IDs that user is currently holding.
     */
    private function getUserKendaraanIds(): array
    {
        return Auth::user()->riwayatPemakaiAktif()
            ->pluck('kendaraan_id')
            ->toArray();
    }

    /**
     * Display a listing of servis.
     */
    public function index(Request $request)
    {
        $query = Servis::with(['kendaraan.merk', 'createdBy']);

        // Filter untuk user pemegang - hanya tampilkan servis kendaraan mereka
        if ($this->isUserPemegang()) {
            $kendaraanIds = $this->getUserKendaraanIds();
            $query->whereIn('kendaraan_id', $kendaraanIds);
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('deskripsi', 'ilike', "%{$search}%")
                    ->orWhere('bengkel', 'ilike', "%{$search}%")
                    ->orWhereHas('kendaraan', function ($q) use ($search) {
                        $q->where('plat_nomor', 'ilike', "%{$search}%");
                    });
            });
        }

        // Kendaraan filter
        if ($request->filled('kendaraan_id')) {
            $query->where('kendaraan_id', $request->kendaraan_id);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Jenis filter
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Sorting
        $sortColumn = $request->input('sort', 'tanggal_servis');
        $sortDirection = $request->input('direction', 'desc');

        $allowedSorts = ['tanggal_servis', 'jenis', 'biaya', 'status'];
        if (!in_array($sortColumn, $allowedSorts)) {
            $sortColumn = 'tanggal_servis';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        $servis = $query->orderBy($sortColumn, $sortDirection)->paginate(15)->withQueryString();

        // Kendaraan dropdown - filter untuk user pemegang
        if ($this->isUserPemegang()) {
            $kendaraanIds = $this->getUserKendaraanIds();
            $kendaraan = Kendaraan::aktif()->with('merk')
                ->whereIn('id', $kendaraanIds)
                ->orderBy('plat_nomor')->get();

            // Stats for user's kendaraan only
            $stats = [
                'total' => Servis::whereIn('kendaraan_id', $kendaraanIds)->count(),
                'dijadwalkan' => Servis::whereIn('kendaraan_id', $kendaraanIds)->dijadwalkan()->count(),
                'dalam_proses' => Servis::whereIn('kendaraan_id', $kendaraanIds)->dalamProses()->count(),
                'selesai_bulan_ini' => Servis::whereIn('kendaraan_id', $kendaraanIds)->selesai()
                    ->whereMonth('tanggal_selesai', now()->month)
                    ->whereYear('tanggal_selesai', now()->year)
                    ->count(),
                'total_biaya_bulan_ini' => Servis::whereIn('kendaraan_id', $kendaraanIds)->selesai()
                    ->whereMonth('tanggal_selesai', now()->month)
                    ->whereYear('tanggal_selesai', now()->year)
                    ->sum('biaya'),
            ];
        } else {
            $kendaraan = Kendaraan::aktif()->with('merk')->orderBy('plat_nomor')->get();

            // Stats for all kendaraan
            $stats = [
                'total' => Servis::count(),
                'dijadwalkan' => Servis::dijadwalkan()->count(),
                'dalam_proses' => Servis::dalamProses()->count(),
                'selesai_bulan_ini' => Servis::selesai()
                    ->whereMonth('tanggal_selesai', now()->month)
                    ->whereYear('tanggal_selesai', now()->year)
                    ->count(),
                'total_biaya_bulan_ini' => Servis::selesai()
                    ->whereMonth('tanggal_selesai', now()->month)
                    ->whereYear('tanggal_selesai', now()->year)
                    ->sum('biaya'),
            ];
        }

        $isUserPemegang = $this->isUserPemegang();
        return view('servis.index', compact('servis', 'kendaraan', 'stats', 'sortColumn', 'sortDirection', 'isUserPemegang'));
    }

    /**
     * Show the form for creating a new servis.
     */
    public function create(Request $request)
    {
        // Filter kendaraan untuk user pemegang
        if ($this->isUserPemegang()) {
            $kendaraanIds = $this->getUserKendaraanIds();
            $kendaraan = Kendaraan::aktif()->with('merk')
                ->whereIn('id', $kendaraanIds)
                ->orderBy('plat_nomor')->get();
        } else {
            $kendaraan = Kendaraan::aktif()->with('merk')->orderBy('plat_nomor')->get();
        }

        $selectedKendaraanId = $request->kendaraan_id;
        $isUserPemegang = $this->isUserPemegang();

        return view('servis.create', compact('kendaraan', 'selectedKendaraanId', 'isUserPemegang'));
    }

    /**
     * Store a newly created servis in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kendaraan_id' => 'required|exists:kendaraan,id',
            'jenis' => 'required|in:rutin,perbaikan,darurat,overhaul',
            'tanggal_servis' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_servis',
            'kilometer' => 'nullable|integer|min:0',
            'bengkel' => 'nullable|string|max:255',
            'deskripsi' => 'required|string',
            'spare_parts' => 'nullable|string',
            'biaya' => 'nullable|numeric|min:0',
            'status' => 'required|in:dijadwalkan,dalam_proses,selesai,dibatalkan',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'servis_berikutnya' => 'nullable|date|after:tanggal_servis',
            'km_berikutnya' => 'nullable|integer|min:0',
            'catatan' => 'nullable|string',
        ]);

        // Cek otorisasi untuk user pemegang
        if ($this->isUserPemegang()) {
            $kendaraanIds = $this->getUserKendaraanIds();
            if (!in_array($validated['kendaraan_id'], $kendaraanIds)) {
                return back()->with('error', 'Anda tidak memiliki akses untuk menambah servis kendaraan ini.');
            }
        }

        DB::beginTransaction();
        try {
            if ($request->hasFile('bukti')) {
                $validated['bukti_path'] = $request->file('bukti')->store('servis/bukti', 'public');
            }

            unset($validated['bukti']);
            $validated['created_by'] = Auth::id();

            $servis = Servis::create($validated);

            DB::commit();

            return redirect()
                ->route('servis.show', $servis)
                ->with('success', 'Data servis berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified servis.
     */
    public function show(Servis $servis)
    {
        // Cek otorisasi untuk user pemegang
        if ($this->isUserPemegang()) {
            $kendaraanIds = $this->getUserKendaraanIds();
            if (!in_array($servis->kendaraan_id, $kendaraanIds)) {
                abort(403, 'Anda tidak memiliki akses untuk melihat servis ini.');
            }
        }

        $servis->load(['kendaraan.merk', 'kendaraan.garasi', 'createdBy']);
        $isUserPemegang = $this->isUserPemegang();

        return view('servis.show', compact('servis', 'isUserPemegang'));
    }

    /**
     * Show the form for editing the specified servis.
     */
    public function edit(Servis $servis)
    {
        // Cek otorisasi untuk user pemegang
        if ($this->isUserPemegang()) {
            $kendaraanIds = $this->getUserKendaraanIds();
            if (!in_array($servis->kendaraan_id, $kendaraanIds)) {
                abort(403, 'Anda tidak memiliki akses untuk mengedit servis ini.');
            }
            $kendaraan = Kendaraan::aktif()->with('merk')
                ->whereIn('id', $kendaraanIds)
                ->orderBy('plat_nomor')->get();
        } else {
            $kendaraan = Kendaraan::aktif()->with('merk')->orderBy('plat_nomor')->get();
        }

        $isUserPemegang = $this->isUserPemegang();

        return view('servis.edit', compact('servis', 'kendaraan', 'isUserPemegang'));
    }

    /**
     * Update the specified servis in storage.
     */
    public function update(Request $request, Servis $servis)
    {
        // Cek otorisasi untuk user pemegang
        if ($this->isUserPemegang()) {
            $kendaraanIds = $this->getUserKendaraanIds();
            if (!in_array($servis->kendaraan_id, $kendaraanIds)) {
                abort(403, 'Anda tidak memiliki akses untuk mengedit servis ini.');
            }
        }

        $validated = $request->validate([
            'kendaraan_id' => 'required|exists:kendaraan,id',
            'jenis' => 'required|in:rutin,perbaikan,darurat,overhaul',
            'tanggal_servis' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_servis',
            'kilometer' => 'nullable|integer|min:0',
            'bengkel' => 'nullable|string|max:255',
            'deskripsi' => 'required|string',
            'spare_parts' => 'nullable|string',
            'biaya' => 'nullable|numeric|min:0',
            'status' => 'required|in:dijadwalkan,dalam_proses,selesai,dibatalkan',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'servis_berikutnya' => 'nullable|date|after:tanggal_servis',
            'km_berikutnya' => 'nullable|integer|min:0',
            'catatan' => 'nullable|string',
        ]);

        // Cek kendaraan_id baru juga harus milik user
        if ($this->isUserPemegang()) {
            if (!in_array($validated['kendaraan_id'], $kendaraanIds)) {
                return back()->with('error', 'Anda tidak memiliki akses untuk kendaraan ini.');
            }
        }

        DB::beginTransaction();
        try {
            if ($request->hasFile('bukti')) {
                if ($servis->bukti_path) {
                    Storage::disk('public')->delete($servis->bukti_path);
                }
                $validated['bukti_path'] = $request->file('bukti')->store('servis/bukti', 'public');
            }

            unset($validated['bukti']);
            $servis->update($validated);

            DB::commit();

            return redirect()
                ->route('servis.show', $servis)
                ->with('success', 'Data servis berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified servis from storage.
     */
    public function destroy(Servis $servis)
    {
        // User pemegang tidak boleh hapus servis
        if ($this->isUserPemegang()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus servis.');
        }

        $servis->delete();

        return redirect()
            ->route('servis.index')
            ->with('success', 'Data servis berhasil dihapus.');
    }

    /**
     * Mark servis as completed.
     */
    public function selesai(Request $request, Servis $servis)
    {
        // Cek otorisasi untuk user pemegang
        if ($this->isUserPemegang()) {
            $kendaraanIds = $this->getUserKendaraanIds();
            if (!in_array($servis->kendaraan_id, $kendaraanIds)) {
                abort(403, 'Anda tidak memiliki akses untuk menyelesaikan servis ini.');
            }
        }

        $validated = $request->validate([
            'tanggal_selesai' => 'required|date|after_or_equal:' . $servis->tanggal_servis->format('Y-m-d'),
            'biaya' => 'nullable|numeric|min:0',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'servis_berikutnya' => 'nullable|date|after:tanggal_selesai',
            'km_berikutnya' => 'nullable|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('bukti')) {
                if ($servis->bukti_path) {
                    Storage::disk('public')->delete($servis->bukti_path);
                }
                $validated['bukti_path'] = $request->file('bukti')->store('servis/bukti', 'public');
            }

            unset($validated['bukti']);
            $validated['status'] = Servis::STATUS_SELESAI;

            $servis->update($validated);

            DB::commit();

            return redirect()
                ->route('servis.show', $servis)
                ->with('success', 'Servis berhasil ditandai selesai.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
