<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    protected $fillable = ['nama', 'slug'];

    public function originRoutes(): HasMany
    {
        return $this->hasMany(Route::class, 'origin_city_id');
    }

    public function destRoutes(): HasMany
    {
        return $this->hasMany(Route::class, 'dest_city_id');
    }
}
