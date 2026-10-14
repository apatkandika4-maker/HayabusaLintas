@extends('admin.layout')

@section('title', 'Data Jadwal')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h2 class="admin-page-title mb-0">Data Jadwal</h2>
    <div class="d-flex gap-2">
        @if ($schedules->total() > 0)
            <form action="{{ route('admin.schedules.destroyAll') }}" method="POST" onsubmit="return confirm('Hapus SEMUA jadwal? Jadwal yang sudah ada booking akan dilewati. Tindakan ini tidak bisa dibatalkan.')">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger">Hapus Semua Jadwal</button>
            </form>
        @endif
        <a href="{{ route('admin.schedules.create') }}" class="btn btn-gold">+ Tambah Jadwal</a>
    </div>
</div>

<div class="filter-toolbar mb-3">
    <div class="d-flex flex-wrap align-items-center gap-2">
        <div class="btn-group" role="group">
            <a href="{{ route('admin.schedules.index', ['filter' => 'hari_ini']) }}" class="btn btn-sm {{ ($filter ?? '') === 'hari_ini' ? 'btn-navy' : 'btn-outline-navy' }}">Hari Ini</a>
            <a href="{{ route('admin.schedules.index', ['filter' => 'mendatang']) }}" class="btn btn-sm {{ ($filter ?? '') === 'mendatang' ? 'btn-navy' : 'btn-outline-navy' }}">Mendatang</a>
            <a href="{{ route('admin.schedules.index') }}" class="btn btn-sm {{ empty($filter) && empty($tanggal) ? 'btn-navy' : 'btn-outline-navy' }}">Semua</a>
        </div>
        <form method="GET" action="{{ route('admin.schedules.index') }}" class="d-flex align-items-center gap-2 ms-auto">
            <label class="form-label small fw-semibold mb-0">Tanggal:</label>
            <input type="date" name="tanggal" class="form-control form-control-sm" style="max-width:170px" value="{{ $tanggal }}">
            <button class="btn btn-sm btn-gold">Lihat</button>
        </form>
    </div>
</div>

<div class="admin-panel">
    <div class="table-responsive">
        <table class="table table-admin align-middle mb-0">
            <thead><tr><th>Tanggal</th><th>Rute</th><th>Harga</th><th>Kursi</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
                @forelse ($schedules as $schedule)
                    <tr>
                        <td>{{ \Illuminate\Support\Carbon::parse($schedule->tanggal)->format('d M Y') }}</td>
                        <td>{{ $schedule->route->origin->nama ?? '-' }} &rarr; {{ $schedule->route->dest->nama ?? '-' }}</td>
                        <td>Rp {{ number_format($schedule->harga, 0, ',', '.') }}</td>
                        <td>{{ (int) $schedule->sisa_kursi }} kursi</td>
                        <td>@include('admin.partials.status-badge', ['status' => $schedule->status])</td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('admin.schedules.edit', $schedule) }}" class="btn btn-sm btn-outline-navy">Ubah</a>
                            <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus jadwal ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada jadwal.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($schedules instanceof \Illuminate\Pagination\LengthAwarePaginator && $schedules->hasPages())
        <div class="p-3 border-top">{{ $schedules->links() }}</div>
    @endif
</div>
@endsection
