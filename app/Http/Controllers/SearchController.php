<?php

namespace App\Http\Controllers;

use App\Models\Garasi;
use App\Models\Kendaraan;
use App\Models\Lembaga;
use App\Models\Paroki;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Global search untuk semua entitas.
     */
    public function search(Request $request): JsonResponse
    {
        $query = trim($request->get('q', ''));

        if (strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $results = [];

        // Cari Kendaraan (prioritas utama)
        $kendaraan = Kendaraan::with(['merk', 'garasi'])
            ->where(function ($q) use ($query) {
                $q->where('plat_nomor', 'ILIKE', "%{$query}%")
                    ->orWhere('nama_model', 'ILIKE', "%{$query}%")
                    ->orWhere('nomor_bpkb', 'ILIKE', "%{$query}%")
                    ->orWhere('nomor_rangka', 'ILIKE', "%{$query}%")
                    ->orWhere('nomor_mesin', 'ILIKE', "%{$query}%")
                    ->orWhere('pemegang_nama', 'ILIKE', "%{$query}%")
                    ->orWhere('catatan', 'ILIKE', "%{$query}%")
                    ->orWhereHas('merk', function ($mq) use ($query) {
                        $mq->where('nama', 'ILIKE', "%{$query}%");
                    })
                    ->orWhereHas('garasi', function ($gq) use ($query) {
                        $gq->where('nama', 'ILIKE', "%{$query}%")
                            ->orWhere('kota', 'ILIKE', "%{$query}%");
                    })
                    ->orWhereHas('riwayatPemakai', function ($rq) use ($query) {
                        $rq->where('nama_pemakai', 'ILIKE', "%{$query}%");
                    });
            })
            ->limit(5)
            ->get();

        foreach ($kendaraan as $k) {
            $results[] = [
                'type' => 'kendaraan',
                'type_label' => $k->jenis === 'motor' ? 'Motor' : 'Mobil',
                'icon' => $k->jenis === 'motor' ? 'fa-motorcycle' : 'fa-car',
                'title' => ($k->merk->nama ?? '') . ' ' . $k->nama_model,
                'subtitle' => $k->plat_nomor,
                'description' => $k->garasi->nama ?? '-',
                'url' => route('kendaraan.show', $k->id),
                'avatar' => $k->avatar_path ? asset('storage/' . $k->avatar_path) : null,
            ];
        }

        // Cari Paroki
        $paroki = Paroki::with('kevikepan')
            ->where('nama', 'ILIKE', "%{$query}%")
            ->orWhere('kota', 'ILIKE', "%{$query}%")
            ->orWhere('alamat', 'ILIKE', "%{$query}%")
            ->limit(3)
            ->get();

        foreach ($paroki as $p) {
            $results[] = [
                'type' => 'paroki',
                'type_label' => 'Paroki',
                'icon' => 'fa-church',
                'title' => $p->nama,
                'subtitle' => $p->kevikepan->nama ?? '-',
                'description' => $p->kota ?? '-',
                'url' => route('paroki.show', $p->id),
                'avatar' => null,
            ];
        }

        // Cari Lembaga
        $lembaga = Lembaga::where('nama', 'ILIKE', "%{$query}%")
            ->orWhere('kota', 'ILIKE', "%{$query}%")
            ->orWhere('alamat', 'ILIKE', "%{$query}%")
            ->limit(3)
            ->get();

        foreach ($lembaga as $l) {
            $results[] = [
                'type' => 'lembaga',
                'type_label' => 'Lembaga',
                'icon' => 'fa-building',
                'title' => $l->nama,
                'subtitle' => $l->kota ?? '-',
                'description' => $l->alamat ?? '-',
                'url' => route('lembaga.show', $l->id),
                'avatar' => null,
            ];
        }

        // Cari Garasi
        $garasi = Garasi::with('kevikepan')
            ->where('nama', 'ILIKE', "%{$query}%")
            ->orWhere('kota', 'ILIKE', "%{$query}%")
            ->orWhere('nama_paroki_lembaga', 'ILIKE', "%{$query}%")
            ->limit(3)
            ->get();

        foreach ($garasi as $g) {
            $results[] = [
                'type' => 'garasi',
                'type_label' => 'Garasi',
                'icon' => 'fa-warehouse',
                'title' => $g->nama,
                'subtitle' => $g->kevikepan->nama ?? '-',
                'description' => $g->kota ?? '-',
                'url' => route('garasi.show', $g->id),
                'avatar' => null,
            ];
        }

        return response()->json([
            'query' => $query,
            'count' => count($results),
            'results' => $results,
        ]);
    }
}
