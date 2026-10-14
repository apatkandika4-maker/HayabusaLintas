<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    protected $fillable = ['route_id', 'fleet_id', 'tanggal', 'jam_berangkat', 'harga', 'sisa_kursi', 'kursi_diblokir', 'status'];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'harga' => 'integer',
            'sisa_kursi' => 'integer',
            'kursi_diblokir' => 'array',
        ];
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Daftar nomor kursi yang sudah terisi (dari booking yang belum dibatalkan).
     *
     * @return string[]
     */
    public function kursiTerisi(): array
    {
        $terisi = [];

        $bookings = $this->relationLoaded('bookings')
            ? $this->bookings
            : $this->bookings()->where('status', '!=', 'cancelled')->get();

        foreach ($bookings as $booking) {
            if ($booking->status === 'cancelled') {
                continue;
            }
            $raw = $booking->no_kursi;
            if (empty($raw)) {
                continue;
            }
            $list = is_array($raw) ? $raw : explode(',', (string) $raw);
            foreach ($list as $no) {
                $no = trim((string) $no);
                if ($no !== '' && ! in_array($no, $terisi, true)) {
                    $terisi[] = $no;
                }
            }
        }

        sort($terisi, SORT_NATURAL);

        return $terisi;
    }

    /**
     * Nomor kursi (int) yang terpakai karena booking web (non-cancelled).
     *
     * @return int[]
     */
    public function kursiBooking(): array
    {
        return array_values(array_unique(array_map('intval', $this->kursiTerisi())));
    }

    /**
     * Nomor kursi (int) yang ditandai admin sebagai terisi (walk-in / WA).
     *
     * @return int[]
     */
    public function kursiDiblokir(): array
    {
        return array_values(array_unique(array_map('intval', (array) ($this->kursi_diblokir ?? []))));
    }

    /**
     * Gabungan semua kursi terisi: booking web + ditandai admin.
     *
     * @return int[]
     */
    public function kursiTerisiSemua(): array
    {
        $gabung = array_unique(array_merge($this->kursiBooking(), $this->kursiDiblokir()));
        sort($gabung);

        return array_values($gabung);
    }

}
