<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Route as TravelRoute;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RuteKalbarSeeder extends Seeder
{
    /**
     * Seed kota + rute berdasarkan daftar Pantura, Hulu, dan Provinsi.
     * Idempotent: aman dijalankan berulang (pakai firstOrCreate).
     */
    public function run(): void
    {
        // [nama tujuan, kategori, perkiraan harga dasar]
        $rute = [
            // ===== Rute Pantura =====
            ['Jungkat', 'pantura', 25000],
            ['Pinyuh', 'pantura', 40000],
            ['Mempawah', 'pantura', 50000],
            ['Sungai Kunyit', 'pantura', 60000],
            ['Sungai Duri', 'pantura', 80000],
            ['Singkawang', 'pantura', 100000],
            ['Selakau', 'pantura', 110000],
            ['Pemangkat', 'pantura', 120000],
            ['Tebas', 'pantura', 130000],
            ['Sambas', 'pantura', 150000],
            ['Sekurak', 'pantura', 160000],
            ['Paloh', 'pantura', 180000],
            ['Galing', 'pantura', 170000],
            ['Aruk', 'pantura', 200000],
            ['Bengkayang', 'pantura', 120000],
            ['Ledo', 'pantura', 140000],
            ['Sanggau Ledo', 'pantura', 150000],
            ['Jagoi Babang', 'pantura', 220000],

            // ===== Rute Hulu =====
            ['Sungai Ampar', 'hulu', 90000],
            ['Tayan', 'hulu', 110000],
            ['Sandai', 'hulu', 200000],
            ['Sungai Melayu', 'hulu', 230000],
            ['Indotani', 'hulu', 180000],
            ['Ketapang', 'hulu', 300000],
            ['Balai Karangan', 'hulu', 200000],
            ['Entikong', 'hulu', 220000],
            ['Sanggau', 'hulu', 180000],
            ['Sekadau', 'hulu', 220000],
            ['Sintang', 'hulu', 280000],
            ['Putussibau', 'hulu', 450000],

            // ===== Rute Provinsi =====
            ['Kalteng', 'provinsi', 450000],
            ['Kaltim', 'provinsi', 700000],
            ['Kalsel', 'provinsi', 650000],
        ];

        $pontianak = City::firstOrCreate(
            ['slug' => Str::slug('Pontianak')],
            ['nama' => 'Pontianak']
        );

        foreach ($rute as [$namaTujuan, $kategori, $harga]) {
            $tujuan = City::firstOrCreate(
                ['slug' => Str::slug($namaTujuan)],
                ['nama' => $namaTujuan]
            );

            $route = TravelRoute::firstOrCreate(
                [
                    'origin_city_id' => $pontianak->id,
                    'dest_city_id' => $tujuan->id,
                ],
                [
                    'kategori' => $kategori,
                    'harga_dasar' => $harga,
                ]
            );

            // Pastikan kategori & harga terupdate walau rute sudah ada sebelumnya.
            $route->update(['kategori' => $kategori]);
            if ((int) $route->harga_dasar === 0) {
                $route->update(['harga_dasar' => $harga]);
            }
        }
    }
}
