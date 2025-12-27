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

        // Umur kendaraan distribution
        $currentYear = (int) date('Y');
        $umurKendaraan = Kendaraan::selectRaw("
                CASE
                    WHEN (? - tahun_pembuatan) <= 5 THEN '0-5 tahun'
                    WHEN (? - tahun_pembuatan) <= 10 THEN '6-10 tahun'
                    WHEN (? - tahun_pembuatan) <= 15 THEN '11-15 tahun'
                    WHEN (? - tahun_pembuatan) <= 20 THEN '16-20 tahun'
                    ELSE '> 20 tahun'
                END as rentang_umur,
                COUNT(*) as jumlah
            ", [$currentYear, $currentYear, $currentYear, $currentYear])
            ->whereNotNull('tahun_pembuatan')
            ->groupBy('rentang_umur')
            ->orderByRaw('MIN(? - tahun_pembuatan)', [$currentYear])
            ->get();

        // Ensure all ranges exist with proper order
        $orderedRanges = ['0-5 tahun', '6-10 tahun', '11-15 tahun', '16-20 tahun', '> 20 tahun'];
        $umurData = [];
        foreach ($orderedRanges as $range) {
            $found = $umurKendaraan->firstWhere('rentang_umur', $range);
            $umurData[$range] = $found ? $found->jumlah : 0;
        }

        // Pajak reminder - grouped by urgency
        $today = now()->startOfDay();

        // Terlambat (overdue)
        $pajakTerlambat = Pajak::with(['kendaraan.merk'])
            ->where('status', '!=', 'lunas')
            ->where('tanggal_jatuh_tempo', '<', $today)
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->get();

        // 7 hari ke depan
        $pajak7Hari = Pajak::with(['kendaraan.merk'])
            ->where('status', '!=', 'lunas')
            ->whereBetween('tanggal_jatuh_tempo', [$today, $today->copy()->addDays(7)])
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->get();

        // 8-30 hari ke depan
        $pajak30Hari = Pajak::with(['kendaraan.merk'])
            ->where('status', '!=', 'lunas')
            ->whereBetween('tanggal_jatuh_tempo', [$today->copy()->addDays(8), $today->copy()->addDays(30)])
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->get();

        // 31 hari - 6 bulan ke depan
        $pajak6Bulan = Pajak::with(['kendaraan.merk'])
            ->where('status', '!=', 'lunas')
            ->whereBetween('tanggal_jatuh_tempo', [$today->copy()->addDays(31), $today->copy()->addMonths(6)])
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->get();

        // Kendaraan per Merk dan Jenis
        $kendaraanPerMerk = Kendaraan::selectRaw('merk_id, jenis, COUNT(*) as jumlah')
            ->whereNotNull('merk_id')
            ->groupBy('merk_id', 'jenis')
            ->with('merk:id,nama')
            ->get()
            ->map(function ($item) {
                return [
                    'merk' => $item->merk->nama ?? 'Unknown',
                    'jenis' => $item->jenis,
                    'jumlah' => $item->jumlah,
                ];
            });

        // Group by jenis for chart
        $merkMobil = $kendaraanPerMerk->where('jenis', 'mobil')->sortByDesc('jumlah')->values();
        $merkMotor = $kendaraanPerMerk->where('jenis', 'motor')->sortByDesc('jumlah')->values();

        return view('dashboard', compact(
            'kendaraanStats',
            'pajakStats',
            'servisStats',
            'pajakJatuhTempo',
            'kendaraanPerGarasi',
            'penugasanAktif',
            'umurData',
            'pajakTerlambat',
            'pajak7Hari',
            'pajak30Hari',
            'pajak6Bulan',
            'merkMobil',
            'merkMotor'
        ));
    }
}
