@extends('layouts.app')

@section('title', 'Booking Berhasil')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card text-center">
            <div class="card-body p-5">
                <div class="step-num mb-3 mx-auto" style="width:64px;height:64px;font-size:1.8rem;background:#25d366;color:#fff">&#10003;</div>
                <span class="section-eyebrow">Langkah 2 dari 2 &middot; Bayar &amp; Konfirmasi</span>
                <h2 class="section-title mt-2">Kursi Berhasil Diamankan!</h2>
                <p class="text-muted">Tunjukkan kode booking ini saat pembayaran &amp; penjemputan:</p>
                <div class="alert alert-success">
                    <div class="small fw-bold">KODE BOOKING</div>
                    <div class="booking-kode fw-bold font-monospace">
                        @isset($booking)
                            {{ $booking->kode }}
                        @else
                            KB-XXXX-XXXX
                        @endisset
                    </div>
                </div>
                @isset($booking)
                    <div class="row text-start g-2 mt-3">
                        <div class="col-sm-6"><div class="border rounded p-3 h-100"><div class="small text-muted">Penumpang</div><div class="fw-bold">{{ $booking->nama_penumpang ?? '-' }}</div></div></div>
                        <div class="col-sm-6"><div class="border rounded p-3 h-100"><div class="small text-muted">Kursi</div><div class="fw-bold">{{ is_array($booking->no_kursi) ? implode(', ', $booking->no_kursi) : ($booking->no_kursi ?? '-') }} ({{ $booking->jumlah_kursi ?? '-' }} kursi)</div></div></div>
                        <div class="col-sm-6"><div class="border rounded p-3 h-100"><div class="small text-muted">Total Bayar</div><div class="fw-bold fs-5">Rp {{ number_format($booking->total_harga ?? 0, 0, ',', '.') }}</div></div></div>
                        <div class="col-sm-6"><div class="border rounded p-3 h-100"><div class="small text-muted">Status</div><div>@include('admin.partials.status-badge', ['status' => $booking->status ?? 'pending'])</div></div></div>
                    </div>
                    <a href="{{ $booking->waKonfirmasiLink() }}" target="_blank" rel="noopener" id="btnWaKonfirmasi" class="btn btn-wa btn-lg w-100 mt-4">Lanjut Pesan via WhatsApp</a>
                    <div class="form-text mt-1">Halaman WhatsApp akan terbuka otomatis. Jika tidak, ketuk tombol di atas &mdash; detail booking sudah terisi untuk admin.</div>
                @endisset
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header fw-bold bg-white border-0 pt-4 px-4">Cara menyelesaikan pembayaran</div>
            <div class="card-body px-4 pb-4">
                <ol class="mb-0">
                    <li class="mb-2">
                        <strong>QRIS:</strong> pindai kode QR yang dikirim admin via WA, bayar sesuai total, lalu kirim bukti ke WA <strong>{{ config('travel.wa_label') }}</strong>.
                    </li>
                    <li class="mb-2">
                        <strong>Transfer {{ config('travel.rekening') }}:</strong> transfer sebesar total tagihan, lalu kirim bukti ke WA <strong>{{ config('travel.wa_label') }}</strong> dengan mencantumkan kode booking.
                    </li>
                    <li><strong>Tunai (cash):</strong> bayar langsung ke sopir/kantor sebelum keberangkatan dengan menunjukkan kode booking.</li>
                </ol>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4 no-print">
            @isset($booking)
                <a href="{{ url('/tiket/' . $booking->kode) }}" class="btn btn-navy flex-fill">Lihat E-Tiket</a>
            @endisset
            <a href="{{ url('/riwayat') }}" class="btn btn-outline-secondary flex-fill">Cek Riwayat</a>
            <a href="{{ url('/') }}" class="btn btn-outline-secondary flex-fill">Beranda</a>
        </div>
    </div>
</div>
@isset($booking)
@push('scripts')
<script>
// Buka WhatsApp otomatis sekali setelah booking berhasil (tab baru).
(function () {
    var link = document.getElementById('btnWaKonfirmasi');
    if (!link) return;
    var kunci = 'wa_auto_{{ $booking->kode }}';
    if (sessionStorage.getItem(kunci)) return;
    sessionStorage.setItem(kunci, '1');
    setTimeout(function () {
        window.open(link.href, '_blank', 'noopener');
    }, 900);
})();
</script>
@endpush
@endisset
@endsection
