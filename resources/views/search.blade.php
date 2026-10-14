@extends('layouts.app')

@section('title', 'Hasil Pencarian')

@section('content')
<span class="section-eyebrow">Hasil Pencarian</span>
<h2 class="section-title mb-3">
    @if(isset($schedules) && $schedules->isNotEmpty())
        {{ $schedules->first()->route->origin->nama ?? '' }} &rarr; {{ $schedules->first()->route->dest->nama ?? '' }}
        <small class="fs-6 fw-normal text-muted">&middot; {{ isset($tanggal) ? \Carbon\Carbon::parse($tanggal)->format('d M Y') : '' }}</small>
    @else
        Jadwal Perjalanan
    @endif
</h2>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ url('/cari') }}" class="row g-2">
            <div class="col-md-4">
                <label for="origin" class="form-label fw-semibold">Kota Asal</label>
                <select name="origin" id="origin" class="form-select" required>
                    <option value="">-- Pilih asal --</option>
                    @foreach(($cities ?? []) as $city)
                        <option value="{{ $city->id }}" {{ (string) ($originId ?? request('origin')) === (string) $city->id ? 'selected' : '' }}>{{ $city->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="dest" class="form-label fw-semibold">Kota Tujuan</label>
                <select name="dest" id="dest" class="form-select" required>
                    <option value="">-- Pilih tujuan --</option>
                    @foreach(($cities ?? []) as $city)
                        <option value="{{ $city->id }}" {{ (string) ($destId ?? request('dest')) === (string) $city->id ? 'selected' : '' }}>{{ $city->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="tanggal" class="form-label fw-semibold">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ $tanggal ?? request('tanggal', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-navy w-100">Ubah</button>
            </div>
        </form>
    </div>
</div>

@isset($schedules)
    @forelse($schedules as $s)
        @php
            $kap = $s->fleet->kapasitas ?? max(1, (int) $s->sisa_kursi);
            $terisi = max(0, $kap - (int) $s->sisa_kursi);
            $persen = $kap > 0 ? round($terisi / $kap * 100) : 0;
        @endphp
        <div class="card jadwal-card mb-3">
            <div class="card-body">
                <div class="row align-items-center g-3">
                    <div class="col-md-2 text-center text-md-start">
                        <div class="fw-bold">{{ \Carbon\Carbon::parse($s->tanggal)->format('d M Y') }}</div>
                        <div class="small text-muted fw-semibold">Jam via WA</div>
                    </div>
                    <div class="col-md-5">
                        <div class="fw-bold"><span class="timeline-dot me-1"></span>{{ $s->route->origin->nama ?? '-' }}</div>
                        <div class="small text-muted ms-3 mb-1">{{ $s->fleet->nama ?? '-' }} &middot; {{ strtoupper($s->fleet->tipe ?? '-') }}</div>
                        <div class="fw-bold"><span class="timeline-dot me-1"></span>{{ $s->route->dest->nama ?? '-' }}</div>
                    </div>
                    <div class="col-md-2">
                        <div class="small fw-semibold mb-1">Sisa <strong>{{ $s->sisa_kursi }}</strong> dari {{ $kap }} kursi</div>
                        <div class="seat-left-bar"><span style="width:{{ $persen }}%"></span></div>
                    </div>
                    <div class="col-md-3 text-md-end">
                        <div class="rute-price">Rp {{ number_format($s->harga ?? 0, 0, ',', '.') }}</div>
                        <div class="rute-meta mb-2">per kursi</div>
                        @php
                            $asalS = $s->route->origin->nama ?? '-';
                            $tujuanS = $s->route->dest->nama ?? '-';
                            $tglS = isset($tanggal) ? \Carbon\Carbon::parse($tanggal)->format('d M Y') : '';
                            $pesanS = "Halo Travel KalBar, saya mau pesan tiket:\nRute: {$asalS} → {$tujuanS}\nTanggal: {$tglS}\nHarga: Rp " . number_format($s->harga ?? 0, 0, ',', '.') . "/kursi\nMohon info jam keberangkatan & ketersediaan kursinya. Terima kasih.";
                            $waS = 'https://wa.me/' . config('travel.wa') . '?text=' . urlencode($pesanS);
                        @endphp
                        <a href="{{ $waS }}" target="_blank" rel="noopener" class="btn btn-wa w-100">Pesan via WA</a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="card"><div class="card-body text-center py-5">
            <div class="step-num mb-3 mx-auto" style="width:56px;height:56px;font-size:1.5rem">?</div>
            <h5 class="fw-bold">Belum ada jadwal untuk rute &amp; tanggal ini</h5>
            <p class="text-muted">Coba tanggal lain, rute sebaliknya, atau tanya ketersediaan via WhatsApp.</p>
            @isset($waPesanCepat)
                <a href="{{ $waPesanCepat }}" target="_blank" rel="noopener" class="btn btn-wa">Tanya via WhatsApp</a>
            @else
                <a href="https://wa.me/{{ config('travel.wa') }}" target="_blank" rel="noopener" class="btn btn-wa">Tanya via WhatsApp</a>
            @endisset
        </div></div>
    @endforelse
@endisset
@endsection
