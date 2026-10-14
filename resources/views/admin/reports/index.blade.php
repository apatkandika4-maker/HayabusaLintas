@extends('admin.layout')

@section('title', 'Laporan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="admin-page-title mb-0">Laporan</h2>
</div>

<form method="GET" action="{{ route('admin.reports.index') }}" class="filter-toolbar mb-3">
    <div class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label small fw-semibold mb-1">Dari Tanggal</label>
            <input type="date" name="dari" class="form-control" value="{{ $dari }}">
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-semibold mb-1">Sampai Tanggal</label>
            <input type="date" name="sampai" class="form-control" value="{{ $sampai }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-navy w-100">Terapkan</button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
        </div>
    </div>
</form>

<div class="row g-3 mb-3">
    <div class="col-md-3 col-6">
        <div class="stat-tile accent-info">
            <div class="stat-cap">Total Booking</div>
            <div class="stat-value">{{ $ringkasan['total_booking'] }}</div>
            <div class="stat-sub">Selesai {{ $ringkasan['selesai'] }} &middot; Belum {{ $ringkasan['belum'] }}</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-tile accent-success">
            <div class="stat-cap">Pendapatan</div>
            <div class="stat-value is-money">Rp {{ number_format($ringkasan['pendapatan'], 0, ',', '.') }}</div>
            <div class="stat-sub">dari booking selesai</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-tile accent-orange">
            <div class="stat-cap">Kursi Terjual</div>
            <div class="stat-value">{{ $ringkasan['kursi'] }}</div>
            <div class="stat-sub">dari booking selesai</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-tile accent-warning">
            <div class="stat-cap">Belum Selesai</div>
            <div class="stat-value">{{ $ringkasan['belum'] }}</div>
            <div class="stat-sub">booking belum ditandai</div>
        </div>
    </div>
</div>

<div class="admin-panel">
    <div class="panel-head">Pendapatan Harian (Booking Selesai)</div>
    <div class="table-responsive">
        <table class="table table-admin align-middle mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jumlah Booking</th>
                    <th>Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($harian as $row)
                    <tr>
                        <td>{{ \Illuminate\Support\Carbon::parse($row->tgl)->format('d M Y') }}</td>
                        <td>{{ $row->jml }}</td>
                        <td class="fw-semibold">Rp {{ number_format((int) $row->total, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-muted py-4">Belum ada data pada rentang ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
