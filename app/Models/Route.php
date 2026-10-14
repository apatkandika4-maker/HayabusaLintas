<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Route extends Model
{
    protected $fillable = ['origin_city_id', 'dest_city_id', 'kategori', 'jarak_km', 'harga_dasar'];

    /** Daftar kategori rute beserta label tampilannya. */
    public const KATEGORI = [
        'pantura' => 'Rute Pantura',
        'hulu' => 'Rute Hulu',
        'provinsi' => 'Rute Provinsi',
    ];

    protected function casts(): array
    {
        return [
            'jarak_km' => 'integer',
            'harga_dasar' => 'integer',
        ];
    }

    public function labelKategori(): string
    {
        return self::KATEGORI[$this->kategori] ?? 'Rute Lainnya';
    }

    /**
     * Link WhatsApp dengan pesan pemesanan terisi otomatis (rute + harga).
     * Dibuat sesederhana mungkin: orang klik -> langsung chat admin.
     */
    public function waPesanLink(?string $tanggal = null): string
    {
        $asal = $this->origin->nama ?? '-';
        $tujuan = $this->dest->nama ?? '-';
        $harga = 'Rp '.number_format((int) $this->harga_dasar, 0, ',', '.');

        $pesan = "Halo Hayabusa Lintas, saya mau pesan tiket:\n"
            ."Rute: {$asal} → {$tujuan}\n"
            ."Harga: {$harga}/kursi\n";

        if ($tanggal) {
            $pesan .= "Tanggal: {$tanggal}\n";
        }

        $pesan .= 'Mohon info jadwal & ketersediaan kursinya. Terima kasih.';

        return 'https://wa.me/'.config('travel.wa').'?text='.urlencode($pesan);
    }

    public function origin(): BelongsTo
    {
        return $this->belongsTo(City::class, 'origin_city_id');
    }

    public function dest(): BelongsTo
    {
        return $this->belongsTo(City::class, 'dest_city_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
}
