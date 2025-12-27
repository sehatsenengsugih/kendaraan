<?php

namespace App\Http\Controllers;

use App\Models\Kevikepan;
use App\Models\Paroki;
use Illuminate\Http\Request;

class ParokiController extends Controller
{
    /**
     * Display a listing of paroki.
     */
    public function index(Request $request)
    {
        $query = Paroki::with('kevikepan')
            ->withCount('riwayatPemakai');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'ilike', "%{$search}%")
                    ->orWhere('alamat', 'ilike', "%{$search}%")
                    ->orWhere('kota', 'ilike', "%{$search}%");
            });
        }

        // Kevikepan filter
        if ($request->filled('kevikepan_id')) {
            $query->where('kevikepan_id', $request->kevikepan_id);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Sorting
        $sortColumn = $request->input('sort', 'nama');
        $sortDirection = $request->input('direction', 'asc');

        $allowedSorts = ['nama', 'kota'];
        if (!in_array($sortColumn, $allowedSorts)) {
            $sortColumn = 'nama';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        $paroki = $query->orderBy($sortColumn, $sortDirection)->paginate(20)->withQueryString();
        $kevikepan = Kevikepan::orderBy('nama')->get();

        return view('paroki.index', compact('paroki', 'kevikepan', 'sortColumn', 'sortDirection'));
    }

    /**
     * Show the form for creating a new paroki.
     */
    public function create()
    {
        $kevikepan = Kevikepan::orderBy('nama')->get();
        return view('paroki.create', compact('kevikepan'));
    }

    /**
     * Store a newly created paroki in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kevikepan_id' => 'required|exists:kevikepan,id',
            'alamat' => 'nullable|string|max:500',
            'kota' => 'nullable|string|max:100',
            'telepon' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $paroki = Paroki::create($validated);

        return redirect()
            ->route('paroki.index')
            ->with('success', 'Paroki "' . $paroki->nama . '" berhasil ditambahkan.');
    }

    /**
     * Display the specified paroki.
     */
    public function show(Paroki $paroki)
    {
        $paroki->load([
            'kevikepan',
            'riwayatPemakai' => function ($q) {
                $q->with('kendaraan.merk')->orderByDesc('tanggal_mulai')->limit(20);
            },
            'kendaraanDipinjam' => function ($q) {
                $q->with('merk')->where('is_dipinjam', true);
            },
        ]);

        return view('paroki.show', compact('paroki'));
    }

    /**
     * Show the form for editing the specified paroki.
     */
    public function edit(Paroki $paroki)
    {
        $kevikepan = Kevikepan::orderBy('nama')->get();
        return view('paroki.edit', compact('paroki', 'kevikepan'));
    }

    /**
     * Update the specified paroki in storage.
     */
    public function update(Request $request, Paroki $paroki)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kevikepan_id' => 'required|exists:kevikepan,id',
            'alamat' => 'nullable|string|max:500',
            'kota' => 'nullable|string|max:100',
            'telepon' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $paroki->update($validated);

        return redirect()
            ->route('paroki.index')
            ->with('success', 'Paroki "' . $paroki->nama . '" berhasil diperbarui.');
    }

    /**
     * Remove the specified paroki from storage.
     */
    public function destroy(Paroki $paroki)
    {
        // Check if paroki has related records
        if ($paroki->riwayatPemakai()->exists()) {
            return redirect()
                ->route('paroki.index')
                ->with('error', 'Paroki tidak dapat dihapus karena memiliki data riwayat pemakai.');
        }

        if ($paroki->kendaraanDipinjam()->exists()) {
            return redirect()
                ->route('paroki.index')
                ->with('error', 'Paroki tidak dapat dihapus karena masih memiliki kendaraan yang dipinjam.');
        }

        $nama = $paroki->nama;
        $paroki->delete();

        return redirect()
            ->route('paroki.index')
            ->with('success', 'Paroki "' . $nama . '" berhasil dihapus.');
    }
}
