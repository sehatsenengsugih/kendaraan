<?php

namespace App\Http\Controllers;

use App\Models\Lembaga;
use Illuminate\Http\Request;

class LembagaController extends Controller
{
    /**
     * Display a listing of lembaga.
     */
    public function index(Request $request)
    {
        $query = Lembaga::withCount(['riwayatPemakai', 'kendaraanDimiliki']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'ilike', "%{$search}%")
                    ->orWhere('alamat', 'ilike', "%{$search}%")
                    ->orWhere('kota', 'ilike', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $lembaga = $query->orderBy('nama')->paginate(20)->withQueryString();

        return view('lembaga.index', compact('lembaga'));
    }

    /**
     * Show the form for creating a new lembaga.
     */
    public function create()
    {
        return view('lembaga.create');
    }

    /**
     * Store a newly created lembaga in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:500',
            'kota' => 'nullable|string|max:100',
            'telepon' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $lembaga = Lembaga::create($validated);

        return redirect()
            ->route('lembaga.index')
            ->with('success', 'Lembaga "' . $lembaga->nama . '" berhasil ditambahkan.');
    }

    /**
     * Display the specified lembaga.
     */
    public function show(Lembaga $lembaga)
    {
        $lembaga->load([
            'riwayatPemakai' => function ($q) {
                $q->with('kendaraan.merk')->orderByDesc('tanggal_mulai')->limit(20);
            },
            'kendaraanDimiliki' => function ($q) {
                $q->with('merk');
            },
        ]);

        return view('lembaga.show', compact('lembaga'));
    }

    /**
     * Show the form for editing the specified lembaga.
     */
    public function edit(Lembaga $lembaga)
    {
        return view('lembaga.edit', compact('lembaga'));
    }

    /**
     * Update the specified lembaga in storage.
     */
    public function update(Request $request, Lembaga $lembaga)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:500',
            'kota' => 'nullable|string|max:100',
            'telepon' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $lembaga->update($validated);

        return redirect()
            ->route('lembaga.index')
            ->with('success', 'Lembaga "' . $lembaga->nama . '" berhasil diperbarui.');
    }

    /**
     * Remove the specified lembaga from storage.
     */
    public function destroy(Lembaga $lembaga)
    {
        // Check if lembaga has related records
        if ($lembaga->riwayatPemakai()->exists()) {
            return redirect()
                ->route('lembaga.index')
                ->with('error', 'Lembaga tidak dapat dihapus karena memiliki data riwayat pemakai.');
        }

        if ($lembaga->kendaraanDimiliki()->exists()) {
            return redirect()
                ->route('lembaga.index')
                ->with('error', 'Lembaga tidak dapat dihapus karena masih memiliki kendaraan.');
        }

        $nama = $lembaga->nama;
        $lembaga->delete();

        return redirect()
            ->route('lembaga.index')
            ->with('success', 'Lembaga "' . $nama . '" berhasil dihapus.');
    }
}
