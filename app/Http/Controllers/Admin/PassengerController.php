<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PassengerController extends Controller
{
    /**
     * Daftar penumpang (diringkas dari data booking, dikelompokkan per nomor HP).
     */
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q'));

        $query = Booking::query()
            ->selectRaw('hp,
                MAX(nama_penumpang) as nama_penumpang,
                COUNT(*) as total_booking,
                SUM(jumlah_kursi) as total_kursi,
                SUM(CASE WHEN status = "done" THEN total_harga ELSE 0 END) as total_bayar,
                MAX(created_at) as terakhir')
            ->when($q !== '', function ($w) use ($q) {
                $w->where(fn ($s) => $s->where('nama_penumpang', 'like', "%{$q}%")
                    ->orWhere('hp', 'like', "%{$q}%"));
            })
            ->groupBy('hp')
            ->orderByDesc('terakhir');

        $passengers = $query->paginate(20)->withQueryString();

        return view('admin.passengers.index', compact('passengers', 'q'));
    }
}
