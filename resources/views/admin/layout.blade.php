<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — Hayabusa Lintas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>
<body class="admin-body">
<!-- Topbar khusus HP: tombol buka menu -->
<div class="admin-topbar d-md-none">
    <button type="button" class="admin-burger" id="adminBurger" aria-label="Buka menu">&#9776;</button>
    <span class="fw-bold"><span class="brand-mark brand-mark-sm">HL</span> Hayabusa Lintas</span>
    <a href="{{ url('/') }}" target="_blank" rel="noopener" class="ms-auto small" style="color:var(--kb-orange)">Lihat Web</a>
</div>

<!-- Lapisan gelap saat sidebar terbuka di HP -->
<div class="admin-overlay" id="adminOverlay"></div>

<div class="d-flex flex-column flex-md-row">
    <aside class="admin-sidebar p-3 flex-shrink-0" id="adminSidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            <h5 class="mb-0 fw-bold"><span class="brand-mark brand-mark-sm">HL</span> Hayabusa Lintas</h5>
        </a>
        <p class="small mb-2" style="color:#64748b">Panel Admin</p>

        <div class="sidebar-label">Menu Utama</div>
        <nav class="nav flex-column gap-1">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <span class="nav-ico">&#9632;</span> Dashboard
            </a>
            <a class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}" href="{{ route('admin.bookings.index') }}">
                <span class="nav-ico">&#128221;</span> Booking
            </a>
            <a class="nav-link {{ request()->routeIs('admin.routes.*') ? 'active' : '' }}" href="{{ route('admin.routes.index') }}">
                <span class="nav-ico">&#128506;</span> Rute
            </a>
            <a class="nav-link {{ request()->routeIs('admin.cities.*') ? 'active' : '' }}" href="{{ route('admin.cities.index') }}">
                <span class="nav-ico">&#127961;</span> Kota
            </a>
            <a class="nav-link {{ request()->routeIs('admin.passengers.*') ? 'active' : '' }}" href="{{ route('admin.passengers.index') }}">
                <span class="nav-ico">&#128100;</span> Penumpang
            </a>
            <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
                <span class="nav-ico">&#128202;</span> Laporan
            </a>
        </nav>

        <hr class="my-3">
        <nav class="nav flex-column gap-1 mt-auto">
            <a class="nav-link" href="{{ url('/') }}" target="_blank" rel="noopener">
                <span class="nav-ico">&#127758;</span> Lihat Website
            </a>
            <form method="POST" action="{{ url('/logout') }}">
                @csrf
                <button type="submit" class="nav-link logout">
                    <span class="nav-ico">&#9099;</span> Logout
                </button>
            </form>
        </nav>
    </aside>

    <main class="admin-main p-3 p-md-4">
        @if (session('sukses'))
            <div class="alert alert-success">{{ session('sukses') }}</div>
        @endif
        @if (session('gagal'))
            <div class="alert alert-danger">{{ session('gagal') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Buka/tutup sidebar admin di HP
(function () {
    var sidebar = document.getElementById('adminSidebar');
    var overlay = document.getElementById('adminOverlay');
    var burger = document.getElementById('adminBurger');
    if (!sidebar || !overlay || !burger) return;

    function buka() { sidebar.classList.add('show'); overlay.classList.add('show'); }
    function tutup() { sidebar.classList.remove('show'); overlay.classList.remove('show'); }

    burger.addEventListener('click', buka);
    overlay.addEventListener('click', tutup);
    // Tutup otomatis saat klik menu (biar langsung lihat halaman)
    sidebar.querySelectorAll('a.nav-link').forEach(function (a) {
        a.addEventListener('click', tutup);
    });
})();
</script>
@stack('scripts')
</body>
</html>
