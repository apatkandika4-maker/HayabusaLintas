<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Fleet;
use App\Models\Route;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KalbarSeeds extends Seeder
{
    public function run(): void
    {
        $kota = [
            'Pontianak', 'Mempawah', 'Singkawang', 'Sambas', 'Bengkayang',
            'Sanggau', 'Sekadau', 'Sintang', 'Ketapang', 'Putussibau',
        ];

        $cities = [];
        foreach ($kota as $nama) {
            $cities[$nama] = City::firstOrCreate(
                ['slug' => Str::slug($nama)],
                ['nama' => $nama]
            );
        }

        // 12 rute populer (sekali jalan, dioperasikan PP), harga Rp120rb-450rb.
        $rute = [
            ['Pontianak', 'Mempawah', 58, '1 jam 30 menit', 120000],
            ['Pontianak', 'Singkawang', 153, '3 jam 30 menit', 150000],
            ['Pontianak', 'Sambas', 197, '5 jam', 180000],
            ['Pontianak', 'Bengkayang', 128, '3 jam', 130000],
            ['Pontianak', 'Sanggau', 196, '5 jam', 180000],
            ['Pontianak', 'Sekadau', 268, '6 jam 30 menit', 220000],
            ['Pontianak', 'Sintang', 321, '8 jam', 280000],
            ['Pontianak', 'Ketapang', 350, '9 jam', 300000],
            ['Pontianak', 'Putussibau', 680, '14 jam', 450000],
            ['Singkawang', 'Sambas', 60, '1 jam 30 menit', 120000],
            ['Sanggau', 'Sintang', 125, '3 jam', 150000],
            ['Sintang', 'Putussibau', 360, '8 jam', 280000],
        ];

        $routes = [];
        foreach ($rute as [$asal, $tujuan, $jarak, $estimasi, $harga]) {
            $routes[] = Route::firstOrCreate([
                'origin_city_id' => $cities[$asal]->id,
                'dest_city_id' => $cities[$tujuan]->id,
            ], [
                'jarak_km' => $jarak,
                'estimasi' => $estimasi,
                'harga_dasar' => $harga,
            ]);
        }

        $armada = [
            ['Innova G Putih', 'innova', 'KB 1452 QA', 'TK-01', 7, 'AC, Reclining Seat, Charger, Air Mineral'],
            ['Innova Reborn Hitam', 'innova', 'KB 1789 QB', 'TK-02', 7, 'AC, Reclining Seat, Charger, Air Mineral, Bantal'],
            ['Hiace Premio Silver', 'hiace', 'KB 7123 QC', 'TK-03', 14, 'AC, Reclining Seat, Charger, Bagasi Luas'],
            ['Hiace Commuter Putih', 'hiace', 'KB 7345 QD', 'TK-04', 14, 'AC, Charger, Bagasi Luas'],
            ['Avanza Veloz Merah', 'avanza', 'KB 1234 QE', 'TK-05', 6, 'AC, Charger'],
            ['Avanza G Silver', 'avanza', 'KB 1567 QF', 'TK-06', 6, 'AC, Charger'],
            ['Elf Long Putih', 'elf', 'KB 7901 QG', 'TK-07', 16, 'AC, Bagasi Luas'],
            ['Innova Zenix Silver', 'innova', 'KB 1990 QH', 'TK-08', 7, 'AC, Captain Seat, Charger, Air Mineral, Bantal'],
        ];

        $fleets = [];
        foreach ($armada as [$nama, $tipe, $plat, $lambung, $kapasitas, $fasilitas]) {
            $fleets[] = Fleet::firstOrCreate(
                ['plat' => $plat],
                [
                    'nama' => $nama,
                    'tipe' => $tipe,
                    'nomor_lambung' => $lambung,
                    'kapasitas' => $kapasitas,
                    'fasilitas' => $fasilitas,
                    'aktif' => true,
                ]
            );
        }

        // 30 jadwal tersebar 7 hari ke depan.
        $jam = ['07:00', '09:00', '13:00', '16:00', '20:00'];

        for ($i = 0; $i < 30; $i++) {
            $route = $routes[$i % count($routes)];
            $fleet = $fleets[$i % count($fleets)];

            Schedule::firstOrCreate([
                'route_id' => $route->id,
                'fleet_id' => $fleet->id,
                'tanggal' => Carbon::today()->addDays($i % 7)->toDateString(),
                'jam_berangkat' => $jam[$i % count($jam)],
            ], [
                'harga' => $route->harga_dasar,
                'sisa_kursi' => $fleet->kapasitas,
                'status' => 'aktif',
            ]);
        }
    }
}
