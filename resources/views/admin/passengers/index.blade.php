@extends('admin.layout')

@section('title', 'Penumpang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="admin-page-title mb-0">Penumpang</h2>
</div>

<form method="GET" action="{{ route('admin.passengers.index') }}" class="filter-toolbar mb-3">
    <div class="row g-2">
        <div class="col-md-5">
            <input type="text" name="q" class="form-control" placeholder="Cari nama atau nomor HP..." value="{{ $q }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-navy w-100">Cari</button>
        </div>
        @if ($q !== '')
            <div class="col-md-2">
                <a href="{{ route('admin.passengers.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        @endif
    </div>
</form>

<div class="admin-panel">
    <div class="table-responsive">
        <table class="table table-admin align-middle mb-0">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>No. HP</th>
                    <th>Total Booking</th>
                    <th>Total Kursi</th>
                    <th>Total Bayar (Lunas)</th>
                    <th>Terakhir Pesan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($passengers as $p)
                    <tr>
                        <td class="fw-semibold">{{ $p->nama_penumpang }}</td>
                        <td>{{ $p->hp }}</td>
                        <td>{{ $p->total_booking }}</td>
                        <td>{{ $p->total_kursi }}</td>
                        <td>Rp {{ number_format((int) $p->total_bayar, 0, ',', '.') }}</td>
                        <td>{{ \Illuminate\Support\Carbon::parse($p->terakhir)->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada penumpang.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($passengers->hasPages())
        <div class="p-3 border-top">{{ $passengers->links() }}</div>
    @endif
</div>
@endsection
