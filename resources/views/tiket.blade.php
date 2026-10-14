@extends('layouts.app')

@section('title', 'Tiket Perjalanan')

@section('content')
@isset($booking)
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="tiket shadow" id="tiketArea">
                <div class="tiket-header p-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h4 class="fw-bold mb-0"><span class="brand-mark brand-mark-sm">KB</span> Travel KalBar</h4>
                            <small style="color:rgba(255,255,255,.75)">E-tiket perjalanan antar-kota Kalimantan Barat</small>
                        </div>
                        <div class="text-end">
                            <div class="small" style="color:#ffd9ad">KODE BOOKING</div>
                            <div class="fs-4 fw-bold font-monospace">{{ $booking->kode }}</div>
                        </div>
                    </div>
                </div>
                <div class="p-4 bg-white">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div>
                            <div class="small text-muted fw-semibold">{{ isset($booking->schedule->tanggal) ? \Carbon\Carbon::parse($booking->schedule->tanggal)->format('d M Y') : '-' }} &middot; {{ isset($booking->schedule->jam_berangkat) ? \Carbon\Carbon::parse($booking->schedule->jam_berangkat)->format('H:i') . ' WIB' : '-' }}</div>
                            <h4 class="fw-bold mb-0">{{ $booking->schedule->route->origin->nama ?? '-' }} &rarr; {{ $booking->schedule->route->dest->nama ?? '-' }}</h4>
                        </div>
                        <span class="badge bg-info text-dark ms-auto">{{ strtoupper($booking->status ?? '-') }}</span>
                    </div>
                    <div class="row g-2">
                        <div class="col-sm-6"><div class="border rounded p-3 h-100"><div class="small text-muted">Penumpang</div><div class="fw-bold">{{ $booking->nama_penumpang ?? '-' }}</div><div class="small text-muted">{{ $booking->hp ?? '-' }}</div></div></div>
                        <div class="col-sm-6"><div class="border rounded p-3 h-100"><div class="small text-muted">Kursi</div><div class="fw-bold fs-5">{{ is_array($booking->no_kursi) ? implode(', ', $booking->no_kursi) : ($booking->no_kursi ?? '-') }}</div><div class="small text-muted">{{ $booking->schedule->fleet->nama ?? '-' }} ({{ strtoupper($booking->schedule->fleet->tipe ?? '-') }})</div></div></div>
                        @isset($booking->alamat_jemput)
                        <div class="col-sm-6"><div class="border rounded p-3 h-100"><div class="small text-muted">Alamat Jemput</div><div class="fw-bold">{{ $booking->alamat_jemput }}</div></div></div>
                        @endisset
                        @if(!empty($booking->shareloc))
                        <div class="col-sm-6"><div class="border rounded p-3 h-100"><div class="small text-muted">Titik Jemput (Serlok)</div><a href="{{ $booking->shareloc }}" target="_blank" rel="noopener" class="btn btn-sm btn-navy mt-1">Buka Maps</a></div></div>
                        @endif
                        <div class="col-sm-6"><div class="border rounded p-3 h-100"><div class="small text-muted">Total Bayar</div><div class="fw-bold text-success fs-5">Rp {{ number_format($booking->total_harga ?? 0, 0, ',', '.') }}</div></div></div>
                    </div>
                    <div class="tiket-perforasi mt-4 pt-3">
                        <p class="small text-muted mb-0">Tunjukkan kode booking kepada sopir/petugas saat penjemputan. Harap datang <strong>30 menit</strong> sebelum jam berangkat.</p>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2 mt-3 no-print">
                <a href="{{ $booking->waKonfirmasiLink() }}" target="_blank" rel="noopener" class="btn btn-wa flex-fill">Konfirmasi via WhatsApp</a>
                <button class="btn btn-navy flex-fill" onclick="window.print()">Cetak Tiket</button>
                <a href="{{ url('/riwayat') }}" class="btn btn-outline-secondary flex-fill">Riwayat</a>
            </div>

            @if(!in_array($booking->status, ['verified', 'done', 'cancelled'], true))
            <div class="card mt-4 no-print">
                <div class="card-body">
                    <h6 class="fw-bold">Upload Bukti Pembayaran</h6>
                    <form method="POST" action="{{ url('/tiket/' . $booking->kode . '/bayar') }}" enctype="multipart/form-data" class="row g-2">
                        @csrf
                        <div class="col-md-4">
                            <select name="metode" class="form-select" required onchange="document.getElementById('buktiFile').required = this.value !== 'cash'">
                                <option value="transfer" {{ ($booking->metode ?? '') === 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                <option value="qris" {{ ($booking->metode ?? '') === 'qris' ? 'selected' : '' }}>QRIS</option>
                                <option value="cash" {{ ($booking->metode ?? '') === 'cash' ? 'selected' : '' }}>Cash</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <input type="file" id="buktiFile" name="bukti_bayar" class="form-control" accept="image/*" {{ ($booking->metode ?? '') === 'cash' ? '' : 'required' }}>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-navy w-100">Kirim Bukti</button>
                        </div>
                    </form>
                    <div class="form-text">Foto/scan bukti transfer (maks 2 MB). Status berubah otomatis menjadi menunggu verifikasi.</div>
                </div>
            </div>
            @endif
        </div>
    </div>
@else
    <div class="alert alert-warning">Tiket tidak ditemukan. Periksa kembali kode booking Anda. <a href="{{ url('/riwayat') }}">Cari di riwayat</a>.</div>
@endisset
@endsection
