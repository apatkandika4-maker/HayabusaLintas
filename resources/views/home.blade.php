@extends('layouts.app')

@section('title', 'Beranda')

@section('hero')
<div class="hero-kalbar hero-bg" style="background-image:url('{{ asset('img/hero-mobil.jpg') }}')">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8">
                <span class="section-eyebrow" style="background:rgba(255,255,255,.2);color:#fff">Hayabusa Lintas &middot; Travel &amp; Cargo Kalimantan Barat</span>
                <h1 class="display-5 fw-bold mt-2">Dari Pontianak Untuk Nusantara, Travel Setiap Hari.</h1>
                <p class="lead mb-0">Travel antar-kota se-Kalimantan Barat. Pilih rute tujuan di bawah, lalu pesan langsung via WhatsApp.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<span class="section-eyebrow" id="rute">Rute Pelayanan</span>
<h2 class="section-title mb-1">Mau ke mana hari ini?</h2>
<p class="text-muted mb-4">Melayani rute Pantura, Hulu, hingga antar provinsi. Harga pasti di muka.</p>

@php
    $urutanKategori = ['pantura' => 'Rute Pantura', 'hulu' => 'Rute Hulu', 'provinsi' => 'Rute Provinsi'];
@endphp

@isset($ruteByKategori)
    @forelse($urutanKategori as $key => $judul)
        @php $daftar = $ruteByKategori[$key] ?? collect(); @endphp
        @if($daftar->count())
            <h5 class="fw-bold mt-4 mb-3"><span class="kat-dot"></span>{{ $judul }}</h5>
            <div class="row g-3 mb-4">
                @foreach($daftar as $route)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card card-rute h-100"><div class="card-body d-flex flex-column p-3">
                            <h6 class="fw-bold mb-1">{{ $route->origin->nama ?? '-' }} &rarr; {{ $route->dest->nama ?? '-' }}</h6>
                            <p class="rute-price mb-3" style="font-size:1.1rem">Rp {{ number_format($route->harga_dasar ?? 0, 0, ',', '.') }} <small class="fs-6 fw-normal text-muted">/kursi</small></p>
                            <button type="button" class="btn btn-wa btn-sm mt-auto align-self-start btn-pesan"
                                data-route="{{ $route->id }}"
                                data-nama="{{ $route->origin->nama ?? '-' }} → {{ $route->dest->nama ?? '-' }}"
                                data-harga="{{ (int) $route->harga_dasar }}">Pesan via WA</button>
                        </div></div>
                    </div>
                @endforeach
            </div>
        @endif
    @empty
    @endforelse
@endisset

@php $adaRute = isset($ruteByKategori) && $ruteByKategori->flatten()->count() > 0; @endphp
@unless($adaRute)
    <div class="alert alert-info mb-5">Belum ada rute. Gunakan form pencarian di atas.</div>
@endunless

<div class="mb-5"></div>

<span class="section-eyebrow">Layanan Kami</span>
<h2 class="section-title mb-4">Bukan cuma antar penumpang</h2>
<div class="row g-3 mb-5">
    <div class="col-md-4">
        <div class="card layanan-item h-100"><div class="card-body d-flex align-items-start gap-3">
            <div class="feature-icon flex-shrink-0">&#128666;</div>
            <div>
                <h6 class="fw-bold mb-1">Reguler</h6>
                <p class="text-muted small mb-2">Perjalanan harian antar-kota, jadwal tetap.</p>
                <a href="#cari" class="btn btn-outline-navy btn-sm">Cari Jadwal</a>
            </div>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card layanan-item h-100"><div class="card-body d-flex align-items-start gap-3">
            <div class="feature-icon flex-shrink-0">&#128664;</div>
            <div>
                <h6 class="fw-bold mb-1">Carter</h6>
                <p class="text-muted small mb-2">Sewa satu mobil, rombongan/pribadi. Harga nego.</p>
                <a href="https://wa.me/{{ config('travel.wa') }}?text={{ urlencode('Halo Hayabusa Lintas, saya mau tanya carter mobil.') }}" target="_blank" rel="noopener" class="btn btn-wa btn-sm">Tanya via WA</a>
            </div>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card layanan-item h-100"><div class="card-body d-flex align-items-start gap-3">
            <div class="feature-icon flex-shrink-0">&#127796;</div>
            <div>
                <h6 class="fw-bold mb-1">Tour</h6>
                <p class="text-muted small mb-2">Paket wisata ke berbagai destinasi KalBar.</p>
                <a href="https://wa.me/{{ config('travel.wa') }}?text={{ urlencode('Halo Hayabusa Lintas, saya mau tanya paket tour.') }}" target="_blank" rel="noopener" class="btn btn-wa btn-sm">Tanya via WA</a>
            </div>
        </div></div>
    </div>
</div>

<span class="section-eyebrow">Jasa Titipan</span>
<h2 class="section-title mb-1">Kirim barang? Bisa juga.</h2>
<p class="text-muted mb-4">Menerima jasa titipan antar-kota se-Kalimantan Barat. Aman, cepat, sampai tujuan.</p>

<div class="card titipan-panel mb-5">
    <div class="card-body p-4">
        <div class="row g-3 g-md-4 text-center">
            @php
                $titipan = [
                    ['&#128230;', 'Paket'],
                    ['&#128196;', 'Dokumen'],
                    ['&#128666;', 'Cargo'],
                    ['&#128062;', 'Hewan'],
                    ['&#127949;', 'Motor'],
                    ['&#10133;', 'Dll'],
            
                ];
            @endphp
            @foreach ($titipan as [$ikon, $nama])
                <div class="col-4 col-md-2">
                    <div class="titipan-item">
                        <div class="titipan-ico">{!! $ikon !!}</div>
                        <div class="titipan-nama">{{ $nama }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <hr class="my-4">

        <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">
            <div class="me-md-auto text-center text-md-start">
                <div class="fw-bold">Mau kirim barang hari ini?</div>
                <div class="text-muted small">Chat admin untuk tanya ongkir &amp; estimasi sampai.</div>
            </div>
            <a href="https://wa.me/{{ config('travel.wa') }}?text={{ urlencode('Halo Hayabusa Lintas, saya mau tanya jasa titipan barang.') }}" target="_blank" rel="noopener" class="btn btn-wa">Tanya Jasa Titipan via WhatsApp</a>
        </div>
    </div>
</div>

<div class="card mb-2" style="background:var(--kb-navy);color:#fff">
    <div class="card-body p-4 d-flex flex-wrap align-items-center gap-3">
        <div class="me-auto">
            <h4 class="fw-bold mb-1">Rombongan atau carter satu mobil?</h4>
            <p class="mb-0" style="color:rgba(255,255,255,.78)">Chat admin untuk harga khusus rombongan, antar paket, dan carter harian.</p>
        </div>
        <a class="btn btn-gold" href="https://wa.me/{{ config('travel.wa') }}" target="_blank" rel="noopener">Tanya via WhatsApp</a>
    </div>
</div>

{{-- Modal form pemesanan singkat: data tersimpan ke admin + laporan, lalu lanjut ke WhatsApp --}}
<div class="modal fade" id="modalPesan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('pesan.rute') }}">
                @csrf
                <input type="hidden" name="route_id" id="pesanRouteId">
                    <h5 class="modal-title">Pesan Kursi</h5>
                    <h5 class="modal-title">Pesan Tiket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-light border mb-3">
                        <div class="fw-bold" id="pesanRuteNama">Rute</div>
                        <div class="text-muted small">Harga <span id="pesanRuteHarga">Rp 0</span> /orang</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Penumpang</label>
                        <input type="text" name="nama_penumpang" class="form-control" required maxlength="100" placeholder="Nama lengkap">
                    </div>
                    <div class="row g-2">
                        <div class="col-7 mb-1">
                            <label class="form-label fw-semibold">No. HP / WA</label>
                            <input type="text" name="hp" class="form-control" required minlength="9" maxlength="20" placeholder="cth: 0812xxxxxxx">
                        </div>
                        <div class="col-5 mb-1">
                            <label class="form-label fw-semibold">Jumlah</label>
                            <input type="number" name="jumlah_kursi" class="form-control" required min="1" max="20" value="1">
                        </div>
                    </div>
                    <input type="hidden" name="tanggal" value="{{ date('Y-m-d') }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-wa">Lanjut ke WhatsApp</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var modalEl = document.getElementById('modalPesan');
    if (!modalEl || !window.bootstrap) return;
    var modal = new bootstrap.Modal(modalEl);

    document.querySelectorAll('.btn-pesan').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('pesanRouteId').value = btn.dataset.route;
            document.getElementById('pesanRuteNama').textContent = btn.dataset.nama;
            var harga = parseInt(btn.dataset.harga || '0', 10);
            document.getElementById('pesanRuteHarga').textContent =
                'Rp ' + harga.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            modal.show();
        });
    });
})();
</script>
@endpush
