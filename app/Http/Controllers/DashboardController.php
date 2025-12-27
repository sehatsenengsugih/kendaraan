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
     * Menampilkan halaman dashboard.
     */
    public function index()
    {
        // Statistik kendaraan (total, mobil, motor)
        $kendaraanStats = [
            'total' => Kendaraan::count(),
            'mobil' => Kendaraan::where('jenis', 'mobil')->count(),
            'motor' => Kendaraan::where('jenis', 'motor')->count(),
        ];

        // Statistik kendaraan aktif
        $kendaraanAktifStats = [
            'total' => Kendaraan::where('status', 'aktif')->count(),
            'mobil' => Kendaraan::where('status', 'aktif')->where('jenis', 'mobil')->count(),
            'motor' => Kendaraan::where('status', 'aktif')->where('jenis', 'motor')->count(),
        ];

        // Statistik pajak (akan jatuh tempo & terlambat)
        $pajakStats = [
            'due_soon' => Pajak::where('status', '!=', 'lunas')
                ->whereBetween('tanggal_jatuh_tempo', [now(), now()->addDays(30)])
                ->count(),
            'overdue' => Pajak::where('status', '!=', 'lunas')
                ->where('tanggal_jatuh_tempo', '<', now())
                ->count(),
        ];

        // Daftar pajak jatuh tempo (5 teratas)
        $pajakJatuhTempo = Pajak::with(['kendaraan.merk'])
            ->where('status', '!=', 'lunas')
            ->where('tanggal_jatuh_tempo', '<=', now()->addDays(30))
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->limit(5)
            ->get();

        // Jumlah kendaraan per garasi/kevikepan
        $kendaraanPerGarasi = Garasi::withCount('kendaraan')
            ->orderBy('kendaraan_count', 'desc')
            ->limit(6)
            ->get();

        // Jumlah penugasan aktif
        $penugasanAktif = Penugasan::aktif()->count();

        // Distribusi umur kendaraan berdasarkan tahun pembuatan
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

        // Pastikan semua rentang umur ada dengan urutan yang benar
        $orderedRanges = ['0-5 tahun', '6-10 tahun', '11-15 tahun', '16-20 tahun', '> 20 tahun'];
        $umurData = [];
        foreach ($orderedRanges as $range) {
            $found = $umurKendaraan->firstWhere('rentang_umur', $range);
            $umurData[$range] = $found ? $found->jumlah : 0;
        }

        // Pengingat pajak - dikelompokkan berdasarkan urgensi
        $today = now()->startOfDay();

        // Pajak terlambat (sudah lewat jatuh tempo)
        $pajakTerlambat = Pajak::with(['kendaraan.merk'])
            ->where('status', '!=', 'lunas')
            ->where('tanggal_jatuh_tempo', '<', $today)
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->get();

        // Pajak jatuh tempo dalam 7 hari ke depan
        $pajak7Hari = Pajak::with(['kendaraan.merk'])
            ->where('status', '!=', 'lunas')
            ->whereBetween('tanggal_jatuh_tempo', [$today, $today->copy()->addDays(7)])
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->get();

        // Pajak jatuh tempo dalam 8-30 hari ke depan
        $pajak30Hari = Pajak::with(['kendaraan.merk'])
            ->where('status', '!=', 'lunas')
            ->whereBetween('tanggal_jatuh_tempo', [$today->copy()->addDays(8), $today->copy()->addDays(30)])
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->get();

        // Pajak jatuh tempo dalam 31 hari - 6 bulan ke depan
        $pajak6Bulan = Pajak::with(['kendaraan.merk'])
            ->where('status', '!=', 'lunas')
            ->whereBetween('tanggal_jatuh_tempo', [$today->copy()->addDays(31), $today->copy()->addMonths(6)])
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->get();

        // Jumlah kendaraan per merk dan jenis (mobil/motor)
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

        // Kelompokkan berdasarkan jenis untuk grafik
        $merkMobil = $kendaraanPerMerk->where('jenis', 'mobil')->sortByDesc('jumlah')->values();
        $merkMotor = $kendaraanPerMerk->where('jenis', 'motor')->sortByDesc('jumlah')->values();

        return view('dashboard', compact(
            'kendaraanStats',
            'kendaraanAktifStats',
            'pajakStats',
            'pajakJatuhTempo',
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
