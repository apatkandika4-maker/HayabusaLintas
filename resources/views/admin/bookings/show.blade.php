@extends('admin.layout')

@section('title', 'Detail Booking')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div>
        <h2 class="admin-page-title mb-0">Detail Booking</h2>
        <div class="mt-1">
            @if ($booking->status === 'done')
                <span class="kb-badge kb-badge-success">Selesai</span>
            @else
                <span class="kb-badge kb-badge-warning">Belum Selesai</span>
            @endif
        </div>
    </div>
    <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">&larr; Kembali</a>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="admin-panel mb-3">
            <div class="panel-head">Data Penumpang</div>
            <div class="p-3">
                <div class="detail-row"><span class="k">Nama</span><span class="v">{{ $booking->nama_penumpang }}</span></div>
                <div class="detail-row"><span class="k">No. HP / WA</span><span class="v">{{ $booking->hp }}</span></div>
                <div class="detail-row"><span class="k">Alamat Jemput</span><span class="v">{{ $booking->alamat_jemput ?: '-' }}</span></div>
            </div>
        </div>

        <div class="admin-panel">
            <div class="panel-head">Rute &amp; Perjalanan</div>
            <div class="p-3">
                <div class="detail-row"><span class="k">Rute</span><span class="v">{{ $booking->labelRute() }}</span></div>
                <div class="detail-row"><span class="k">Tanggal</span><span class="v">{{ $booking->tanggal ? \Illuminate\Support\Carbon::parse($booking->tanggal)->translatedFormat('l, d F Y') : '-' }}</span></div>
                <div class="detail-row"><span class="k">Jumlah Penumpang</span><span class="v">{{ $booking->jumlah_kursi }} orang</span></div>
                <div class="detail-row"><span class="k">Jam Berangkat</span><span class="v">Dikonfirmasi via WA</span></div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="admin-panel mb-3">
            <div class="panel-head">Pembayaran</div>
            <div class="p-3">
                <div class="detail-row"><span class="k">Metode</span><span class="v">Bayar di Tempat (Cash)</span></div>
                <div class="detail-row"><span class="k">Perkiraan Biaya</span><span class="v" style="font-size:1.15rem">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span></div>
            </div>
        </div>

        <div class="admin-panel">
            <div class="panel-head">Aksi</div>
            <div class="p-3 d-grid gap-2">
                <form action="{{ route('admin.bookings.selesai', $booking) }}" method="POST">
                    @csrf @method('PATCH')
                    @if ($booking->status === 'done')
                        <button class="btn btn-outline-secondary w-100">Batalkan Tanda Selesai</button>
                    @else
                        <button class="btn btn-gold w-100">&#10003; Tandai Selesai (masuk pendapatan)</button>
                    @endif
                </form>

                @if ($booking->waPenumpangLink())
                    <a href="{{ $booking->waPenumpangLink() }}" target="_blank" rel="noopener" class="btn btn-wa">Chat Penumpang via WA</a>
                @endif

                <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn btn-outline-navy">&#9998; Edit</a>

                <hr class="my-1">
                <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" onsubmit="return confirm('Hapus permanen booking {{ $booking->nama_penumpang }}? Data tidak bisa dikembalikan.')">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger w-100">&#128465; Hapus Permanen</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
