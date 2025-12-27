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
     * Display a listing of servis.
     */
    public function index(Request $request)
    {
        $query = Servis::with(['kendaraan.merk', 'createdBy']);

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

        $servis = $query->orderBy('tanggal_servis', 'desc')->paginate(15)->withQueryString();

        $kendaraan = Kendaraan::aktif()->with('merk')->orderBy('plat_nomor')->get();

        // Stats
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

        return view('servis.index', compact('servis', 'kendaraan', 'stats'));
    }

    /**
     * Show the form for creating a new servis.
     */
    public function create(Request $request)
    {
        $kendaraan = Kendaraan::aktif()->with('merk')->orderBy('plat_nomor')->get();
        $selectedKendaraanId = $request->kendaraan_id;

        return view('servis.create', compact('kendaraan', 'selectedKendaraanId'));
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
        $servis->load(['kendaraan.merk', 'kendaraan.garasi', 'createdBy']);

        return view('servis.show', compact('servis'));
    }

    /**
     * Show the form for editing the specified servis.
     */
    public function edit(Servis $servis)
    {
        $kendaraan = Kendaraan::aktif()->with('merk')->orderBy('plat_nomor')->get();

        return view('servis.edit', compact('servis', 'kendaraan'));
    }

    /**
     * Update the specified servis in storage.
     */
    public function update(Request $request, Servis $servis)
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
