<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Hayabusa Lintas - travel & cargo antar-kota Kalimantan Barat: Pontianak, Singkawang, Sambas, Mempawah, Ketapang, Sintang, dan lainnya.">
    <title>@yield('title', 'Hayabusa Lintas') &mdash; Travel &amp; Cargo Kalimantan Barat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>
<body>
<div class="topbar d-none d-md-block">
    <div class="container d-flex justify-content-between py-1">
        <span>Berangkat setiap hari &middot; Pontianak &mdash; seluruh KalBar</span>
        <span>WA: <a href="https://wa.me/{{ config('travel.wa') }}" target="_blank" rel="noopener">{{ config('travel.wa_label') }}</a> &middot; 06.00&ndash;21.00 WIB</span>
    </div>
</div>
<nav class="navbar navbar-expand-lg navbar-kalbar sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ url('/') }}">
            <span class="brand-mark">HL</span> Hayabusa Lintas
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navKalbar" aria-controls="navKalbar" aria-expanded="false" aria-label="Navigasi">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navKalbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Beranda</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/admin') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ url('/logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary btn-sm ms-lg-2">Logout</button>
                            </form>
                        </li>
                    @endif
                @endauth
                <li class="nav-item ms-lg-2">
                    <a class="btn btn-gold btn-sm" href="{{ url('/') }}#cari">Pesan Sekarang</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

@hasSection('hero')
    @yield('hero')
@endif

<main class="container my-4">
    @php $flashSukses = session('sukses', session('success')); $flashGagal = session('gagal', session('error')); @endphp
    @if($flashSukses)
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $flashSukses }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif
    @if($flashGagal)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $flashGagal }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif
    @if(isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Periksa kembali isian Anda:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @yield('content')
</main>

<footer class="footer-kalbar mt-5">
    <div class="container py-5">
        <div class="row">
            <div class="col-md-5 mb-4">
                <h5 class="fw-bold text-white"><span class="brand-mark">HL</span> Hayabusa Lintas</h5>
                <p class="mb-1">Travel &amp; Cargo antar-kota se-Kalimantan Barat. Amanah dalam setiap perjalanan, standby 24 jam.</p>
                <p class="mb-0">Jl. Husein Hamzah, Pal V Gg Mufakat No. 214, Pontianak, Kalimantan Barat</p>
            </div>
            <div class="col-md-3 mb-4">
                <h6 class="fw-bold text-white">Layanan</h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-1"><a href="{{ url('/') }}#cari">Cari Jadwal</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-4">
                <h6 class="fw-bold text-white">Hubungi Kami</h6>
                <p class="mb-1">WA: <a href="https://wa.me/{{ config('travel.wa') }}" target="_blank" rel="noopener">{{ config('travel.wa_label') }}</a></p>
                <p class="mb-3">Standby 24 Jam &middot; Admin: Iyan</p>
                <a class="btn btn-gold btn-sm" href="https://wa.me/{{ config('travel.wa') }}" target="_blank" rel="noopener">Chat Admin Sekarang</a>
            </div>
        </div>
        <hr class="border-secondary">
        <p class="text-center small mb-0">&copy; {{ date('Y') }} Hayabusa Lintas &mdash; Travel &amp; Cargo, Pontianak, Kalimantan Barat.</p>
    </div>
</footer>

<a class="wa-float no-print" href="https://wa.me/{{ config('travel.wa') }}" target="_blank" rel="noopener" aria-label="Chat WhatsApp">
    <span>Chat WA</span>
</a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
@stack('scripts')
</body>
</html>
