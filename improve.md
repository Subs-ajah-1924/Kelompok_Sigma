# Saran Improvement TasKuy

> Repo: `Subs-ajah-1924/Kelompok_Sigma` — branch `main`
> App: **TasKuy** (sebelumnya Sigma Task Manager), proyek Kelompok Sigma
> Stack yang benar: PHP `^8.2`, Laravel `12`, Breeze `2.3`, PHPUnit `11`, Vite `8`, Tailwind CSS (build yang terdeteksi: v4.0.7), SQLite (dev)
> Source of truth: `instruksi.txt` v1.0, 26 Oktober 2026 (2.109 baris)
> Status saat file ini dibuat: 17 November 2026

Dokumen ini adalah daftar improvement yang sudah dicek ke isi repo, bukan tebakan.
Pakai dokumen ini sebagai brief untuk coding agent. Kerjakan **satu fase per tugas**,
jangan lompat fase, jangan tambah fitur di luar scope.

---

## 1. Kondisi saat ini (sudah diverifikasi)

### Yang sudah selesai

- Setup Laravel + Breeze, route sudah di-scaffold di `routes/web.php`:
  `/dashboard`, resource `tasks` + `PATCH tasks/{task}/status`, resource
  `courses` (tanpa `show`), `/kanban`, `/calendar`, `agendas` (hanya
  store/update/destroy), `/notifications`, route profil.
- README sudah rapi dan sudah disamakan ke `composer.json` (TasKuy, Laravel 12,
  PHP 8.2). Commit `d299a1a`.
- `.env.example` sudah `APP_NAME="TasKuy"`. Commit `e48c2dc`.
- Ada test routing dasar: `tests/Feature/ControllerRoutingTest.php`.

### Yang belum ada / masih placeholder

- `database/migrations/` hanya migrasi bawaan Laravel (users, cache, jobs).
  **Belum ada migrasi `courses`, `tasks`, `agendas`.**
- `app/Models/` hanya ada `User.php`.
- `TaskController`, `CourseController`, `KanbanController`, dan
  `CalendarController` masih placeholder: halaman list/form hanya menampilkan
  view placeholder, `store()` masih `abort(501)`.
- `DashboardController` hanya mengembalikan view.
- `resources/views/dashboard.blade.php` masih bawaan Breeze
  (“You're logged in!”), belum ada statistik / deadline / rekomendasi.
- `resources/views/welcome.blade.php` sudah memakai `config('app.name')`,
  jadi nama TasKuy tampil otomatis begitu `.env` lokal diisi `APP_NAME="TasKuy"`.
- Test hanya `tests/Unit/ExampleTest.php`, test Auth bawaan Breeze, dan
  `ControllerRoutingTest.php`. Belum ada test domain (CRUD, isolasi data,
  rekomendasi, kalender).
- Seeder demo dari `instruksi.txt` (demo1/demo2 dengan courses/tasks/agendas)
  belum diimplementasikan. `DatabaseSeeder.php` hanya membuat satu user
  `Test User / test@example.com`.

### Penilaian singkat

- Struktur database di `instruksi.txt`: **9/10** — bersih, 4 tabel, relasi jelas,
  tidak over-engineering. Lihat Bagian 3 untuk kekurangan kecilnya.
- Blueprint `instruksi.txt`: **9/10**.
- Implementasi saat ini: masih tahap setup/scaffolding, fitur inti belum dibangun.

---

## 2. Prinsip kerja (wajib diikuti agent)

1. `instruksi.txt` adalah source of truth. Kalau ada konflik dengan dokumen lain,
   ikuti `instruksi.txt`, lalu laporkan konfliknya.
2. Selesaikan fitur inti dulu, baru polish UI. Jangan lompat ke UI sebelum
   database dan auth stabil.
3. Satu fase = satu branch + satu commit yang fokus. Jangan campur banyak fase
   dalam satu commit.
4. Jangan ubah migrasi yang sudah di-commit. Perubahan skema setelahnya
   **wajib** lewat migrasi baru.
5. Semua data terikat ke user yang login. Jangan pernah terima `user_id` dari
   form/request. Semua perubahan data lewat Controller + Form Request + Policy.
6. Prioritas dihitung saat dibutuhkan, jangan disimpan di database.
7. Deadline tugas tidak pernah disalin ke tabel `agendas`. Agenda hanya untuk
   agenda manual.
8. Setiap fase selesai hanya jika lolos Definition of Done di Bagian 6.

---

## 3. Database — sudah bagus, ini perbaikan kecilnya

Skema yang direncanakan di `instruksi.txt` (jangan diubah strukturnya):

| Tabel | Kolom kunci | Relasi |
| --- | --- | --- |
| `users` | id, name, email unique, password, timestamps | 1:N courses, tasks, agendas |
| `courses` | id, `user_id` FK cascade, name (100), code (20, nullable), color (7, nullable), timestamps; unique `(user_id, name)` | 1:N tasks |
| `tasks` | id, `user_id` FK cascade, `course_id` FK nullable nullOnDelete, title (150), description (text, nullable), deadline (datetime, wajib), difficulty `mudah\|sedang\|sulit`, status `todo\|in_progress\|done` (default `todo`), timestamps | milik user + course |
| `agendas` | id, `user_id` FK cascade, date, start_time (nullable), title (150), description (nullable), timestamps; **tanpa `task_id`** | milik user |

Yang sudah benar: isolasi per user, `course_id` nullable + `nullOnDelete`
(hapus matkul tidak menghapus tugasnya), index sesuai pola query
(`user_id+deadline`, `user_id+status`, `user_id+difficulty`, `user_id+date`),
prioritas tidak disimpan, agenda manual terpisah total dari deadline.

Perbaikan kecil (opsional, lewat migrasi baru, bukan ubah migrasi lama):

- [x] **P0 — Tambah index `(user_id, course_id)` di `tasks`.** (selesai)
  Membantu `withCount('tasks')` per course dan filter tugas per matkul.
- [x] **P1 — Tambah `completed_at` (nullable) di `tasks`.** (selesai)
  Opsional di `instruksi.txt`. Berguna untuk statistik “selesai minggu ini”.
  Isi saat status berubah ke `done`, kosongkan saat kembali ke `todo` /
  `in_progress`. Jangan pakai `updated_at` sebagai pengganti, karena
  `updated_at` berubah juga saat edit judul/deskripsi.
- [x] **P1 — Tambah `end_time` (nullable) di `agendas`.** (selesai)
  Sekarang hanya ada `start_time`, jadi durasi agenda tidak bisa dihitung
  dan tidak bisa dicek bentrok.
- [ ] **P2 — Pertimbangkan soft delete untuk `tasks` dan `courses`.**
  Sekarang hapus berarti hapus permanen, tidak ada tempat sampah.
  Kalau dipakai, pastikan query dashboard / statistik / rekomendasi
  mengecualikan data yang di-soft-delete.
- [ ] **P2 — Tambah check constraint di database untuk `status` dan
  `difficulty`.** Sekarang validasi hanya di aplikasi (enum + Form Request).
  Constraint DB adalah pengaman kedua kalau ada yang menulis langsung ke DB.

---

## 4. Prioritas improvement

### P0 — Wajib, fondasi (Fase 1–5)

- [x] **Fase 1 — Database + Model (1 hari)** (selesai)
  - Satu migrasi per tabel: `courses`, `tasks`, `agendas`, lengkap dengan FK,
    index, dan unique `(user_id, name)` untuk courses.
  - Model `Course`, `Task`, `Agenda` + enum `TaskStatus` dan `Difficulty`
    + casts + relasi.
  - Factory + `DemoSeeder` sesuai `instruksi.txt` (2 user demo dengan
    courses/tasks/agendas).
  - Verifikasi: `php artisan migrate:fresh --seed` berhasil, relasi dan
    unique constraint terbukti di test.

- [x] **Fase 2 — Autentikasi + Layout (1 hari)** (selesai)
  - Rapikan layout Breeze: sidebar/topbar, flash message, halaman 403/404
    sederhana.
  - Middleware `auth` di semua route aplikasi, navbar + badge notifikasi
    (placeholder dulu).
  - Selesai jika: guest tidak bisa akses halaman aplikasi, user login melihat
    dashboard (masih boleh kosong di fase ini).

- [x] **Fase 3 — Courses (1 hari)** (selesai)
  - CRUD course + Form Request + Policy, validasi unik nama per user,
    `withCount('tasks')`.
  - Hapus course meminta konfirmasi dan menjelaskan tugas terkait tidak
    terhapus, hanya kehilangan label matkul.

- [x] **Fase 4 — Tasks CRUD (1,5–2 hari)** (selesai)
  - CRUD task + Form Request + Policy. Select course hanya milik user yang login.
  - Detail, edit, hapus dengan konfirmasi. Badge status / difficulty / deadline.
  - Selesai jika: semua field tersimpan, validasi jalan, isolasi data terbukti.

- [ ] **Fase 5 — Dashboard + Filter + Search (1,5 hari)**
  - Dashboard isi: sapaan “Halo, {nama}” + tanggal, kartu statistik
    (total aktif, To Do, In Progress, Done, Terlambat), panel
    “Deadline Terdekat” (7 hari), panel “Tugas Terbaru”, tombol cepat
    (Tambah Tugas, Buka Kanban, Buka Kalender), empty state dengan CTA.
  - Search + filter kombinasi (status, course, difficulty, deadline) +
    reset + pagination 12. Filter tersimpan di URL. Query lewat scope.
  - **Catatan:** panel “Rekomendasi Fokus” bukan di fase ini, melainkan Fase 7.
  - Semua angka dihitung dari database saat request, bukan di-cache di session.
  - Selesai jika: filter bisa digabung, hasil hanya milik user yang login.

### P1 — Penting, fitur inti lanjutan (Fase 6–9)

- [ ] **Fase 6 — Kanban (1–1,5 hari)**
  - 3 kolom: To Do, In Progress, Done. Drag & drop pakai SortableJS
    (sudah ada di `package.json` sebagai dependency) + `PATCH tasks/{task}/status`.
  - Optimistic update + rollback kalau gagal. Fallback select status untuk
    pengguna tanpa JS.
  - Status Kanban harus sinkron dengan dashboard / daftar / notifikasi,
    karena semuanya membaca kolom `status` yang sama.

- [ ] **Fase 7 — Rekomendasi (1 hari)**
  - Buat `TaskRecommendationService` terpisah dari controller.
  - Logika deterministik, tanpa AI/ML: dari sisa waktu ke deadline +
    difficulty. Label `Prioritas Tinggi / Sedang / Rendah` + alasan
    yang bisa dibaca manusia.
  - Tampilkan maksimal 5 di dashboard, tulis jelas
    “Rekomendasi, bukan urutan otomatis.” Tugas `done` tidak masuk rekomendasi.
  - Unit test untuk setiap kombinasi deadline + difficulty.

- [ ] **Fase 8 — Notifikasi (0,5–1 hari)**
  - Dihitung saat request, **jangan buat tabel notifikasi baru**.
  - Aturan dari `instruksi.txt`: deadline < 24 jam, deadline hari ini,
    terlambat, dan tugas sulit yang deadline-nya < 3 hari.
  - Tugas `done` tidak masuk notifikasi. Badge jumlah di navbar harus
    konsisten dengan isi halaman notifikasi.

- [ ] **Fase 9 — Kalender + Agenda (1,5–2 hari)**
  - Kalender bulanan: gabungkan agenda manual (`agendas`) dan deadline
    tugas, tapi tampilkan dengan penanda berbeda. Jangan salin deadline
    ke `agendas`.
  - CRUD agenda manual (tambah/ubah/hapus) + tampilan agenda hari ini.
  - Filter per course. Navigasi bulan sebelumnya/berikutnya.

### P2 — Kualitas dan konsistensi

- [ ] **Fase 10 — Polish UI/UX (1–1,5 hari)**
  - Komponen Blade yang dipakai ulang: `x-stat-card`, `x-badge`,
    `x-task-card`, `x-empty-state`, `x-modal`, `x-filter-bar`.
  - Design token di `instruksi.txt`: background `slate-50`, kartu putih,
    aksen `indigo-600 (#4F46E5)`.
  - Sidebar desktop 240px, responsif 375px / 768px / 1280px.
    Pastikan tombol dan teks tidak terpotong di layar kecil.
  - Empty state, loading state, dan pesan error yang jelas di semua halaman.

- [ ] **Fase 11 — Testing menyeluruh + Bugfix (1–1,5 hari)**
  - Lihat Bagian 5 — ini bagian yang paling sering terlewat.

- [ ] **Konsistensi dokumen dan versi (kerjakan sebelum agent mulai Fase 1)**
  - `instruksi.txt` masih menyebut Laravel 13, PHP 8.4, Breeze 2.4,
    PHPUnit 12, dan nama Sigma Task Manager. Samakan ke Laravel 12,
    PHP 8.2, Breeze 2.3, PHPUnit 11, dan TasKuy, sesuai `composer.json`
    dan README yang sudah diperbarui.
  - `package.json` tidak konsisten: `tailwindcss` masih `^3.1.0`, tapi
    `@tailwindcss/vite` sudah `^4.0.0`, dan CSS yang ter-build terdeteksi
    sebagai Tailwind v4.0.7. Tentukan satu versi Tailwind yang dipakai,
    lalu samakan dependensinya.
  - Laporan progres Capstone (web + PDF 13 halaman) masih memakai nama
    lama dan versi lama. Perbarui kalau laporan itu akan dipakai lagi,
    dan isi placeholder `[perlu diisi]` (nama + NIM anggota, kelas, dosen,
    semester/tahun ajaran, fakultas, universitas, logo) sebelum submit.
  - Mockup di BAB 3 laporan masih deskripsi teks. Ganti dengan screenshot
    aplikasi asli untuk laporan final.

- [ ] **Performa dan keamanan**
  - Eager loading `with('course')` di semua daftar tugas. Jangan biarkan N+1.
  - Semua query list dibatasi ke user yang login. Uji dengan dua user.
  - Jangan kirim data user lain lewat API/route, jangan tampilkan ID user
    lain di URL yang bisa ditebak tanpa Policy.
  - Validasi server untuk semua input, termasuk `color` course (format hex
    7 karakter) dan `deadline` (format tanggal yang valid).

### P3 — Jangan dikerjakan dulu (out of scope)

Sesuai `instruksi.txt`, ini **dilarang** dibangun di proyek ini:

- Role admin
- AI/ML / chatbot
- Realtime / WebSocket
- Chat antar user
- Lampiran file (file attachment)
- Integrasi email / WhatsApp / push notification
- Aplikasi mobile native
- Pembayaran
- Fitur sosial

Kalau agent mengusulkan salah satu di atas, tolak dan kembali ke scope.

---

## 5. Testing — jebakan yang wajib diketahui agent

> **Perhatian:** `tests/Feature/ControllerRoutingTest.php` saat ini
> meng-assert bahwa semua write action harus mengembalikan **501**
> (`test_write_actions_are_not_implemented_yet`). Begitu CRUD diimplementasikan,
> test itu **wajib di-update** — kalau tidak, test akan merah padahal
> fiturnya sudah benar.

Test yang harus ada setelah Fase 4:

- [ ] CRUD course dan task: happy path + validasi gagal (judul kosong,
  deadline kosong, difficulty tidak valid, nama course duplikat per user).
- [ ] Isolasi data: user B tidak bisa melihat, mengedit, atau menghapus
  course/task/agenda user A (harus 403/404, bukan bocor data).
- [ ] Status berubah lewat `PATCH tasks/{task}/status` dan tercermin
  di dashboard, daftar, Kanban, dan notifikasi.
- [ ] Rekomendasi: unit test untuk kombinasi deadline + difficulty
  (misalnya deadline < 24 jam + sulit = Tinggi).
- [ ] Kalender: deadline tugas dan agenda manual muncul di tanggal yang benar,
  dan tidak ada duplikat.
- [ ] Notifikasi: tugas `done` tidak muncul; tugas terlambat muncul;
  batas 24 jam dan 3 hari diuji di sekitar batasnya.
- [ ] Guest diarahkan ke login di semua halaman aplikasi (sudah ada testnya,
  jangan dihapus).

Perintah verifikasi tiap fase:

```bash
php artisan test
php artisan migrate:fresh --seed
./vendor/bin/pint --test
npm run build
```

---

## 6. Definition of Done (berlaku untuk setiap fase)

Fase dianggap selesai hanya jika semua ini terpenuhi:

- [ ] Fitur jalan di browser lokal (bukan hanya “kode sudah ditulis”).
- [ ] `php artisan test` hijau, termasuk test isolasi data.
- [ ] `php artisan migrate:fresh --seed` berhasil dari nol.
- [ ] Tidak ada `abort(501)` yang tersisa untuk fitur fase tersebut.
- [ ] Tidak ada data user lain yang bocor ke user yang login.
- [ ] Pint dan `npm run build` lolos.
- [ ] Perubahan skema (kalau ada) lewat migrasi baru, bukan edit migrasi lama.
- [ ] Commit terpisah per fase, dengan pesan yang jelas
  (contoh: `feat: tambah CRUD tasks + policy`).

---

## 7. Cara memakai dokumen ini dengan coding agent

Tempel prompt ini ke agent, lalu sebutkan satu fase saja:

> Kerjakan **Fase [nomor] — [nama fase]** dari `instruksi.txt` untuk repo
> Kelompok_Sigma (TasKuy) di branch `main`.
>
> Source of truth: `instruksi.txt` dan dokumen `saran-improvement-tasKuy.md` ini.
> Stack: PHP 8.2, Laravel 12, Breeze 2.3, PHPUnit 11, Vite 8, SQLite (dev).
>
> Aturan:
> - Kerjakan hanya fase itu, jangan lompat ke fase lain.
> - Jangan ubah migrasi yang sudah di-commit; perubahan skema lewat migrasi baru.
> - Semua perubahan data lewat Controller + Form Request + Policy, dan semua
>   data terikat ke user yang login. Jangan terima `user_id` dari form.
> - Update test yang terdampak, terutama `ControllerRoutingTest.php` yang
>   saat ini meng-assert 501 — ganti assertion itu dengan test CRUD yang benar.
> - Jangan tambah fitur di luar scope (admin, AI, realtime, chat, lampiran,
>   email/WA/push, mobile native, pembayaran, sosial).
> - Setelah selesai, jalankan `php artisan test`, `php artisan migrate:fresh --seed`,
>   `./vendor/bin/pint --test`, dan `npm run build`, lalu laporkan hasilnya
>   dan apa yang masih belum selesai.

Urutan yang disarankan: Fase 1 → 2 → 3 → 4 → 5 → 6 → 7 → 8 → 9 → 10 → 11 → 12.
Estimasi total di `instruksi.txt`: 12–15 hari kerja efektif.

---

## 8. Ringkasan satu halaman

| Prioritas | Isi | Kapan |
| --- | --- | --- |
| P0 | Migrasi + model, layout + auth, CRUD course, CRUD task, dashboard + filter/search | Fase 1–5 |
| P1 | Kanban, rekomendasi deterministik, notifikasi (tanpa tabel), kalender + agenda | Fase 6–9 |
| P2 | Komponen UI + polish, testing menyeluruh, samakan `instruksi.txt` + `package.json` ke Laravel 12 / PHP 8.2 / TasKuy | Fase 10–11 + sebelum Fase 1 |
| P3 | Jangan dibuat: admin, AI, realtime, chat, lampiran, email/WA/push, mobile, pembayaran, sosial | — |

Kalau hanya ada waktu untuk satu hal: **selesaikan Fase 1–4 dulu.**
Tanpa migrasi, model, dan CRUD, semua fitur lain (dashboard, Kanban,
rekomendasi, kalender) tidak punya data untuk ditampilkan.
