@extends('admin.layout')

@section('title', 'Edit Booking')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="admin-page-title mb-0">Edit Booking</h2>
    <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-outline-secondary">&larr; Batal</a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="admin-panel">
            <div class="panel-head">Ubah Data Booking</div>
            <form action="{{ route('admin.bookings.update', $booking) }}" method="POST" class="p-3">
                @csrf
                @method('PATCH')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Penumpang</label>
                    <input type="text" name="nama_penumpang" class="form-control" value="{{ old('nama_penumpang', $booking->nama_penumpang) }}" required maxlength="100">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">No. HP / WA</label>
                    <input type="text" name="hp" class="form-control" value="{{ old('hp', $booking->hp) }}" required maxlength="20">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Alamat Jemput</label>
                    <input type="text" name="alamat_jemput" class="form-control" value="{{ old('alamat_jemput', $booking->alamat_jemput) }}" maxlength="255">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="pending" @selected(old('status', $booking->status) !== 'done')>Belum Selesai</option>
                        <option value="done" @selected(old('status', $booking->status) === 'done')>Selesai (masuk pendapatan)</option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-gold">Simpan Perubahan</button>
                    <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
