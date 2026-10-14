@extends('admin.layout')

@section('title', 'Data Kota')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="admin-page-title mb-0">Data Kota</h2>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="admin-panel">
            <div class="panel-head">Tambah Kota</div>
            <form action="{{ route('admin.cities.store') }}" method="POST" class="p-3">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Kota</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" placeholder="cth: Landak" required maxlength="100" autofocus>
                    <div class="form-text">Kota baru langsung tersedia saat membuat rute &amp; jadwal.</div>
                </div>
                <button class="btn btn-gold w-100">+ Tambah Kota</button>
            </form>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="admin-panel">
            <div class="panel-head">Daftar Kota ({{ $cities->count() }})</div>
            <div class="table-responsive">
                <table class="table table-admin align-middle mb-0">
                    <thead>
                        <tr><th>Nama</th><th>Dipakai Rute</th><th class="text-end">Aksi</th></tr>
                    </thead>
                    <tbody>
                        @forelse ($cities as $city)
                            <tr>
                                <td class="fw-semibold">{{ $city->nama }}</td>
                                <td>
                                    @php $dipakai = $city->origin_routes_count + $city->dest_routes_count; @endphp
                                    @if ($dipakai > 0)
                                        <span class="kb-badge kb-badge-info">{{ $dipakai }} rute</span>
                                    @else
                                        <span class="kb-badge kb-badge-neutral">belum dipakai</span>
                                    @endif
                                </td>
                                <td class="text-end text-nowrap">
                                    <button type="button" class="btn btn-sm btn-outline-navy" data-bs-toggle="modal" data-bs-target="#editKota{{ $city->id }}">Edit</button>
                                    <form action="{{ route('admin.cities.destroy', $city) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus kota {{ $city->nama }}?')" {{ $dipakai > 0 ? 'disabled title=Masih dipakai rute' : '' }}>Hapus</button>
                                    </form>

                                    <div class="modal fade" id="editKota{{ $city->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.cities.update', $city) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Ubah Kota</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body text-start">
                                                        <label class="form-label fw-semibold">Nama Kota</label>
                                                        <input type="text" name="nama" class="form-control" value="{{ $city->nama }}" required maxlength="100">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button class="btn btn-gold">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">Belum ada kota.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
