<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fleet extends Model
{
    protected $fillable = ['nama', 'tipe', 'plat', 'nomor_lambung', 'kapasitas', 'fasilitas', 'aktif'];

    protected function casts(): array
    {
        return [
            'kapasitas' => 'integer',
            'aktif' => 'boolean',
        ];
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
}
