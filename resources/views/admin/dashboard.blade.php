@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="admin-page-title mb-0">Dashboard</h2>
    <span class="text-muted small">{{ now()->translatedFormat('l, d F Y') }}</span>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="stat-tile accent-info">
            <span class="stat-ico">&#128221;</span>
            <div class="stat-cap">Booking Hari Ini</div>
            <div class="stat-value">{{ $bookingHariIni }}</div>
            <div class="stat-sub">
                @if ($selisihBooking > 0)
                    <span style="color:var(--kb-success)">&#9650; +{{ $selisihBooking }}</span> dari kemarin
                @elseif ($selisihBooking < 0)
                    <span style="color:var(--kb-danger)">&#9660; {{ $selisihBooking }}</span> dari kemarin
                @else
                    sama seperti kemarin
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-tile accent-success">
            <span class="stat-ico">&#128181;</span>
            <div class="stat-cap">Pendapatan Hari Ini</div>
            <div class="stat-value is-money">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</div>
            <div class="stat-sub">dari booking selesai</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-tile accent-warning">
            <span class="stat-ico">&#128197;</span>
            <div class="stat-cap">Pendapatan Bulan Ini</div>
            <div class="stat-value is-money">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</div>
            <div class="stat-sub">dari booking selesai</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-tile accent-orange">
            <span class="stat-ico">&#128506;</span>
            <div class="stat-cap">Total Rute</div>
            <div class="stat-value">{{ $totalRute }}</div>
            <div class="stat-sub">rute tersedia</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-12">
        <div class="admin-panel h-100">
            <div class="panel-head">
                Booking Terbaru
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-navy btn-sm">Semua Booking</a>
            </div>
            <div class="table-responsive">
                <table class="table table-admin align-middle mb-0">
                    <thead>
                        <tr><th>Penumpang</th><th>No. HP</th><th>Rute</th></tr>
                    </thead>
                    <tbody>
                        @forelse ($bookingTerbaru as $booking)
                            <tr>
                                <td class="fw-semibold">{{ $booking->nama_penumpang }}</td>
                                <td>{{ $booking->hp }}</td>
                                <td>{{ $booking->labelRute() }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">Belum ada booking.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
