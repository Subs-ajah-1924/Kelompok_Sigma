# TasKuy

Aplikasi web untuk mahasiswa mengelola tugas, mata kuliah, dan agenda kalender.
Proyek Kelompok Sigma, sebelumnya bernama Sigma Task Manager.

Fokusnya: isolasi data antar pengguna, tampilan responsif, dan UI yang ringan.
Dibangun dengan Laravel dan database relasional.

> **Status saat ini:** fitur inti sudah diimplementasikan (tugas, mata kuliah,
> kalender + agenda, Kanban tampilan, rekomendasi, notifikasi, statistik,
> tema, profil). Sebagian masih parsial: drag-and-drop Kanban, fitur kelompok
> (beta), restore soft delete, dan upload avatar. Lihat tabel status di bawah.

## Tech Stack

| Lapisan | Teknologi |
| --- | --- |
| Framework | Laravel 12 |
| Bahasa | PHP ^8.2 |
| Autentikasi | Laravel Breeze 2.3 (stack Blade) |
| Tampilan | Blade + Tailwind CSS + Alpine.js |
| Build tool | Vite 8 |
| Kanban | SortableJS (tersedia; drag-and-drop masih parsial) |
| Database (dev) | SQLite |
| Testing & format | PHPUnit 11 + Laravel Pint |

Versi di atas mengikuti `composer.json` dan `package.json`, bukan perkiraan.

## Kebutuhan

- PHP 8.2+
- Composer 2
- Node.js 20+
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
2. Menyalin `.env.example` ke `.env` jika belum ada
3. Generate application key
4. Membuat `database/database.sqlite` jika belum ada
5. Menjalankan migration
6. Install dependency npm dan build asset

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

Default proyek memakai **SQLite** agar setiap contributor bisa menjalankan
aplikasi tanpa kredensial database bersama.

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
untuk pengembangan, karena kredensial dan data bisa bentrok. Yang dibagikan
lewat GitHub adalah struktur database (migration) dan data awal (seeder).

## Status Implementasi

| Bagian | Status |
| --- | --- |
| Setup Laravel + Breeze | Selesai |
| Autentikasi (login/register/profil) | Selesai |
| Model & migration `users`, `courses`, `tasks`, `agendas`, `groups` | Selesai |
| CRUD tugas, mata kuliah, agenda kalender | Selesai |
| Kalender (grid + agenda) | Selesai |
| Kanban (tampilan kolom) | Selesai; drag-and-drop parsial |
| Rekomendasi prioritas tugas | Selesai |
| Notifikasi tugas baru/belum dibaca | Selesai |
| Statistik & produktivitas | Selesai |
| Tema warna (light/dark) | Selesai |
| Fitur kelompok (beta) | Parsial, lihat issue #14 |
| Upload avatar | Parsial |
| Restore soft delete | Belum diimplementasikan |
| Testing & CI | Sebagian; lihat `.github/workflows/tests.yml` |

Detail rencana awal ada di `instruksi.txt`; status terbaru dilacak lewat GitHub Issues.

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
