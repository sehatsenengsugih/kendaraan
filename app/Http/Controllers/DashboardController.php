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

        // Distribusi umur kendaraan berdasarkan tahun pembuatan (hanya kendaraan aktif)
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
            ->where('status', 'aktif')
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

        // Jumlah kendaraan per merk dan jenis (mobil/motor) - hanya kendaraan aktif
        $kendaraanPerMerk = Kendaraan::selectRaw('merk_id, jenis, COUNT(*) as jumlah')
            ->where('status', 'aktif')
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

        // Upcoming events untuk widget kalender (14 hari ke depan)
        $upcomingEvents = collect();

        // Pajak upcoming
        $upcomingPajak = Pajak::with(['kendaraan.merk'])
            ->where('status', '!=', 'lunas')
            ->whereBetween('tanggal_jatuh_tempo', [$today, $today->copy()->addDays(14)])
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->get()
            ->map(function ($pajak) {
                return [
                    'type' => 'pajak',
                    'date' => $pajak->tanggal_jatuh_tempo,
                    'title' => 'Pajak ' . $pajak->kendaraan->plat_nomor,
                    'subtitle' => ($pajak->kendaraan->merk->nama ?? '') . ' - ' . ucfirst($pajak->jenis),
                    'color' => $pajak->isOverdue() ? 'error' : ($pajak->days_until_due <= 7 ? 'warning' : 'blue'),
                    'days' => $pajak->days_until_due,
                    'url' => route('pajak.show', $pajak),
                ];
            });

        // Servis upcoming (tanggal servis yang dijadwalkan)
        $upcomingServis = Servis::with(['kendaraan.merk'])
            ->where('status', Servis::STATUS_DIJADWALKAN)
            ->whereBetween('tanggal_servis', [$today, $today->copy()->addDays(14)])
            ->orderBy('tanggal_servis', 'asc')
            ->get()
            ->map(function ($servis) {
                return [
                    'type' => 'servis',
                    'date' => $servis->tanggal_servis,
                    'title' => 'Servis ' . $servis->kendaraan->plat_nomor,
                    'subtitle' => ($servis->kendaraan->merk->nama ?? '') . ' - ' . ucfirst($servis->jenis),
                    'color' => 'purple',
                    'days' => now()->startOfDay()->diffInDays($servis->tanggal_servis, false),
                    'url' => route('servis.show', $servis),
                ];
            });

        // Servis berikutnya (reminder)
        $upcomingNextServis = Servis::with(['kendaraan.merk'])
            ->where('status', Servis::STATUS_SELESAI)
            ->whereNotNull('servis_berikutnya')
            ->whereBetween('servis_berikutnya', [$today, $today->copy()->addDays(14)])
            ->orderBy('servis_berikutnya', 'asc')
            ->get()
            ->map(function ($servis) {
                return [
                    'type' => 'next_servis',
                    'date' => $servis->servis_berikutnya,
                    'title' => 'Servis Berikutnya ' . $servis->kendaraan->plat_nomor,
                    'subtitle' => ($servis->kendaraan->merk->nama ?? '') . ' - ' . ucfirst($servis->jenis),
                    'color' => 'indigo',
                    'days' => $servis->days_until_next_service,
                    'url' => route('servis.show', $servis),
                ];
            });

        $upcomingEvents = $upcomingPajak->merge($upcomingServis)->merge($upcomingNextServis)
            ->sortBy('date')
            ->take(10)
            ->values();

        // Mini calendar data - events dalam bulan ini
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();

        // Pajak dalam bulan ini
        $monthPajak = Pajak::where('status', '!=', 'lunas')
            ->whereBetween('tanggal_jatuh_tempo', [$monthStart, $monthEnd])
            ->get()
            ->map(function ($pajak) {
                return [
                    'date' => $pajak->tanggal_jatuh_tempo->format('Y-m-d'),
                    'type' => 'pajak',
                    'color' => $pajak->isOverdue() ? 'error' : ($pajak->days_until_due <= 7 ? 'warning' : 'blue'),
                ];
            });

        // Servis dalam bulan ini
        $monthServis = Servis::where('status', Servis::STATUS_DIJADWALKAN)
            ->whereBetween('tanggal_servis', [$monthStart, $monthEnd])
            ->get()
            ->map(function ($servis) {
                return [
                    'date' => $servis->tanggal_servis->format('Y-m-d'),
                    'type' => 'servis',
                    'color' => 'purple',
                ];
            });

        // Servis berikutnya dalam bulan ini
        $monthNextServis = Servis::where('status', Servis::STATUS_SELESAI)
            ->whereNotNull('servis_berikutnya')
            ->whereBetween('servis_berikutnya', [$monthStart, $monthEnd])
            ->get()
            ->map(function ($servis) {
                return [
                    'date' => $servis->servis_berikutnya->format('Y-m-d'),
                    'type' => 'next_servis',
                    'color' => 'indigo',
                ];
            });

        // Gabungkan dan kelompokkan berdasarkan tanggal
        $calendarEvents = $monthPajak->merge($monthServis)->merge($monthNextServis)
            ->groupBy('date')
            ->map(function ($events) {
                return $events->pluck('color')->unique()->values()->all();
            });

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
            'merkMotor',
            'upcomingEvents',
            'calendarEvents'
        ));
    }
}
