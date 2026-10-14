#!/bin/sh
# ============================================================
# Hayabusa Lintas - Script start untuk Railway (Nixpacks)
# Dijalankan tiap kali container start.
# ============================================================
set -e

# 1. Pastikan ada file .env (ambil dari contoh kalau belum ada).
if [ ! -f .env ]; then
  cp .env.example .env
fi

# 2. Generate APP_KEY kalau masih kosong (wajib untuk Laravel).
if ! grep -q "^APP_KEY=base64:" .env; then
  php artisan key:generate --force
fi

# 3. Pastikan database SQLite ada.
mkdir -p database
if [ ! -f database/database.sqlite ]; then
  touch database/database.sqlite
fi

# 4. Bersihkan & cache ulang config (pakai env vars dari Railway).
php artisan config:clear
php artisan cache:clear || true

# 5. Jalankan migrasi + seed (aman diulang; seeder cek data dulu).
php artisan migrate --force
php artisan db:seed --force || true

# 6. Link storage biar gambar/upload bisa diakses publik.
php artisan storage:link || true

# 7. Jalankan server di port yang diberikan Railway ($PORT).
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
