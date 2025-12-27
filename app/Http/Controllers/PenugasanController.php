<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Pengguna;
use App\Models\Penugasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenugasanController extends Controller
{
    /**
     * Display a listing of penugasan.
     */
    public function index(Request $request)
    {
        $query = Penugasan::with(['kendaraan.merk', 'pemegang', 'assignedBy']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('tujuan', 'ilike', "%{$search}%")
                    ->orWhereHas('kendaraan', function ($q) use ($search) {
                        $q->where('plat_nomor', 'ilike', "%{$search}%");
                    })
                    ->orWhereHas('pemegang', function ($q) use ($search) {
                        $q->where('name', 'ilike', "%{$search}%");
                    });
            });
        }

        // Kendaraan filter
        if ($request->filled('kendaraan_id')) {
            $query->where('kendaraan_id', $request->kendaraan_id);
        }

        // Pemegang filter
        if ($request->filled('pemegang_id')) {
            $query->where('pemegang_id', $request->pemegang_id);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $penugasan = $query->orderBy('tanggal_mulai', 'desc')->paginate(15)->withQueryString();

        $kendaraan = Kendaraan::aktif()->with('merk')->orderBy('plat_nomor')->get();
        $pemegang = Pengguna::where('role', 'user')->where('status', 'active')->orderBy('name')->get();

        // Stats
        $stats = [
            'total' => Penugasan::count(),
            'aktif' => Penugasan::aktif()->count(),
            'selesai' => Penugasan::selesai()->count(),
        ];

        return view('penugasan.index', compact('penugasan', 'kendaraan', 'pemegang', 'stats'));
    }

    /**
     * Show the form for creating a new penugasan.
     */
    public function create(Request $request)
    {
        $kendaraan = Kendaraan::aktif()->with('merk')->orderBy('plat_nomor')->get();
        $pemegang = Pengguna::where('role', 'user')->where('status', 'active')->orderBy('name')->get();
        $selectedKendaraanId = $request->kendaraan_id;

        return view('penugasan.create', compact('kendaraan', 'pemegang', 'selectedKendaraanId'));
    }

    /**
     * Store a newly created penugasan in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kendaraan_id' => 'required|exists:kendaraan,id',
            'pemegang_id' => 'required|exists:pengguna,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'tujuan' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Check if kendaraan already has active penugasan
            $existingActive = Penugasan::where('kendaraan_id', $validated['kendaraan_id'])
                ->aktif()
                ->exists();

            if ($existingActive) {
                return back()
                    ->withInput()
                    ->with('error', 'Kendaraan ini masih memiliki penugasan aktif. Selesaikan terlebih dahulu.');
            }

            $validated['assigned_by'] = Auth::id();
            $validated['status'] = Penugasan::STATUS_AKTIF;

            $penugasan = Penugasan::create($validated);

            // Update pemegang_id di kendaraan
            $penugasan->kendaraan->update(['pemegang_id' => $validated['pemegang_id']]);

            DB::commit();

            return redirect()
                ->route('penugasan.show', $penugasan)
                ->with('success', 'Penugasan berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified penugasan.
     */
    public function show(Penugasan $penugasan)
    {
        $penugasan->load(['kendaraan.merk', 'kendaraan.garasi', 'pemegang', 'assignedBy']);

        return view('penugasan.show', compact('penugasan'));
    }

    /**
     * Show the form for editing the specified penugasan.
     */
    public function edit(Penugasan $penugasan)
    {
        $kendaraan = Kendaraan::aktif()->with('merk')->orderBy('plat_nomor')->get();
        $pemegang = Pengguna::where('role', 'user')->where('status', 'active')->orderBy('name')->get();

        return view('penugasan.edit', compact('penugasan', 'kendaraan', 'pemegang'));
    }

    /**
     * Update the specified penugasan in storage.
     */
    public function update(Request $request, Penugasan $penugasan)
    {
        $validated = $request->validate([
            'pemegang_id' => 'required|exists:pengguna,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:aktif,selesai,dibatalkan',
            'tujuan' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $penugasan->update($validated);

            // If status changed to selesai/dibatalkan, clear pemegang from kendaraan
            if (in_array($validated['status'], [Penugasan::STATUS_SELESAI, Penugasan::STATUS_DIBATALKAN])) {
                $penugasan->kendaraan->update(['pemegang_id' => null]);
            } else {
                // Update pemegang if still active
                $penugasan->kendaraan->update(['pemegang_id' => $validated['pemegang_id']]);
            }

            DB::commit();

            return redirect()
                ->route('penugasan.show', $penugasan)
                ->with('success', 'Penugasan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified penugasan from storage.
     */
    public function destroy(Penugasan $penugasan)
    {
        DB::beginTransaction();
        try {
            // Clear pemegang from kendaraan if this was active
            if ($penugasan->isAktif()) {
                $penugasan->kendaraan->update(['pemegang_id' => null]);
            }

            $penugasan->delete();

            DB::commit();

            return redirect()
                ->route('penugasan.index')
                ->with('success', 'Penugasan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Mark penugasan as completed.
     */
    public function selesai(Request $request, Penugasan $penugasan)
    {
        $validated = $request->validate([
            'tanggal_selesai' => 'required|date|after_or_equal:' . $penugasan->tanggal_mulai->format('Y-m-d'),
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $validated['status'] = Penugasan::STATUS_SELESAI;
            $penugasan->update($validated);

            // Clear pemegang from kendaraan
            $penugasan->kendaraan->update(['pemegang_id' => null]);

            DB::commit();

            return redirect()
                ->route('penugasan.show', $penugasan)
                ->with('success', 'Penugasan berhasil diselesaikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
