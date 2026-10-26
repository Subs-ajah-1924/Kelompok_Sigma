# Sigma Task Manager

Aplikasi web untuk mengelola tugas, mata kuliah, dan agenda kalender mahasiswa.
Proyek ini menggunakan Laravel dan database relasional, dengan fokus pada
isolasi data antar pengguna, tampilan responsif, dan UI yang ringan.

## Status Saat Ini

Tahap setup awal selesai:

- Laravel 13.38
- PHP 8.4
- Laravel Breeze 2.4 (stack Blade) untuk autentikasi
- Blade + Tailwind CSS + Alpine.js
- Vite 8
- SortableJS (dependency Kanban, belum diimplementasikan)
- SQLite sebagai database pengembangan default
- PHPUnit 12 + Laravel Pint

Fitur aplikasi (tugas, kanban, rekomendasi, notifikasi, kalender) belum
diimplementasikan. Rencana lengkap ada di `instruksi.txt`.

## Kebutuhan

- PHP 8.3+ (disarankan 8.4)
- Composer 2
- Node.js 20+ (disarankan 24)
- npm

## Setup Cepat

Dari root proyek:

```bash
composer setup
php artisan db:seed
npm run dev
```

Di terminal lain:

```bash
php artisan serve
```

Buka http://localhost:8000.

`composer setup` melakukan:

1. `composer install`
2. menyalin `.env.example` ke `.env` jika belum ada
3. generate application key
4. membuat `database/database.sqlite` jika belum ada
5. menjalankan migration
6. install dependency npm dan build asset

Akun demo setelah `php artisan db:seed`:

```text
Email    : test@example.com
Password : password
```

## Setup Manual

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan db:seed
npm install
npm run build
php artisan serve
```

Untuk development frontend, gunakan `npm run dev` di terminal terpisah.

## Database

Default proyek memakai **SQLite** agar setiap contributor GitHub bisa
menjalankan aplikasi tanpa kredensial server database bersama.

- Database lokal ada di `database/database.sqlite`.
- File tersebut **tidak di-commit** ke Git.
- Setiap contributor membuat database sendiri lewat migration.
- Jangan mengirim file SQLite lewat Git atau chat; gunakan migration + seeder.

Untuk memakai MySQL/MariaDB, edit `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sigma_task_manager
DB_USERNAME=root
DB_PASSWORD=
```

Buat database terlebih dahulu:

```sql
CREATE DATABASE sigma_task_manager
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
```

Lalu jalankan:

```bash
php artisan migrate
```

Catatan: database server bersama untuk semua contributor tidak disarankan
untuk pengembangan karena kredensial dan data bisa bentrok. Yang dibagikan
lewat GitHub adalah struktur database (migration) dan data awal (seeder).

## Testing

```bash
php artisan test
vendor/bin/pint
npm run build
```

## Struktur Penting

```text
app/Http/Controllers/Auth/   Autentikasi dari Breeze
database/migrations/         Struktur tabel
database/seeders/            Data awal
resources/views/             Halaman Blade
resources/css/app.css        Entry CSS
resources/js/app.js          Entry JavaScript
routes/web.php               Route utama
routes/auth.php              Route autentikasi
instruksi.txt                Spesifikasi + rencana implementasi lengkap
```

## Git

Folder ini adalah root repository Git. Jangan membuat repository Git baru di
dalam subfolder. Sebelum commit, periksa `git status`; jangan commit `.env`,
`database/database.sqlite`, `vendor`, `node_modules`, atau `public/build`.
