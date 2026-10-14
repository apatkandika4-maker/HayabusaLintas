@php
$kelas = [
    'pending' => 'kb-badge-warning',
    'paid' => 'kb-badge-info',
    'verified' => 'kb-badge-success',
    'cancelled' => 'kb-badge-danger',
    'done' => 'kb-badge-neutral',
    'aktif' => 'kb-badge-success',
    'penuh' => 'kb-badge-warning',
    'batal' => 'kb-badge-danger',
    'selesai' => 'kb-badge-neutral',
][$status] ?? 'kb-badge-neutral';
$label = [
    'pending' => 'Pending',
    'paid' => 'Sudah Bayar',
    'verified' => 'Lunas',
    'cancelled' => 'Dibatalkan',
    'done' => 'Selesai',
    'aktif' => 'Aktif',
    'penuh' => 'Penuh',
    'batal' => 'Batal',
    'selesai' => 'Selesai',
][$status] ?? ucfirst($status);
@endphp
<span class="kb-badge {{ $kelas }}">{{ $label }}</span>
