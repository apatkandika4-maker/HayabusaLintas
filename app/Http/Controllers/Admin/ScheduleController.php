<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route as TravelRoute;
use App\Models\Schedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->query('filter'); // 'hari_ini' | 'mendatang' | null
        $tanggal = $request->query('tanggal'); // tanggal spesifik (opsional)

        $schedules = Schedule::with(['route.origin', 'route.dest'])
            ->when($tanggal, fn ($q, $t) => $q->whereDate('tanggal', $t))
            ->when($filter === 'hari_ini', fn ($q) => $q->whereDate('tanggal', today()))
            ->when($filter === 'mendatang', fn ($q) => $q->whereDate('tanggal', '>=', today()))
            ->orderBy('tanggal')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.schedules.index', compact('schedules', 'filter', 'tanggal'));
    }

    public function create(): View
    {
        return $this->form(new Schedule([
            'tanggal' => today()->toDateString(),
            'sisa_kursi' => 7,
            'status' => 'aktif',
        ]));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['sisa_kursi'] ??= 7;

        Schedule::create($data);

        return redirect()->route('admin.schedules.index')->with('sukses', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(Schedule $schedule): View
    {
        return $this->form($schedule);
    }

    public function update(Request $request, Schedule $schedule): RedirectResponse
    {
        $schedule->update($this->validated($request));

        return redirect()->route('admin.schedules.index')->with('sukses', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule): RedirectResponse
    {
        $schedule->delete();

        return redirect()->route('admin.schedules.index')->with('sukses', 'Jadwal berhasil dihapus.');
    }

    /**
     * Hapus semua jadwal sekaligus. Jadwal yang sudah punya booking dilewati
     * agar data penumpang tidak ikut hilang.
     */
    public function destroyAll(): RedirectResponse
    {
        $punyaBooking = Schedule::has('bookings')->count();

        $terhapus = Schedule::doesntHave('bookings')->delete();

        if ($punyaBooking > 0) {
            return redirect()->route('admin.schedules.index')
                ->with('sukses', "{$terhapus} jadwal dihapus. {$punyaBooking} jadwal dilewati karena sudah ada booking.");
        }

        return redirect()->route('admin.schedules.index')
            ->with('sukses', "Semua jadwal dihapus ({$terhapus} jadwal).");
    }

    private function form(Schedule $schedule): View
    {
        $routes = TravelRoute::with(['origin', 'dest'])->orderBy('id')->get();

        return view('admin.schedules.form', compact('schedule', 'routes'));
    }

    /** @return array<string, mixed> */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'route_id' => 'required|exists:routes,id',
            'tanggal' => 'required|date',
            'harga' => 'required|integer|min:0',
            'sisa_kursi' => 'nullable|integer|min:0',
            'status' => 'required|in:aktif,penuh,batal,selesai',
        ]);

        // Jam tidak dipakai lagi (admin konfirmasi via WA). Isi default agar DB valid.
        $data['jam_berangkat'] = '00:00';

        return $data;
    }
}
