<?php

declare(strict_types=1);

namespace App\View\Composers;

use App\Models\Pajak;
use App\Models\Servis;
use Illuminate\View\View;

class NotificationComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        if (!auth()->check()) {
            $view->with('notifications', collect());
            $view->with('notificationCount', 0);
            return;
        }

        $notifications = collect();

        // Get overdue pajak (terlambat)
        $pajakTerlambat = Pajak::with(['kendaraan.merk'])
            ->where('status', '!=', 'lunas')
            ->where('tanggal_jatuh_tempo', '<', now())
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->limit(5)
            ->get();

        foreach ($pajakTerlambat as $pajak) {
            $notifications->push([
                'type' => 'pajak_terlambat',
                'icon' => 'fa-exclamation-circle',
                'color' => 'error',
                'title' => 'Pajak Terlambat',
                'message' => ($pajak->kendaraan->merk->nama ?? 'Kendaraan') . ' - ' . $pajak->kendaraan->no_plat,
                'detail' => 'Jatuh tempo: ' . $pajak->tanggal_jatuh_tempo->format('d M Y'),
                'url' => route('pajak.show', $pajak),
                'time' => $pajak->tanggal_jatuh_tempo->diffForHumans(),
            ]);
        }

        // Get pajak due soon (dalam 30 hari)
        $pajakSegera = Pajak::with(['kendaraan.merk'])
            ->where('status', '!=', 'lunas')
            ->whereBetween('tanggal_jatuh_tempo', [now(), now()->addDays(30)])
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->limit(5)
            ->get();

        foreach ($pajakSegera as $pajak) {
            $notifications->push([
                'type' => 'pajak_segera',
                'icon' => 'fa-clock',
                'color' => 'warning',
                'title' => 'Pajak Akan Jatuh Tempo',
                'message' => ($pajak->kendaraan->merk->nama ?? 'Kendaraan') . ' - ' . $pajak->kendaraan->no_plat,
                'detail' => 'Jatuh tempo: ' . $pajak->tanggal_jatuh_tempo->format('d M Y'),
                'url' => route('pajak.show', $pajak),
                'time' => $pajak->tanggal_jatuh_tempo->diffForHumans(),
            ]);
        }

        // Get servis due soon
        $servisSegera = Servis::with(['kendaraan.merk'])
            ->where('status', 'selesai')
            ->whereNotNull('servis_berikutnya')
            ->whereBetween('servis_berikutnya', [now(), now()->addDays(30)])
            ->orderBy('servis_berikutnya', 'asc')
            ->limit(5)
            ->get();

        foreach ($servisSegera as $servis) {
            $notifications->push([
                'type' => 'servis_segera',
                'icon' => 'fa-wrench',
                'color' => 'info',
                'title' => 'Servis Berikutnya',
                'message' => ($servis->kendaraan->merk->nama ?? 'Kendaraan') . ' - ' . $servis->kendaraan->no_plat,
                'detail' => 'Jadwal: ' . $servis->servis_berikutnya->format('d M Y'),
                'url' => route('servis.show', $servis),
                'time' => $servis->servis_berikutnya->diffForHumans(),
            ]);
        }

        // Sort by urgency (terlambat first, then by date)
        $notifications = $notifications->sortBy(function ($item) {
            $priority = match ($item['type']) {
                'pajak_terlambat' => 0,
                'pajak_segera' => 1,
                'servis_segera' => 2,
                default => 3,
            };
            return $priority;
        })->take(10);

        $view->with('notifications', $notifications);
        $view->with('notificationCount', $notifications->count());
    }
}
