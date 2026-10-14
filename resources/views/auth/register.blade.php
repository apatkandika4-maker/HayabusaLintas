@extends('layouts.app')

@section('title', 'Daftar Akun')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card overflow-hidden">
            <div class="row g-0">
                <div class="col-md-5 auth-side p-4 d-none d-md-block">
                    <h4 class="fw-bold mt-2">Satu akun, semua rute.</h4>
                    <p style="color:rgba(255,255,255,.8)">Daftar gratis, pesan kursi ke seluruh KalBar dalam hitungan detik.</p>
                    <ul class="list-unstyled mt-4">
                        <li>&#10003; Checkout 1 menit</li>
                        <li>&#10003; E-tiket otomatis</li>
                        <li>&#10003; Konfirmasi via WhatsApp</li>
                    </ul>
                </div>
                <div class="col-md-7">
                    <div class="card-body p-4 p-lg-5">
                        <h2 class="fw-bold mb-1">Daftar</h2>
                        <p class="text-muted mb-4">Buat akun untuk memesan lebih cepat</p>
                        <form method="POST" action="{{ url('/register') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                                <input type="text" name="name" id="name" class="form-control form-control-lg" value="{{ old('name') }}" required maxlength="100" autocomplete="name">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <input type="email" name="email" id="email" class="form-control form-control-lg" value="{{ old('email') }}" required autocomplete="email">
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label fw-semibold">No. HP / WA</label>
                                <input type="text" name="phone" id="phone" class="form-control form-control-lg" value="{{ old('phone') }}" required maxlength="20" placeholder="cth: 081234567890" autocomplete="tel">
                            </div>
                            <div class="row g-2">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label fw-semibold">Password</label>
                                    <input type="password" name="password" id="password" class="form-control form-control-lg" required minlength="8" autocomplete="new-password">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-lg" required autocomplete="new-password">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-navy btn-lg w-100">Daftar</button>
                        </form>
                        <p class="text-center mt-3 mb-0">Sudah punya akun? <a href="{{ url('/login') }}">Login</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
