<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        // Rentang tanggal default: 30 hari terakhir.
        $dari = $request->date('dari') ?? Carbon::today()->subDays(29);
        $sampai = $request->date('sampai') ?? Carbon::today();

        if ($dari->gt($sampai)) {
            [$dari, $sampai] = [$sampai, $dari];
        }

        $base = Booking::query()
            ->whereDate('created_at', '>=', $dari->toDateString())
            ->whereDate('created_at', '<=', $sampai->toDateString());

        // Booking yang dihitung sebagai pendapatan = yang ditandai SELESAI (done) oleh admin.
        $ringkasan = [
            'total_booking' => (clone $base)->count(),
            'selesai' => (clone $base)->where('status', 'done')->count(),
            'belum' => (clone $base)->where('status', '!=', 'done')->count(),
            'pendapatan' => (int) (clone $base)->where('status', 'done')->sum('total_harga'),
            'kursi' => (int) (clone $base)->where('status', 'done')->sum('jumlah_kursi'),
        ];

        // Pendapatan harian (hanya booking selesai) untuk tabel laporan.
        $harian = (clone $base)
            ->where('status', 'done')
            ->selectRaw('DATE(created_at) as tgl, COUNT(*) as jml, SUM(total_harga) as total')
            ->groupByRaw('DATE(created_at)')
            ->orderByDesc('tgl')
            ->get();

        return view('admin.reports.index', [
            'ringkasan' => $ringkasan,
            'harian' => $harian,
            'dari' => $dari->toDateString(),
            'sampai' => $sampai->toDateString(),
        ]);
    }
}
