# Panduan Keamanan — Travel KalBar

Dokumen ini menjelaskan praktik keamanan, konfigurasi yang direkomendasikan, dan
hal-hal yang **wajib dilakukan sebelum website dipakai secara online (produksi)**.

Aplikasi ini dibangun dengan **Laravel** (PHP) dan database **SQLite**.

---

## 1. Hal WAJIB sebelum online (produksi)

Checklist ini paling penting. Jangan dilewati.

- [ ] **Ganti password admin default.** Password awal `admin123` hanya untuk uji coba.
      Ganti dengan password kuat (minimal 12 karakter, campuran huruf besar/kecil, angka, simbol).
- [ ] **Matikan mode debug.** Di file `.env`, set:
      ```
      APP_ENV=production
      APP_DEBUG=false
      ```
      Jika `APP_DEBUG=true`, pesan error bisa membocorkan detail sistem ke pengunjung.
- [ ] **Pastikan `APP_KEY` sudah terisi** (bukan kosong). Jika kosong, jalankan:
      ```
      php artisan key:generate
      ```
- [ ] **Pakai HTTPS (SSL).** Setelah domain pakai HTTPS, aktifkan di `.env`:
      ```
      SESSION_SECURE_COOKIE=true
      APP_URL=https://domainanda.com
      ```
- [ ] **Jangan upload file `.env` ke internet / GitHub.** File ini berisi kunci rahasia.
      (Sudah diabaikan otomatis lewat `.gitignore`.)
- [ ] **Cache konfigurasi untuk produksi:**
      ```
      php artisan config:cache
      php artisan route:cache
      ```

---

## 2. Akun & Kata Sandi

- Password disimpan **ter-hash** (bcrypt, 12 putaran) — tidak pernah disimpan sebagai teks biasa.
- Hanya akun dengan `role = admin` yang bisa membuka panel admin (`/admin`).
  Dijaga oleh middleware `IsAdmin`.
- **Saran:**
  - Ganti password admin secara berkala.
  - Jangan membagikan satu akun admin ke banyak orang. Buat akun admin terpisah bila perlu.
  - Hapus/нonaktifkan akun demo (`user@travelkalbar.id`) sebelum online.

### Cara ganti password admin

Lewat Tinker:
```
php artisan tinker
>>> $u = App\Models\User::where('email','admin@travelkalbar.id')->first();
>>> $u->password = 'PasswordBaruYangKuat';   // otomatis di-hash oleh model
>>> $u->save();
```

---

## 3. Perlindungan yang Sudah Aktif

Fitur keamanan bawaan yang sudah berjalan di aplikasi ini:

- **CSRF Protection** — semua form (login, booking, admin) memakai token `@csrf`,
  mencegah pengiriman form palsu dari situs lain.
- **Proteksi XSS** — Blade (`{{ }}`) otomatis meng-escape output, mencegah injeksi script.
- **Proteksi SQL Injection** — memakai Eloquent/Query Builder (query terparameter),
  bukan query mentah dari input pengguna.
- **Validasi input** — form divalidasi lewat Form Request / `validate()`
  (contoh: `StoreBookingRequest`, validasi rute, jadwal, armada).
- **Session aman** — cookie session `HttpOnly` (tak bisa dibaca JavaScript) dan `SameSite=lax`.
- **Kontrol akses admin** — middleware `IsAdmin` menolak non-admin dengan error 403.
- **Hash password** — bcrypt via `BCRYPT_ROUNDS=12`.

---

## 4. Konfigurasi `.env` yang Direkomendasikan (Produksi)

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domainanda.com

SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax

LOG_LEVEL=error
```

> Catatan: `SESSION_SECURE_COOKIE=true` hanya dipakai jika situs sudah HTTPS.
> Kalau belum HTTPS, cookie tidak akan terkirim dan login bisa gagal.

---

## 5. Keamanan Server & File

- **Document root** harus mengarah ke folder `public/`, bukan ke root project.
  Ini mencegah pengunjung mengakses `.env`, `database/`, atau kode sumber langsung.
- Pastikan file berikut **tidak bisa diakses dari browser**:
  - `.env`
  - `database/database.sqlite`
  - folder `storage/` dan `vendor/`
- Beri izin tulis hanya pada folder yang perlu: `storage/` dan `bootstrap/cache/`.
- **Backup rutin** file `database/database.sqlite` (karena semua data ada di sana).

---

## 6. Data Pengguna & Privasi

- Data penumpang (nama, nomor HP) hanya dipakai untuk keperluan pemesanan.
- Jangan menampilkan nomor HP penumpang di halaman publik.
- Bukti pembayaran yang diunggah disimpan di storage privat — jangan diekspos ke publik.
- Hapus data booking lama secara berkala bila sudah tidak diperlukan.

---

## 7. Integrasi WhatsApp

- Pemesanan diarahkan ke WhatsApp admin melalui tautan `wa.me`.
- Nomor WA admin diatur di `config/travel.php` (`TRAVEL_WA`). Pastikan nomor benar.
- Tautan WA hanya berisi teks pesan (rute, tanggal) — tidak mengirim data sensitif.

---

## 8. Pembaruan (Update)

- Perbarui dependensi secara berkala untuk menutup celah keamanan:
  ```
  composer update
  ```
- Pantau rilis keamanan Laravel di: https://laravel.com/docs/releases
- Jangan menambahkan paket (library) dari sumber yang tidak dikenal.

---

## 9. Melaporkan Masalah Keamanan

Jika menemukan celah atau masalah keamanan pada website ini:

1. **Jangan** sebarkan ke publik.
2. Hubungi admin lewat WhatsApp resmi yang tertera di website.
3. Sertakan: apa yang ditemukan, langkah menirukannya, dan dampaknya.

Laporan akan ditindaklanjuti sesegera mungkin.

---

_Dokumen ini dibuat untuk project Travel KalBar. Sesuaikan isinya bila ada perubahan
pada infrastruktur, domain, atau kontak resmi._
