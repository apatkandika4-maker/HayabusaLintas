@extends('admin.layout')

@section('title', ($schedule->exists ? 'Ubah' : 'Tambah') . ' Jadwal')

@section('content')
<h2 class="mb-3">{{ $schedule->exists ? 'Ubah' : 'Tambah' }} Jadwal</h2>

<div class="card">
    <div class="card-body">
        <form action="{{ $schedule->exists ? route('admin.schedules.update', $schedule) : route('admin.schedules.store') }}" method="POST">
            @csrf
            @if ($schedule->exists)
                @method('PUT')
            @endif
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Rute</label>
                    <select name="route_id" class="form-select" required>
                        @foreach ($routes as $route)
                            <option value="{{ $route->id }}" @selected((int) old('route_id', $schedule->route_id) === $route->id)>
                                {{ $route->origin->nama ?? '?' }} → {{ $route->dest->nama ?? '?' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal <small class="text-muted">(tgl/bln/thn)</small></label>
                    @php
                        $tglVal = old('tanggal', $schedule->tanggal ? \Illuminate\Support\Carbon::parse($schedule->tanggal)->format('Y-m-d') : '');
                    @endphp
                    <input type="text" id="tanggalPicker" class="form-control" placeholder="hh/bb/tttt" autocomplete="off" required
                        value="{{ $tglVal }}">
                    <input type="hidden" name="tanggal" id="tanggalValue" value="{{ $tglVal }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Harga (Rp)</label>
                    <input type="number" name="harga" class="form-control" min="0" value="{{ old('harga', $schedule->harga) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jumlah Kursi <small class="text-muted">(kosongkan = default 7)</small></label>
                    <input type="number" name="sisa_kursi" class="form-control" min="0" value="{{ old('sisa_kursi', $schedule->exists ? $schedule->sisa_kursi : '') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        @foreach (['aktif', 'penuh', 'batal', 'selesai'] as $status)
                            <option value="{{ $status }}" @selected(old('status', $schedule->status) === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-3">
                <button class="btn btn-gold">Simpan</button>
                <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
<script>
(function () {
    var picker = document.getElementById('tanggalPicker');
    var hidden = document.getElementById('tanggalValue');
    if (!picker || !window.flatpickr) return;

    flatpickr(picker, {
        locale: 'id',
        altInput: true,
        altFormat: 'd/m/Y',   // tampilan untuk admin: tanggal/bulan/tahun
        dateFormat: 'Y-m-d',  // nilai asli yang dikirim ke server
        defaultDate: hidden.value || null,
        onReady: function (sel, str, inst) { hidden.value = inst.input.value; },
        onChange: function (sel, str, inst) { hidden.value = str; }
    });
})();
</script>
@endpush
@endsection
