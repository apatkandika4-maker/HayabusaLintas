<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingAdminController extends Controller
{
    public function index(Request $request): View
    {
        // Kolom yang boleh dipakai untuk sorting (whitelist biar aman).
        $kolomSort = [
            'kode' => 'kode',
            'nama' => 'nama_penumpang',
            'total' => 'total_harga',
            'tanggal' => 'created_at',
        ];
        $sort = $request->query('sort', 'tanggal');
        $sort = array_key_exists($sort, $kolomSort) ? $sort : 'tanggal';
        $arah = $request->query('arah') === 'asc' ? 'asc' : 'desc';

        $bookings = Booking::with(['route.origin', 'route.dest', 'schedule.route.origin', 'schedule.route.dest'])
            ->when($request->query('q'), function ($q, $kata) {
                $q->where(fn ($w) => $w->where('kode', 'like', "%{$kata}%")
                    ->orWhere('nama_penumpang', 'like', "%{$kata}%")
                    ->orWhere('hp', 'like', "%{$kata}%"));
            })
            ->orderBy($kolomSort[$sort], $arah)
            ->paginate(15)
            ->withQueryString();

        return view('admin.bookings.index', compact('bookings', 'sort', 'arah'));
    }

    public function show(Booking $booking): View
    {
        $booking->load(['route.origin', 'route.dest', 'schedule.route.origin', 'schedule.route.dest']);

        return view('admin.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking): View
    {
        $booking->load(['route.origin', 'route.dest']);

        return view('admin.bookings.edit', compact('booking'));
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $data = $request->validate([
            'nama_penumpang' => 'required|string|max:100',
            'hp' => 'required|string|max:20',
            'alamat_jemput' => 'nullable|string|max:255',
            'status' => 'required|in:pending,done',
        ]);

        $booking->update($data);

        return redirect()
            ->route('admin.bookings.show', $booking)
            ->with('sukses', 'Booking berhasil diperbarui.');
    }

    /**
     * Tandai booking selesai (penumpang berangkat & sudah bayar) atau batalkan tandanya.
     * Pendapatan dihitung dari booking berstatus "done".
     */
    public function toggleSelesai(Booking $booking): RedirectResponse
    {
        if ($booking->status === 'done') {
            $booking->update(['status' => 'pending']);

            return back()->with('sukses', "Tanda selesai untuk {$booking->nama_penumpang} dibatalkan.");
        }

        $booking->update(['status' => 'done']);

        return back()->with('sukses', "Booking {$booking->nama_penumpang} ditandai SELESAI & masuk pendapatan.");
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        $kode = $booking->kode;

        // Kembalikan sisa kursi kalau booking masih aktif (belum dibatalkan/selesai).
        if ($booking->schedule && ! in_array($booking->status, ['cancelled', 'done'], true)) {
            $booking->schedule->increment('sisa_kursi', (int) $booking->jumlah_kursi);
        }

        $booking->delete();

        return redirect()
            ->route('admin.bookings.index')
            ->with('sukses', "Booking {$kode} dihapus permanen.");
    }
}
