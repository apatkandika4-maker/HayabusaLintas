@extends('layouts.app')

@section('title', 'Pilih Kursi')

@section('content')
@isset($schedule)
<span class="section-eyebrow">Langkah 1 dari 2 &middot; Pilih Kursi</span>
<h2 class="section-title mb-1">{{ $schedule->route->origin->nama ?? '-' }} &rarr; {{ $schedule->route->dest->nama ?? '-' }}</h2>
<p class="text-muted mb-4">{{ isset($schedule->tanggal) ? \Carbon\Carbon::parse($schedule->tanggal)->format('l, d M Y') : '-' }} &middot; <strong>Jam dikonfirmasi via WA</strong> &middot; <strong class="text-success">Rp {{ number_format($schedule->harga ?? 0, 0, ',', '.') }}/kursi</strong></p>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="bus-shell shadow-sm">
            <div class="d-flex align-items-center mb-3">
                <span class="badge bg-secondary">DEPAN</span>
                <div class="bus-wheel"></div>
            </div>
            <div class="seat-grid" id="seatGrid">
                @for ($i = 1; $i <= ($capacity ?? 0); $i++)
                    @php $taken = in_array($i, $occupiedSeats ?? []); @endphp
                    <button type="button" class="btn {{ $taken ? 'btn-secondary' : 'btn-outline-success' }} seat-btn"
                        data-seat="{{ $i }}" {{ $taken ? 'disabled' : '' }}>{{ $i }}</button>
                @endfor
            </div>
            <div class="d-flex gap-3 mt-3 small fw-semibold">
                <span><span class="badge btn-outline-success border">&nbsp;&nbsp;&nbsp;</span> Kosong</span>
                <span><span class="badge bg-success">&nbsp;&nbsp;&nbsp;</span> Pilihanmu</span>
                <span><span class="badge bg-secondary">&nbsp;&nbsp;&nbsp;</span> Terisi</span>
            </div>
            @php
                $kapasitasInfo = (int) ($capacity ?? 0);
                $sisaDariDenah = max($kapasitasInfo - count($occupiedSeats ?? []), 0);
                $sisaInfo = min((int) ($schedule->sisa_kursi ?? $sisaDariDenah), $sisaDariDenah);
            @endphp
            <p class="mt-3 mb-1 small text-muted">Sisa kursi tersedia: <strong>{{ $sisaInfo }}</strong> dari {{ $kapasitasInfo }}</p>
            <p class="mb-0 fw-bold">Dipilih: <span id="seatCount">0</span> kursi &middot; Total: <span id="seatTotal" class="text-success">Rp 0</span></p>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Data Penumpang</h5>
                <form method="POST" action="{{ url('/booking') }}">
                    @csrf
                    <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                    <input type="hidden" name="jumlah_kursi" id="jumlahKursi" value="0">
                    <div id="seatInputs"></div>
                    <div class="mb-3">
                        <label for="nama" class="form-label fw-semibold">Nama Penumpang</label>
                        <input type="text" name="nama_penumpang" id="nama" class="form-control form-control-lg" value="{{ old('nama_penumpang') }}" required maxlength="100" placeholder="Nama sesuai KTP">
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6 mb-3">
                            <label for="hp" class="form-label fw-semibold">No. HP / WA</label>
                            <input type="text" name="hp" id="hp" class="form-control form-control-lg" value="{{ old('hp') }}" required minlength="9" maxlength="20" placeholder="cth: 081234567890">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Metode Bayar</label>
                            <input type="hidden" name="metode" value="cash">
                            <div class="form-control form-control-lg bg-light d-flex align-items-center" style="cursor:default">
                                Bayar di Tempat (Cash)
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label fw-semibold">Alamat Jemput <span class="text-muted fw-normal">(opsional)</span></label>
                        <input type="text" name="alamat_jemput" id="alamat" class="form-control" value="{{ old('alamat_jemput') }}" maxlength="255" placeholder="cth: Jl. Merdeka No. 10, Pontianak">
                    </div>
                    <div class="mb-4">
                        <label for="shareloc" class="form-label fw-semibold">Serlok Titik Jemput <span class="text-muted fw-normal">(opsional)</span></label>
                        <input type="text" name="shareloc" id="shareloc" class="form-control" value="{{ old('shareloc') }}" maxlength="500" placeholder="Tempel link Google Maps">
                        <div class="form-text">Google Maps &rarr; tahan titik jemput &rarr; Bagikan &rarr; salin link. Sopir langsung tahu titik Anda.</div>
                    </div>
                    <button type="submit" class="btn btn-gold btn-lg w-100" id="btnBooking" disabled>Pilih kursi dulu</button>
                </form>
                <div class="text-center text-muted small my-3">&mdash; atau &mdash;</div>
                @isset($waPesanCepat)
                    <a href="{{ $waPesanCepat }}" target="_blank" rel="noopener" class="btn btn-wa w-100">Pesan Langsung via WhatsApp</a>
                    <div class="form-text text-center mt-1">Tanpa isi form &mdash; satu ketuk, detail rute &amp; jadwal langsung terkirim ke admin.</div>
                @endisset
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
const grid = document.getElementById('seatGrid');
const chosen = new Set();
const countEl = document.getElementById('seatCount');
const totalEl = document.getElementById('seatTotal');
const jumlahEl = document.getElementById('jumlahKursi');
const inputsEl = document.getElementById('seatInputs');
const btn = document.getElementById('btnBooking');
const harga = {{ (int) ($schedule->harga ?? 0) }};
@php
    $kapasitasView = (int) ($capacity ?? 0);
    $sisaDariKursi = max($kapasitasView - count($occupiedSeats ?? []), 0);
    // Batas pilih = nilai terkecil antara sisa_kursi di DB dan sisa dari denah kursi.
    $sisaKursiView = min((int) ($schedule->sisa_kursi ?? $sisaDariKursi), $sisaDariKursi);
@endphp
const sisaKursi = {{ (int) $sisaKursiView }};
const rupiah = n => 'Rp ' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
grid.addEventListener('click', e => {
    const b = e.target.closest('.seat-btn');
    if (!b || b.disabled) return;
    const s = b.dataset.seat;
    if (chosen.has(s)) { chosen.delete(s); b.classList.replace('btn-success', 'btn-outline-success'); }
    else {
        if (chosen.size >= sisaKursi) {
            alert('Sisa kursi tinggal ' + sisaKursi + '. Tidak bisa memilih lebih banyak.');
            return;
        }
        chosen.add(s); b.classList.replace('btn-outline-success', 'btn-success');
    }
    countEl.textContent = chosen.size;
    totalEl.textContent = rupiah(chosen.size * harga);
    jumlahEl.value = chosen.size;
    inputsEl.innerHTML = [...chosen].map(s => `<input type="hidden" name="no_kursi[]" value="${s}">`).join('');
    btn.disabled = chosen.size === 0;
    btn.textContent = chosen.size === 0 ? 'Pilih kursi dulu' : `Pesan ${chosen.size} kursi — ${rupiah(chosen.size * harga)}`;
});
</script>
@endpush
@else
<div class="alert alert-warning">Jadwal tidak ditemukan. <a href="{{ url('/') }}">Kembali ke beranda</a>.</div>
@endisset
@endsection
