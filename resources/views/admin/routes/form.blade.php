@extends('admin.layout')

@section('title', ($route->exists ? 'Ubah' : 'Tambah') . ' Rute')

@section('content')
<h2 class="mb-3">{{ $route->exists ? 'Ubah' : 'Tambah' }} Rute</h2>

<div class="card">
    <div class="card-body">
        <form action="{{ $route->exists ? route('admin.routes.update', $route) : route('admin.routes.store') }}" method="POST">
            @csrf
            @if ($route->exists)
                @method('PUT')
            @endif
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Kota Asal</label>
                    <select name="origin_city_id" class="form-select" required>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}" @selected((int) old('origin_city_id', $route->origin_city_id) === $city->id)>{{ $city->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kota Tujuan</label>
                    <select name="dest_city_id" class="form-select" required>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}" @selected((int) old('dest_city_id', $route->dest_city_id) === $city->id)>{{ $city->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <div class="form-text">Kota belum ada di daftar? <a href="{{ route('admin.cities.index') }}" target="_blank" rel="noopener">Tambah kota baru &rarr;</a> lalu muat ulang halaman ini.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kategori Rute</label>
                    <select name="kategori" class="form-select" required>
                        @foreach (\App\Models\Route::KATEGORI as $key => $label)
                            <option value="{{ $key }}" @selected(old('kategori', $route->kategori ?? 'pantura') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Harga Dasar (Rp)</label>
                    <input type="number" name="harga_dasar" class="form-control" min="0" value="{{ old('harga_dasar', $route->harga_dasar) }}" required>
                </div>
            </div>
            <div class="mt-3">
                <button class="btn btn-gold">Simpan</button>
                <a href="{{ route('admin.routes.index') }}" class="btn btn-outline-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
