<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Pajak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PajakController extends Controller
{
    /**
     * Display a listing of pajak.
     */
    public function index(Request $request)
    {
        $query = Pajak::with(['kendaraan.merk', 'createdBy']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('kendaraan', function ($q) use ($search) {
                $q->where('plat_nomor', 'ilike', "%{$search}%")
                    ->orWhere('nama_model', 'ilike', "%{$search}%");
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

        // Due soon filter (pajak jatuh tempo dalam 30 hari)
        if ($request->boolean('due_soon')) {
            $query->dueWithinDays(30);
        }

        // Overdue filter
        if ($request->boolean('overdue')) {
            $query->where('status', '!=', Pajak::STATUS_LUNAS)
                ->where('tanggal_jatuh_tempo', '<', now());
        }

        $pajak = $query->orderBy('tanggal_jatuh_tempo', 'desc')->paginate(15)->withQueryString();

        $kendaraan = Kendaraan::aktif()->with('merk')->orderBy('plat_nomor')->get();

        // Stats
        $stats = [
            'total' => Pajak::count(),
            'belum_bayar' => Pajak::belumBayar()->count(),
            'terlambat' => Pajak::where('status', '!=', Pajak::STATUS_LUNAS)
                ->where('tanggal_jatuh_tempo', '<', now())->count(),
            'due_soon' => Pajak::dueWithinDays(30)->count(),
        ];

        return view('pajak.index', compact('pajak', 'kendaraan', 'stats'));
    }

    /**
     * Show the form for creating a new pajak.
     */
    public function create(Request $request)
    {
        $kendaraan = Kendaraan::aktif()->with('merk')->orderBy('plat_nomor')->get();
        $selectedKendaraanId = $request->kendaraan_id;

        return view('pajak.create', compact('kendaraan', 'selectedKendaraanId'));
    }

    /**
     * Store a newly created pajak in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kendaraan_id' => 'required|exists:kendaraan,id',
            'jenis' => 'required|in:tahunan,lima_tahunan',
            'tanggal_jatuh_tempo' => 'required|date',
            'tanggal_bayar' => 'nullable|date',
            'nominal' => 'nullable|numeric|min:0',
            'denda' => 'nullable|numeric|min:0',
            'status' => 'required|in:belum_bayar,lunas,terlambat',
            'nomor_notice' => 'nullable|string|max:100',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Handle file upload
            if ($request->hasFile('bukti')) {
                $validated['bukti_path'] = $request->file('bukti')->store('pajak/bukti', 'public');
            }

            unset($validated['bukti']);
            $validated['created_by'] = Auth::id();

            $pajak = Pajak::create($validated);

            DB::commit();

            return redirect()
                ->route('pajak.show', $pajak)
                ->with('success', 'Data pajak berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified pajak.
     */
    public function show(Pajak $pajak)
    {
        $pajak->load(['kendaraan.merk', 'kendaraan.garasi', 'createdBy']);

        return view('pajak.show', compact('pajak'));
    }

    /**
     * Show the form for editing the specified pajak.
     */
    public function edit(Pajak $pajak)
    {
        $kendaraan = Kendaraan::aktif()->with('merk')->orderBy('plat_nomor')->get();

        return view('pajak.edit', compact('pajak', 'kendaraan'));
    }

    /**
     * Update the specified pajak in storage.
     */
    public function update(Request $request, Pajak $pajak)
    {
        $validated = $request->validate([
            'kendaraan_id' => 'required|exists:kendaraan,id',
            'jenis' => 'required|in:tahunan,lima_tahunan',
            'tanggal_jatuh_tempo' => 'required|date',
            'tanggal_bayar' => 'nullable|date',
            'nominal' => 'nullable|numeric|min:0',
            'denda' => 'nullable|numeric|min:0',
            'status' => 'required|in:belum_bayar,lunas,terlambat',
            'nomor_notice' => 'nullable|string|max:100',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Handle file upload
            if ($request->hasFile('bukti')) {
                // Delete old file
                if ($pajak->bukti_path) {
                    Storage::disk('public')->delete($pajak->bukti_path);
                }
                $validated['bukti_path'] = $request->file('bukti')->store('pajak/bukti', 'public');
            }

            unset($validated['bukti']);
            $pajak->update($validated);

            DB::commit();

            return redirect()
                ->route('pajak.show', $pajak)
                ->with('success', 'Data pajak berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified pajak from storage.
     */
    public function destroy(Pajak $pajak)
    {
        $pajak->delete();

        return redirect()
            ->route('pajak.index')
            ->with('success', 'Data pajak berhasil dihapus.');
    }

    /**
     * Mark pajak as paid.
     */
    public function bayar(Request $request, Pajak $pajak)
    {
        $validated = $request->validate([
            'tanggal_bayar' => 'required|date',
            'nominal' => 'nullable|numeric|min:0',
            'denda' => 'nullable|numeric|min:0',
            'nomor_notice' => 'nullable|string|max:100',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('bukti')) {
                if ($pajak->bukti_path) {
                    Storage::disk('public')->delete($pajak->bukti_path);
                }
                $validated['bukti_path'] = $request->file('bukti')->store('pajak/bukti', 'public');
            }

            unset($validated['bukti']);
            $validated['status'] = Pajak::STATUS_LUNAS;

            $pajak->update($validated);

            DB::commit();

            return redirect()
                ->route('pajak.show', $pajak)
                ->with('success', 'Pajak berhasil ditandai sebagai lunas.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
