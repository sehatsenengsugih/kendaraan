<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Pajak;
use App\Models\Servis;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CalendarController extends Controller
{
    /**
     * Display the calendar page.
     */
    public function index(): View
    {
        $kendaraanList = Kendaraan::aktif()
            ->with('merk')
            ->orderBy('plat_nomor')
            ->get();

        return view('calendar.index', compact('kendaraanList'));
    }

    /**
     * Get calendar events for FullCalendar.
     */
    public function events(Request $request): JsonResponse
    {
        $start = Carbon::parse($request->input('start'));
        $end = Carbon::parse($request->input('end'));

        $events = [];

        // Fetch Pajak events
        $pajakEvents = Pajak::with(['kendaraan.merk'])
            ->whereBetween('tanggal_jatuh_tempo', [$start, $end])
            ->get();

        foreach ($pajakEvents as $pajak) {
            $events[] = $this->formatPajakEvent($pajak);
        }

        // Fetch Servis events (tanggal_servis)
        $servisEvents = Servis::with(['kendaraan.merk'])
            ->whereBetween('tanggal_servis', [$start, $end])
            ->get();

        foreach ($servisEvents as $servis) {
            $events[] = $this->formatServisEvent($servis);
        }

        // Fetch upcoming servis_berikutnya events
        $nextServisEvents = Servis::with(['kendaraan.merk'])
            ->where('status', Servis::STATUS_SELESAI)
            ->whereNotNull('servis_berikutnya')
            ->whereBetween('servis_berikutnya', [$start, $end])
            ->get();

        foreach ($nextServisEvents as $servis) {
            $events[] = $this->formatNextServisEvent($servis);
        }

        return response()->json($events);
    }

    /**
     * Store a new Pajak from calendar.
     */
    public function storePajak(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kendaraan_id' => ['required', 'exists:kendaraan,id'],
            'jenis' => ['required', 'in:tahunan,lima_tahunan'],
            'tanggal_jatuh_tempo' => ['required', 'date'],
            'nominal' => ['nullable', 'numeric', 'min:0'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $validated['status'] = Pajak::STATUS_BELUM_BAYAR;
        $validated['created_by'] = auth()->id();

        $pajak = Pajak::create($validated);
        $pajak->load('kendaraan.merk');

        return response()->json([
            'success' => true,
            'message' => 'Pajak berhasil ditambahkan',
            'event' => $this->formatPajakEvent($pajak),
        ]);
    }

    /**
     * Update a Pajak from calendar.
     */
    public function updatePajak(Request $request, Pajak $pajak): JsonResponse
    {
        $validated = $request->validate([
            'kendaraan_id' => ['required', 'exists:kendaraan,id'],
            'jenis' => ['required', 'in:tahunan,lima_tahunan'],
            'tanggal_jatuh_tempo' => ['required', 'date'],
            'nominal' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:belum_bayar,lunas,terlambat'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $pajak->update($validated);
        $pajak->load('kendaraan.merk');

        return response()->json([
            'success' => true,
            'message' => 'Pajak berhasil diperbarui',
            'event' => $this->formatPajakEvent($pajak),
        ]);
    }

    /**
     * Delete a Pajak from calendar.
     */
    public function destroyPajak(Pajak $pajak): JsonResponse
    {
        $pajak->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pajak berhasil dihapus',
        ]);
    }

    /**
     * Store a new Servis from calendar.
     */
    public function storeServis(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kendaraan_id' => ['required', 'exists:kendaraan,id'],
            'jenis' => ['required', 'in:rutin,perbaikan,darurat,overhaul'],
            'tanggal_servis' => ['required', 'date'],
            'bengkel' => ['nullable', 'string', 'max:255'],
            'biaya' => ['nullable', 'numeric', 'min:0'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $validated['status'] = Servis::STATUS_DIJADWALKAN;
        $validated['created_by'] = auth()->id();

        $servis = Servis::create($validated);
        $servis->load('kendaraan.merk');

        return response()->json([
            'success' => true,
            'message' => 'Servis berhasil ditambahkan',
            'event' => $this->formatServisEvent($servis),
        ]);
    }

    /**
     * Update a Servis from calendar.
     */
    public function updateServis(Request $request, Servis $servis): JsonResponse
    {
        $validated = $request->validate([
            'kendaraan_id' => ['required', 'exists:kendaraan,id'],
            'jenis' => ['required', 'in:rutin,perbaikan,darurat,overhaul'],
            'tanggal_servis' => ['required', 'date'],
            'bengkel' => ['nullable', 'string', 'max:255'],
            'biaya' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:dijadwalkan,dalam_proses,selesai,dibatalkan'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $servis->update($validated);
        $servis->load('kendaraan.merk');

        return response()->json([
            'success' => true,
            'message' => 'Servis berhasil diperbarui',
            'event' => $this->formatServisEvent($servis),
        ]);
    }

    /**
     * Delete a Servis from calendar.
     */
    public function destroyServis(Servis $servis): JsonResponse
    {
        $servis->delete();

        return response()->json([
            'success' => true,
            'message' => 'Servis berhasil dihapus',
        ]);
    }

    /**
     * Move an event (drag-drop reschedule).
     */
    public function moveEvent(Request $request, string $type, int $id): JsonResponse
    {
        $request->validate([
            'start' => ['required', 'date'],
        ]);

        $newDate = Carbon::parse($request->input('start'));

        if ($type === 'pajak') {
            $pajak = Pajak::findOrFail($id);
            $pajak->update(['tanggal_jatuh_tempo' => $newDate]);

            return response()->json([
                'success' => true,
                'message' => 'Tanggal pajak berhasil diubah',
            ]);
        }

        if ($type === 'servis') {
            $servis = Servis::findOrFail($id);
            $servis->update(['tanggal_servis' => $newDate]);

            return response()->json([
                'success' => true,
                'message' => 'Tanggal servis berhasil diubah',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Tipe event tidak valid',
        ], 400);
    }

    /**
     * Format Pajak event for FullCalendar.
     */
    private function formatPajakEvent(Pajak $pajak): array
    {
        $kendaraan = $pajak->kendaraan;
        $merkNama = $kendaraan->merk->nama ?? '';
        $platNomor = $kendaraan->plat_nomor ?? '';

        return [
            'id' => 'pajak_' . $pajak->id,
            'title' => 'Pajak ' . $platNomor,
            'start' => $pajak->tanggal_jatuh_tempo->format('Y-m-d'),
            'color' => $this->getPajakColor($pajak),
            'textColor' => '#FFFFFF',
            'extendedProps' => [
                'type' => 'pajak',
                'modelId' => $pajak->id,
                'kendaraan' => $platNomor . ' - ' . $merkNama . ' ' . ($kendaraan->nama_model ?? ''),
                'kendaraan_id' => $pajak->kendaraan_id,
                'jenis' => $pajak->jenis,
                'jenisLabel' => Pajak::JENIS_OPTIONS[$pajak->jenis] ?? $pajak->jenis,
                'status' => $pajak->status,
                'statusLabel' => Pajak::STATUS_OPTIONS[$pajak->status] ?? $pajak->status,
                'nominal' => $pajak->nominal,
                'nominalFormatted' => 'Rp ' . number_format($pajak->nominal ?? 0, 0, ',', '.'),
                'catatan' => $pajak->catatan,
                'isOverdue' => $pajak->isOverdue(),
                'daysUntilDue' => $pajak->days_until_due,
                'viewUrl' => route('pajak.show', $pajak),
                'editUrl' => route('pajak.edit', $pajak),
            ],
        ];
    }

    /**
     * Format Servis event for FullCalendar.
     */
    private function formatServisEvent(Servis $servis): array
    {
        $kendaraan = $servis->kendaraan;
        $merkNama = $kendaraan->merk->nama ?? '';
        $platNomor = $kendaraan->plat_nomor ?? '';

        return [
            'id' => 'servis_' . $servis->id,
            'title' => 'Servis ' . $platNomor,
            'start' => $servis->tanggal_servis->format('Y-m-d'),
            'end' => $servis->tanggal_selesai ? $servis->tanggal_selesai->addDay()->format('Y-m-d') : null,
            'color' => $this->getServisColor($servis),
            'textColor' => '#FFFFFF',
            'extendedProps' => [
                'type' => 'servis',
                'modelId' => $servis->id,
                'kendaraan' => $platNomor . ' - ' . $merkNama . ' ' . ($kendaraan->nama_model ?? ''),
                'kendaraan_id' => $servis->kendaraan_id,
                'jenis' => $servis->jenis,
                'jenisLabel' => Servis::JENIS_OPTIONS[$servis->jenis] ?? $servis->jenis,
                'status' => $servis->status,
                'statusLabel' => Servis::STATUS_OPTIONS[$servis->status] ?? $servis->status,
                'bengkel' => $servis->bengkel,
                'biaya' => $servis->biaya,
                'biayaFormatted' => 'Rp ' . number_format($servis->biaya ?? 0, 0, ',', '.'),
                'deskripsi' => $servis->deskripsi,
                'catatan' => $servis->catatan,
                'viewUrl' => route('servis.show', $servis),
                'editUrl' => route('servis.edit', $servis),
            ],
        ];
    }

    /**
     * Format next Servis reminder event for FullCalendar.
     */
    private function formatNextServisEvent(Servis $servis): array
    {
        $kendaraan = $servis->kendaraan;
        $merkNama = $kendaraan->merk->nama ?? '';
        $platNomor = $kendaraan->plat_nomor ?? '';

        return [
            'id' => 'next_servis_' . $servis->id,
            'title' => 'Servis Berikutnya ' . $platNomor,
            'start' => $servis->servis_berikutnya->format('Y-m-d'),
            'color' => '#6366F1', // Indigo
            'textColor' => '#FFFFFF',
            'extendedProps' => [
                'type' => 'next_servis',
                'modelId' => $servis->id,
                'kendaraan' => $platNomor . ' - ' . $merkNama . ' ' . ($kendaraan->nama_model ?? ''),
                'kendaraan_id' => $servis->kendaraan_id,
                'jenis' => $servis->jenis,
                'jenisLabel' => Servis::JENIS_OPTIONS[$servis->jenis] ?? $servis->jenis,
                'daysUntilDue' => $servis->days_until_next_service,
                'viewUrl' => route('servis.show', $servis),
            ],
        ];
    }

    /**
     * Get mini calendar events for dashboard widget (grouped by date with colors only).
     */
    public function miniCalendarEvents(Request $request): JsonResponse
    {
        $year = (int) $request->input('year', now()->year);
        $month = (int) $request->input('month', now()->month);

        $start = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();

        $events = collect();

        // Pajak events
        $pajakEvents = Pajak::where('status', '!=', 'lunas')
            ->whereBetween('tanggal_jatuh_tempo', [$start, $end])
            ->get()
            ->map(function ($pajak) {
                $color = 'blue';
                if ($pajak->isOverdue()) {
                    $color = 'error';
                } elseif ($pajak->days_until_due <= 7) {
                    $color = 'warning';
                }
                return [
                    'date' => $pajak->tanggal_jatuh_tempo->format('Y-m-d'),
                    'color' => $color,
                ];
            });

        // Servis events
        $servisEvents = Servis::where('status', Servis::STATUS_DIJADWALKAN)
            ->whereBetween('tanggal_servis', [$start, $end])
            ->get()
            ->map(function ($servis) {
                return [
                    'date' => $servis->tanggal_servis->format('Y-m-d'),
                    'color' => 'purple',
                ];
            });

        // Next servis events
        $nextServisEvents = Servis::where('status', Servis::STATUS_SELESAI)
            ->whereNotNull('servis_berikutnya')
            ->whereBetween('servis_berikutnya', [$start, $end])
            ->get()
            ->map(function ($servis) {
                return [
                    'date' => $servis->servis_berikutnya->format('Y-m-d'),
                    'color' => 'indigo',
                ];
            });

        // Group by date
        $grouped = $pajakEvents->merge($servisEvents)->merge($nextServisEvents)
            ->groupBy('date')
            ->map(function ($events) {
                return $events->pluck('color')->unique()->values()->all();
            });

        return response()->json([
            'year' => $year,
            'month' => $month,
            'monthName' => $start->translatedFormat('F Y'),
            'daysInMonth' => $start->daysInMonth,
            'startDayOfWeek' => $start->dayOfWeek,
            'events' => $grouped,
        ]);
    }

    /**
     * Get color for Pajak event based on status.
     */
    private function getPajakColor(Pajak $pajak): string
    {
        if ($pajak->status === Pajak::STATUS_LUNAS) {
            return '#22C55E'; // Green
        }

        if ($pajak->isOverdue()) {
            return '#EF4444'; // Red
        }

        $daysUntilDue = $pajak->days_until_due;

        if ($daysUntilDue <= 7) {
            return '#F97316'; // Orange
        }

        if ($daysUntilDue <= 30) {
            return '#EAB308'; // Yellow
        }

        return '#22C55E'; // Green (normal)
    }

    /**
     * Get color for Servis event based on status.
     */
    private function getServisColor(Servis $servis): string
    {
        return match ($servis->status) {
            Servis::STATUS_DIJADWALKAN => '#3B82F6', // Blue
            Servis::STATUS_DALAM_PROSES => '#8B5CF6', // Purple
            Servis::STATUS_SELESAI => '#10B981', // Green
            Servis::STATUS_DIBATALKAN => '#6B7280', // Gray
            default => '#3B82F6', // Blue
        };
    }
}
