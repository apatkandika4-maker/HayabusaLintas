@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card overflow-hidden">
            <div class="row g-0">
                <div class="col-md-5 auth-side p-4 d-none d-md-block">
                    <h4 class="fw-bold mt-2">Panel Admin</h4>
                    <p style="color:rgba(255,255,255,.8)">Hayabusa Lintas &mdash; Travel &amp; Cargo. Masuk untuk mengelola rute, jadwal, armada, dan booking.</p>
                    <ul class="list-unstyled mt-4">
                        <li>&#10003; Kelola rute &amp; harga</li>
                        <li>&#10003; Atur jadwal &amp; kursi</li>
                        <li>&#10003; Pantau booking masuk</li>
                    </ul>
                </div>
                <div class="col-md-7">
                    <div class="card-body p-4 p-lg-5">
                        <h2 class="fw-bold mb-1">Login</h2>
                        <p class="text-muted mb-4">Masuk ke akun admin Hayabusa Lintas</p>
                        <form method="POST" action="{{ url('/admin-login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <input type="email" name="email" id="email" class="form-control form-control-lg" value="{{ old('email') }}" required autofocus autocomplete="email">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <input type="password" name="password" id="password" class="form-control form-control-lg" required autocomplete="current-password">
                            </div>
                            <div class="mb-4 form-check">
                                <input type="checkbox" name="remember" id="remember" class="form-check-input" value="1">
                                <label for="remember" class="form-check-label">Ingat saya</label>
                            </div>
                            <button type="submit" class="btn btn-navy btn-lg w-100">Masuk</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
