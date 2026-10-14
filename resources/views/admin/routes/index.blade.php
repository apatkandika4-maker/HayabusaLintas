@extends('admin.layout')

@section('title', 'Data Rute')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="admin-page-title mb-0">Data Rute</h2>
    <a href="{{ route('admin.routes.create') }}" class="btn btn-gold">+ Tambah Rute</a>
</div>

<div class="admin-panel">
    <div class="table-responsive">
        <table class="table table-admin align-middle mb-0">
            <thead><tr><th>Asal</th><th>Tujuan</th><th>Kategori</th><th>Harga Dasar</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
                @forelse ($routes as $route)
                    <tr>
                        <td class="fw-semibold">{{ $route->origin->nama ?? '-' }}</td>
                        <td>{{ $route->dest->nama ?? '-' }}</td>
                        <td><span class="kb-badge kb-badge-neutral">{{ $route->labelKategori() }}</span></td>
                        <td>Rp {{ number_format($route->harga_dasar, 0, ',', '.') }}</td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('admin.routes.edit', $route) }}" class="btn btn-sm btn-outline-navy">Ubah</a>
                            <form action="{{ route('admin.routes.destroy', $route) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus rute ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada rute.</td></tr>
                    @php /* colspan 5: Asal, Tujuan, Kategori, Harga, Aksi */ @endphp
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($routes instanceof \Illuminate\Pagination\LengthAwarePaginator && $routes->hasPages())
        <div class="p-3 border-top">{{ $routes->links() }}</div>
    @endif
</div>
@endsection
