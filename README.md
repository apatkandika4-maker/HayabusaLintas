# Travel Antar-Kota Kalimantan Barat 🚌

Web booking travel Pontianak ↔ Singkawang, Sambas, Mempawah, Ketapang, Sintang, dll.
Laravel 13 + Blade + Bootstrap 5 (CDN) + SQLite (siap MySQL).

## Jalanin (2 langkah)

```bash
cd "travel-kalbar"
php artisan serve --host=127.0.0.1 --port=8765
```

Buka http://127.0.0.1:8765

## Akun demo

| Role  | Email                  | Password |
|-------|------------------------|----------|
| Admin | admin@travelkalbar.id  | admin123 |
| User  | user@travelkalbar.id   | user123  |

## Alur

Cari jadwal → pilih kursi → isi data → bayar (upload bukti) → admin verifikasi → cetak tiket.

- Cek tiket: `/riwayat` (kode + HP) atau `/tiket/KB-XXXX`
- Admin: `/admin` (dashboard omzet, armada, jadwal, verifikasi booking)

## Pindah ke MySQL (XAMPP)

1. Buat DB `travel_kalbar` di phpMyAdmin
2. Edit `.env`: `DB_CONNECTION=mysql`, `DB_DATABASE=travel_kalbar`, `DB_USERNAME=root`, `DB_PASSWORD=`
3. `php artisan migrate:fresh --seed`

## Struktur

- `database/migrations/` + `database/seeders/KalbarSeeds.php` — 10 kota, 12 rute, 8 armada, 30 jadwal
- `app/Models/` — City, Route, Fleet, Schedule, Booking
- `app/Http/Controllers/` — Home, Search, Schedule, Booking, Payment, Auth
- `app/Http/Controllers/Admin/` — Dashboard, Fleet, Schedule, Route, Booking, City
- `resources/views/` — home, search, schedule-detail, booking-success, tiket, riwayat, auth, admin
