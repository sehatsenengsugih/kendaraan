<?php

namespace App\Http\Controllers;

use App\Models\Garasi;
use App\Models\Kendaraan;
use App\Models\Pajak;
use App\Models\Penugasan;
use App\Models\Servis;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the dashboard.
     */
    public function index()
    {
        // Kendaraan stats
        $kendaraanStats = [
            'total' => Kendaraan::count(),
            'mobil' => Kendaraan::where('jenis', 'mobil')->count(),
            'motor' => Kendaraan::where('jenis', 'motor')->count(),
        ];

        // Pajak stats
        $pajakStats = [
            'due_soon' => Pajak::where('status', '!=', 'lunas')
                ->whereBetween('tanggal_jatuh_tempo', [now(), now()->addDays(30)])
                ->count(),
            'overdue' => Pajak::where('status', '!=', 'lunas')
                ->where('tanggal_jatuh_tempo', '<', now())
                ->count(),
        ];

        // Servis stats - next service due soon
        $servisStats = [
            'due_soon' => Servis::where('status', 'selesai')
                ->whereNotNull('servis_berikutnya')
                ->whereBetween('servis_berikutnya', [now(), now()->addDays(30)])
                ->count(),
        ];

        // Pajak jatuh tempo list (top 5)
        $pajakJatuhTempo = Pajak::with(['kendaraan.merk'])
            ->where('status', '!=', 'lunas')
            ->where('tanggal_jatuh_tempo', '<=', now()->addDays(30))
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->limit(5)
            ->get();

        // Kendaraan per garasi/kevikepan
        $kendaraanPerGarasi = Garasi::withCount('kendaraan')
            ->orderBy('kendaraan_count', 'desc')
            ->limit(6)
            ->get();

        // Penugasan aktif
        $penugasanAktif = Penugasan::aktif()->count();

        return view('dashboard', compact(
            'kendaraanStats',
            'pajakStats',
            'servisStats',
            'pajakJatuhTempo',
            'kendaraanPerGarasi',
            'penugasanAktif'
        ));
    }
}
