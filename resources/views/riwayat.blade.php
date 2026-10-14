@extends('layouts.app')

@section('title', 'Riwayat Booking')

@section('content')
<span class="section-eyebrow">Riwayat &amp; Cek Tiket</span>
<h2 class="section-title mb-3">Pemesanan Anda</h2>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ url('/riwayat') }}">
            <div class="row g-2">
                <div class="col-md-9">
                    <label for="kode" class="form-label fw-semibold">Kode Booking</label>
                    <input type="text" name="kode" id="kode" class="form-control form-control-lg" value="{{ request('kode') }}" placeholder="cth: KB-20260101-AB12" autofocus>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-navy btn-lg w-100">Cari Tiket</button>
                </div>
            </div>
        </form>
    </div>
</div>

@php
    $rows = isset($bookings) ? $bookings : (isset($riwayat) ? $riwayat : null);
@endphp
@if(!is_null($rows))
    @forelse($rows as $row)
        <div class="card mb-3">
            <div class="card-body d-flex flex-wrap align-items-center gap-3">
                <div class="me-auto">
                    <div class="font-monospace fw-bold fs-5">{{ $row->kode }}</div>
                    <div class="fw-bold">{{ $row->schedule->route->origin->nama ?? '-' }} &rarr; {{ $row->schedule->route->dest->nama ?? '-' }}</div>
                    <div class="small text-muted">{{ isset($row->schedule->tanggal) ? \Carbon\Carbon::parse($row->schedule->tanggal)->format('d M Y') : '-' }} &middot; Kursi {{ is_array($row->no_kursi) ? implode(', ', $row->no_kursi) : ($row->no_kursi ?? '-') }} &middot; Rp {{ number_format($row->total_harga ?? 0, 0, ',', '.') }}</div>
                </div>
                @include('admin.partials.status-badge', ['status' => $row->status ?? 'pending'])
                <a href="{{ url('/tiket/' . $row->kode) }}" class="btn btn-navy btn-sm">Buka Tiket</a>
            </div>
        </div>
    @empty
        <div class="card"><div class="card-body text-center py-5">
            <div class="step-num mb-3 mx-auto" style="width:56px;height:56px;font-size:1.5rem">?</div>
            <h5 class="fw-bold">Tidak ada booking dengan kode tersebut</h5>
            <p class="text-muted mb-0">Periksa kembali kode booking Anda.</p>
        </div></div>
    @endforelse
@else
    <div class="alert alert-info">Masukkan kode booking untuk melihat tiket pemesanan Anda. Pengguna yang login otomatis melihat daftar booking miliknya.</div>
@endif
@endsection
