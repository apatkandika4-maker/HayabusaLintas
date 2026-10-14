<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Route as TravelRoute;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $hariIni = today()->toDateString();
        $kemarin = today()->subDay()->toDateString();

        // Booking hari ini + perbandingan dengan kemarin.
        $bookingHariIni = Booking::whereDate('created_at', $hariIni)->count();
        $bookingKemarin = Booking::whereDate('created_at', $kemarin)->count();
        $selisihBooking = $bookingHariIni - $bookingKemarin;

        // Total rute tersedia.
        $totalRute = TravelRoute::count();

        // Pendapatan dari booking yang ditandai SELESAI (status done) oleh admin.
        $pendapatanHariIni = (int) Booking::where('status', 'done')
            ->whereDate('updated_at', $hariIni)
            ->sum('total_harga');
        $pendapatanBulanIni = (int) Booking::where('status', 'done')
            ->whereMonth('updated_at', today()->month)
            ->whereYear('updated_at', today()->year)
            ->sum('total_harga');

        // Booking terbaru.
        $bookingTerbaru = Booking::with(['route.origin', 'route.dest', 'schedule.route.origin', 'schedule.route.dest'])
            ->latest()
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact(
            'bookingHariIni',
            'selisihBooking',
            'totalRute',
            'pendapatanHariIni',
            'pendapatanBulanIni',
            'bookingTerbaru'
        ));
    }
}
