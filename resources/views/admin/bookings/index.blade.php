@extends('admin.layout')

@section('title', 'Data Booking')

@php
    $sortLink = function (string $kolom) use ($sort, $arah) {
        $arahBaru = ($sort === $kolom && $arah === 'asc') ? 'desc' : 'asc';
        $params = array_merge(request()->query(), ['sort' => $kolom, 'arah' => $arahBaru]);
        return route('admin.bookings.index', $params);
    };
    $sortInd = fn (string $kolom) => $sort === $kolom ? ($arah === 'asc' ? '▲' : '▼') : '';
@endphp

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="admin-page-title mb-0">Data Booking</h2>
</div>

<form method="GET" action="{{ route('admin.bookings.index') }}" class="filter-toolbar mb-3">
    <div class="row g-2 align-items-end">
        <div class="col-md-8">
            <label class="form-label small fw-semibold mb-1">Cari</label>
            <input type="text" name="q" class="form-control" placeholder="Cari nama atau no HP..." value="{{ request('q') }}">
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button class="btn btn-navy flex-fill">Cari</button>
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </div>
</form>

<div class="admin-panel">
    <div class="table-responsive">
        <table class="table table-admin align-middle mb-0">
            <thead>
                <tr>
                    <th><a href="{{ $sortLink('nama') }}">Penumpang <span class="sort-ind">{{ $sortInd('nama') }}</span></a></th>
                    <th>Rute</th>
                    <th>Jumlah</th>
                    <th><a href="{{ $sortLink('total') }}">Total <span class="sort-ind">{{ $sortInd('total') }}</span></a></th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bookings as $booking)
                    @php $selesai = $booking->status === 'done'; @endphp
                    <tr>
                        <td>{{ $booking->nama_penumpang }}<br><small class="text-muted">{{ $booking->hp }}</small></td>
                        <td>{{ $booking->labelRute() }}</td>
                        <td>{{ $booking->jumlah_kursi }} orang</td>
                        <td class="fw-semibold">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                        <td>
                            @if ($selesai)
                                <span class="kb-badge kb-badge-success">Selesai</span>
                            @else
                                <span class="kb-badge kb-badge-warning">Belum</span>
                            @endif
                        </td>
                        <td class="text-nowrap">
                            <form action="{{ route('admin.bookings.selesai', $booking) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                @if ($selesai)
                                    <button class="btn btn-sm btn-outline-secondary">Batalkan</button>
                                @else
                                    <button class="btn btn-sm btn-wa">&#10003; Tandai Selesai</button>
                                @endif
                            </form>
                            <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus booking {{ $booking->nama_penumpang }}? Tindakan ini tidak bisa dibatalkan.')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada booking.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($bookings->hasPages())
        <div class="p-3 border-top">{{ $bookings->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
