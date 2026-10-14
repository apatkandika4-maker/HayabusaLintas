<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Schedule;

class ScheduleController extends Controller
{
    public function show(Schedule $schedule)
    {
        $schedule->load(['route.origin', 'route.dest']);

        $kursiBooking = Booking::where('schedule_id', $schedule->id)
            ->whereNotIn('status', ['cancelled'])
            ->pluck('no_kursi')
            ->flatten()
            ->map(fn ($n) => (int) $n)
            ->all();

        // Gabungkan kursi dari booking web + kursi yang ditandai admin (walk-in / WA).
        $kursiDiblokir = array_map('intval', (array) ($schedule->kursi_diblokir ?? []));
        $occupiedSeats = collect(array_merge($kursiBooking, $kursiDiblokir))
            ->unique()
            ->sort()
            ->values()
            ->all();

        // Jumlah kursi jadwal (armada tidak dipakai lagi).
        $capacity = (int) ($schedule->sisa_kursi ?? 0) + count($occupiedSeats);

        $rute = ($schedule->route->origin->nama ?? '-').' → '.($schedule->route->dest->nama ?? '-');
        $pesan = "Halo Hayabusa Lintas! Mau pesan kursi:\nRute: {$rute}\nTanggal: {$schedule->tanggal}\nMohon info jam keberangkatan & kursi yang tersedia.";

        return view('schedule-detail', [
            'schedule' => $schedule,
            'occupiedSeats' => $occupiedSeats,
            'capacity' => $capacity,
            'waPesanCepat' => 'https://wa.me/'.config('travel.wa').'?text='.urlencode($pesan),
        ]);
    }
}
