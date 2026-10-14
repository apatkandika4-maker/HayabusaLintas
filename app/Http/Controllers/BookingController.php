<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    /**
     * Pemesanan berbasis rute (alur WhatsApp):
     * simpan data penumpang ke DB (masuk admin + laporan), lalu arahkan ke WA admin.
     */
    public function pesanRute(Request $request)
    {
        $data = $request->validate([
            'route_id' => ['required', 'exists:routes,id'],
            'nama_penumpang' => ['required', 'string', 'max:100'],
            'hp' => ['required', 'string', 'min:9', 'max:20'],
            'jumlah_kursi' => ['required', 'integer', 'min:1', 'max:20'],
            'tanggal' => ['required', 'date'],
        ], [
            'nama_penumpang.required' => 'Nama penumpang wajib diisi.',
            'hp.required' => 'Nomor HP/WA wajib diisi.',
            'jumlah_kursi.required' => 'Jumlah penumpang wajib diisi.',
            'tanggal.required' => 'Tanggal berangkat wajib diisi.',
        ]);

        $route = \App\Models\Route::with(['origin', 'dest'])->findOrFail($data['route_id']);

        $booking = Booking::create([
            'kode' => Booking::generateKode(),
            'nama_penumpang' => $data['nama_penumpang'],
            'hp' => $data['hp'],
            'route_id' => $route->id,
            'tanggal' => $data['tanggal'],
            'jumlah_kursi' => $data['jumlah_kursi'],
            'total_harga' => (int) $route->harga_dasar * (int) $data['jumlah_kursi'],
            'status' => 'pending',
            'metode' => 'cash',
            'user_id' => auth()->id(),
        ]);

        // Arahkan ke WhatsApp admin dengan detail terisi.
        return redirect()->away($booking->waKonfirmasiLink());
    }

    public function store(StoreBookingRequest $request)
    {
        $data = $request->validated();

        try {
            $booking = DB::transaction(function () use ($data) {
                $schedule = Schedule::whereKey($data['schedule_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($schedule->sisa_kursi < $data['jumlah_kursi']) {
                    throw ValidationException::withMessages([
                        'jumlah_kursi' => 'Sisa kursi tidak mencukupi. Tersisa ' . $schedule->sisa_kursi . ' kursi.',
                    ]);
                }

                $occupied = Booking::where('schedule_id', $schedule->id)
                    ->whereNotIn('status', ['cancelled'])
                    ->lockForUpdate()
                    ->pluck('no_kursi')
                    ->flatten()
                    ->map(fn ($n) => (int) $n)
                    ->all();

                $noKursi = $this->pickSeats($occupied, $data, $schedule);

                $schedule->decrement('sisa_kursi', $data['jumlah_kursi']);

                return Booking::create([
                    'kode' => Booking::generateKode(),
                    'nama_penumpang' => $data['nama_penumpang'],
                    'hp' => $data['hp'],
                    'alamat_jemput' => $data['alamat_jemput'] ?? null,
                    'shareloc' => $data['shareloc'] ?? null,
                    'schedule_id' => $schedule->id,
                    'jumlah_kursi' => $data['jumlah_kursi'],
                    'no_kursi' => $noKursi,
                    'total_harga' => $schedule->harga * $data['jumlah_kursi'],
                    'status' => 'pending',
                    'metode' => $data['metode'],
                    'user_id' => auth()->id(),
                ]);
            });
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()
            ->route('booking.sukses', $booking->kode)
            ->with('sukses', 'Booking berhasil! Kode tiket Anda: ' . $booking->kode);
    }

    /**
     * @param  int[]  $occupied
     */
    protected function pickSeats(array $occupied, array $data, Schedule $schedule): array
    {
        $jumlah = (int) $data['jumlah_kursi'];

        if (! empty($data['no_kursi'])) {
            $requested = array_map('intval', (array) $data['no_kursi']);
            $requested = array_values(array_unique($requested));

            if (count($requested) !== $jumlah) {
                throw ValidationException::withMessages([
                    'no_kursi' => 'Jumlah nomor kursi yang dipilih harus sama dengan jumlah kursi dipesan.',
                ]);
            }

            $bentrok = array_intersect($requested, $occupied);
            if (! empty($bentrok)) {
                throw ValidationException::withMessages([
                    'no_kursi' => 'Kursi nomor ' . implode(', ', $bentrok) . ' sudah terisi. Silakan pilih kursi lain.',
                ]);
            }

            sort($requested);

            return $requested;
        }

        $capacity = $schedule->fleet?->kapasitas;

        if ($capacity) {
            $bebas = array_values(array_diff(range(1, (int) $capacity), $occupied));
            if (count($bebas) < $jumlah) {
                throw ValidationException::withMessages([
                    'jumlah_kursi' => 'Sisa kursi tidak mencukupi untuk jumlah yang diminta.',
                ]);
            }

            return array_slice($bebas, 0, $jumlah);
        }

        $maks = empty($occupied) ? 0 : max($occupied);

        return range($maks + 1, $maks + $jumlah);
    }

    public function sukses(string $kode)
    {
        $booking = Booking::with(['schedule.route.origin', 'schedule.route.dest', 'schedule.fleet'])
            ->where('kode', $kode)
            ->first();

        if (! $booking) {
            return redirect()->route('riwayat')->with('gagal', 'Kode booking tidak ditemukan.');
        }

        return view('booking-success', compact('booking'));
    }

    public function tiket(string $kode)
    {
        $booking = Booking::with(['schedule.route.origin', 'schedule.route.dest', 'schedule.fleet'])
            ->where('kode', $kode)
            ->first();

        if (! $booking) {
            return redirect()->route('riwayat')->with('gagal', 'Tiket dengan kode tersebut tidak ditemukan.');
        }

        return view('tiket', compact('booking'));
    }

    public function riwayat(Request $request)
    {
        $bookings = collect();

        if ($request->filled('kode')) {
            $request->validate([
                'kode' => ['required', 'string'],
            ], [
                'kode.required' => 'Kode booking wajib diisi.',
            ]);

            $bookings = Booking::with(['schedule.route.origin', 'schedule.route.dest'])
                ->where('kode', $request->kode)
                ->latest()
                ->get();
        } elseif (auth()->check()) {
            $bookings = Booking::with(['schedule.route.origin', 'schedule.route.dest'])
                ->where('user_id', auth()->id())
                ->latest()
                ->get();
        }

        return view('riwayat', compact('bookings'));
    }
}
