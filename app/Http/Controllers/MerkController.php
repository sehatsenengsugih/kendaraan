<?php

namespace App\Http\Controllers;

use App\Models\Merk;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MerkController extends Controller
{
    /**
     * Display a listing of merk.
     */
    public function index(Request $request)
    {
        $query = Merk::withCount('kendaraan');

        // Search filter
        if ($request->filled('search')) {
            $query->where('nama', 'ilike', "%{$request->search}%");
        }

        // Jenis filter
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        $merk = $query->orderBy('nama')->paginate(20)->withQueryString();

        return view('merk.index', compact('merk'));
    }

    /**
     * Show the form for creating a new merk.
     */
    public function create()
    {
        return view('merk.create');
    }

    /**
     * Store a newly created merk in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:100',
                Rule::unique('merk')->where(function ($query) use ($request) {
                    return $query->where('jenis', $request->jenis)->whereNull('deleted_at');
                }),
            ],
            'jenis' => 'required|in:mobil,motor',
        ], [
            'nama.unique' => 'Merk dengan nama dan jenis yang sama sudah ada.',
        ]);

        $merk = Merk::create($validated);

        return redirect()
            ->route('merk.index')
            ->with('success', 'Merk "' . $merk->nama . '" berhasil ditambahkan.');
    }

    /**
     * Display the specified merk.
     */
    public function show(Merk $merk)
    {
        $merk->load(['kendaraan' => function ($q) {
            $q->with('garasi')->orderBy('plat_nomor')->limit(20);
        }]);
        $merk->loadCount('kendaraan');

        return view('merk.show', compact('merk'));
    }

    /**
     * Show the form for editing the specified merk.
     */
    public function edit(Merk $merk)
    {
        return view('merk.edit', compact('merk'));
    }

    /**
     * Update the specified merk in storage.
     */
    public function update(Request $request, Merk $merk)
    {
        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:100',
                Rule::unique('merk')->where(function ($query) use ($request) {
                    return $query->where('jenis', $request->jenis)->whereNull('deleted_at');
                })->ignore($merk->id),
            ],
            'jenis' => 'required|in:mobil,motor',
        ], [
            'nama.unique' => 'Merk dengan nama dan jenis yang sama sudah ada.',
        ]);

        $merk->update($validated);

        return redirect()
            ->route('merk.index')
            ->with('success', 'Merk "' . $merk->nama . '" berhasil diperbarui.');
    }

    /**
     * Remove the specified merk from storage.
     */
    public function destroy(Merk $merk)
    {
        // Check if merk has kendaraan
        if ($merk->kendaraan()->exists()) {
            return redirect()
                ->route('merk.index')
                ->with('error', 'Merk tidak dapat dihapus karena masih memiliki kendaraan.');
        }

        $nama = $merk->nama;
        $merk->delete();

        return redirect()
            ->route('merk.index')
            ->with('success', 'Merk "' . $nama . '" berhasil dihapus.');
    }
}
