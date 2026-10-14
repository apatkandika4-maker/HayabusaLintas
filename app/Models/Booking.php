<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Booking extends Model
{
    protected $fillable = [
        'kode', 'nama_penumpang', 'hp', 'alamat_jemput', 'shareloc', 'schedule_id',
        'route_id', 'tanggal', 'jumlah_kursi', 'no_kursi', 'total_harga', 'status',
        'bukti_bayar', 'metode', 'user_id',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_kursi' => 'integer',
            'total_harga' => 'integer',
            'no_kursi' => 'array',
            'tanggal' => 'date',
        ];
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    /**
     * Nama rute: dari relasi route (booking baru) atau schedule (booking lama).
     */
    public function labelRute(): string
    {
        $r = $this->route ?? $this->schedule?->route;
        if (! $r) {
            return '-';
        }

        return ($r->origin->nama ?? '-').' → '.($r->dest->nama ?? '-');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate kode booking unik format KB-YYYYMMDD-XXXX.
     */
    public static function generateKode(): string
    {
        do {
            $kode = 'KB-'.now()->format('Ymd').'-'.Str::upper(Str::random(4));
        } while (static::where('kode', $kode)->exists());

        return $kode;
    }

    /**
     * Link WA ke admin untuk konfirmasi booking (ditekan penumpang).
     */
    public function waKonfirmasiLink(): string
    {
        $this->loadMissing(['route.origin', 'route.dest', 'schedule.route.origin', 'schedule.route.dest']);
        $rute = $this->labelRute();
        $tgl = $this->tanggal ? $this->tanggal->format('d M Y') : ($this->schedule->tanggal ?? '-');
        $pesan = "Halo Admin Hayabusa Lintas 🙏\n"
            ."Saya ingin memesan kursi travel dengan detail berikut:\n\n"
            ."Nama: {$this->nama_penumpang}\n"
            ."No. HP: {$this->hp}\n"
            ."Rute: {$rute}\n"
            ."Jumlah Kursi: {$this->jumlah_kursi} kursi\n"
            ."Perkiraan Biaya: Rp ".number_format((int) $this->total_harga, 0, ',', '.')."\n\n"
            .'Mohon info ketersediaan kursi dan jam keberangkatannya ya. Terima kasih 🙏';

        return 'https://wa.me/'.config('travel.wa').'?text='.urlencode($pesan);
    }

    /**
     * Link WA admin untuk chat penumpang (dipakai di panel admin).
     */
    public function waPenumpangLink(): ?string
    {
        $hp = preg_replace('/\D/', '', (string) $this->hp);
        if ($hp !== null && str_starts_with($hp, '0')) {
            $hp = '62'.substr($hp, 1);
        }
        if (! $hp) {
            return null;
        }
        $pesan = "Halo {$this->nama_penumpang}, ini dari Hayabusa Lintas terkait booking {$this->kode}.";

        return 'https://wa.me/'.$hp.'?text='.urlencode($pesan);
    }
}
