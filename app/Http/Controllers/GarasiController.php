<?php

namespace App\Http\Controllers;

use App\Models\Garasi;
use App\Models\Kevikepan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GarasiController extends Controller
{
    /**
     * Display a listing of garasi.
     */
    public function index(Request $request)
    {
        $query = Garasi::with('kevikepan')
            ->withCount(['kendaraan' => fn($q) => $q->where('status', 'aktif')]);

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

        // Kota filter
        if ($request->filled('kota')) {
            $query->where('kota', $request->kota);
        }

        $garasi = $query->orderBy('nama')->paginate(15)->withQueryString();
        $kevikepan = Kevikepan::orderBy('nama')->get();
        $kotaList = Garasi::select('kota')->distinct()->orderBy('kota')->pluck('kota');

        return view('garasi.index', compact('garasi', 'kevikepan', 'kotaList'));
    }

    /**
     * Show the form for creating a new garasi.
     */
    public function create()
    {
        $kevikepan = Kevikepan::orderBy('nama')->get();
        return view('garasi.create', compact('kevikepan'));
    }

    /**
     * Store a newly created garasi in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kota' => 'required|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'kevikepan_id' => 'required|exists:kevikepan,id',
            'pic_name' => 'nullable|string|max:255',
            'pic_phone' => 'nullable|string|max:20',
        ]);

        $garasi = Garasi::create($validated);

        return redirect()
            ->route('garasi.index')
            ->with('success', 'Garasi "' . $garasi->nama . '" berhasil ditambahkan.');
    }

    /**
     * Display the specified garasi.
     */
    public function show(Garasi $garasi)
    {
        $garasi->load(['kevikepan', 'kendaraan' => function ($q) {
            $q->with('merk', 'pemegang')->orderBy('plat_nomor');
        }]);

        return view('garasi.show', compact('garasi'));
    }

    /**
     * Show the form for editing the specified garasi.
     */
    public function edit(Garasi $garasi)
    {
        $kevikepan = Kevikepan::orderBy('nama')->get();
        return view('garasi.edit', compact('garasi', 'kevikepan'));
    }

    /**
     * Update the specified garasi in storage.
     */
    public function update(Request $request, Garasi $garasi)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kota' => 'required|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'kevikepan_id' => 'required|exists:kevikepan,id',
            'pic_name' => 'nullable|string|max:255',
            'pic_phone' => 'nullable|string|max:20',
        ]);

        $garasi->update($validated);

        return redirect()
            ->route('garasi.index')
            ->with('success', 'Garasi "' . $garasi->nama . '" berhasil diperbarui.');
    }

    /**
     * Remove the specified garasi from storage.
     */
    public function destroy(Garasi $garasi)
    {
        // Check if garasi has kendaraan
        if ($garasi->kendaraan()->exists()) {
            return redirect()
                ->route('garasi.index')
                ->with('error', 'Garasi tidak dapat dihapus karena masih memiliki kendaraan.');
        }

        $nama = $garasi->nama;
        $garasi->delete();

        return redirect()
            ->route('garasi.index')
            ->with('success', 'Garasi "' . $nama . '" berhasil dihapus.');
    }
}
