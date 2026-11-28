<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'TasKuy') }} — Student Workspace</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=block" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .landing-grid { background-image: linear-gradient(rgba(79,70,229,.06) 1px, transparent 1px), linear-gradient(90deg, rgba(79,70,229,.06) 1px, transparent 1px); background-size: 32px 32px; }
        .dashboard-float { animation: dashboardFloat 7s ease-in-out infinite; }
        @keyframes dashboardFloat { 0%,100% { transform: translateY(0) rotate(1deg); } 50% { transform: translateY(-10px) rotate(0deg); } }
        @media (prefers-reduced-motion: reduce) { .dashboard-float { animation: none; } }
    </style>
</head>
<body class="bg-[#fcfbff] font-sans text-[#111936] antialiased selection:bg-indigo-600 selection:text-white">
    <header class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/95 backdrop-blur">
        <nav class="mx-auto flex h-[72px] max-w-7xl items-center justify-between px-6 lg:px-10" aria-label="Navigasi utama">
            <a href="{{ url('/') }}" class="flex items-center gap-3" aria-label="TasKuy beranda">
                <img src="{{ asset('images/logo-small.webp') }}" alt="TasKuy" class="h-10 w-10 rounded-xl object-cover shadow-sm">
                <span class="text-xl font-extrabold tracking-tight text-indigo-700">TasKuy</span>
            </a>
            <div class="hidden items-center gap-9 text-sm font-semibold text-slate-500 md:flex">
                <a href="#beranda" class="text-indigo-600 transition hover:text-indigo-700">Beranda</a>
                <a href="#fitur" class="transition hover:text-indigo-600">Fitur</a>
                <a href="#tentang" class="transition hover:text-indigo-600">Tentang</a>
            </div>
            <div class="flex items-center gap-2 sm:gap-4">
                <a href="{{ route('login') }}" class="hidden px-3 py-2 text-sm font-semibold text-slate-700 transition hover:text-indigo-600 sm:block">Masuk</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-indigo-600/20 transition hover:-translate-y-0.5 hover:bg-indigo-700">Daftar Akun</a>
                @endif
            </div>
        </nav>
    </header>

    <main id="beranda">
        <section class="relative overflow-hidden border-b border-indigo-100 bg-gradient-to-br from-indigo-50/80 via-white to-violet-50/70">
            <div class="absolute inset-0 landing-grid opacity-60"></div>
            <div class="absolute -right-40 top-10 h-[520px] w-[520px] rounded-full bg-violet-200/40 blur-3xl"></div>
            <div class="relative mx-auto grid max-w-7xl items-center gap-14 px-6 py-20 lg:grid-cols-[.9fr_1.1fr] lg:px-10 lg:py-28">
                <div class="max-w-xl">
                    <div class="mb-7 inline-flex items-center gap-2 rounded-full border border-indigo-200 bg-white px-3 py-1.5 text-xs font-bold text-indigo-700 shadow-sm">
                        <span class="material-symbols-outlined text-[16px]">auto_awesome</span>Satu workspace untuk perjalanan kuliahmu
                    </div>
                    <h1 class="text-4xl font-extrabold leading-[1.08] tracking-[-.04em] text-[#10183d] sm:text-5xl lg:text-[4.35rem]">Urus Kuliah Lebih Teratur, <span class="text-indigo-600">Fokus pada Hal yang Penting.</span></h1>
                    <p class="mt-7 max-w-lg text-lg leading-8 text-slate-600">TasKuy membantu kamu mengelola tugas, mencatat materi, mengatur jadwal, dan memantau aktivitas kuliah dalam satu workspace.</p>
                    <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('register') }}" class="inline-flex h-12 items-center justify-center gap-2 rounded-xl bg-indigo-600 px-6 font-bold text-white shadow-xl shadow-indigo-600/20 transition hover:-translate-y-0.5 hover:bg-indigo-700"><span class="material-symbols-outlined">play_arrow</span>Mulai Sekarang</a>
                        <a href="#fitur" class="inline-flex h-12 items-center justify-center gap-2 rounded-xl border border-indigo-200 bg-white px-6 font-bold text-indigo-700 transition hover:border-indigo-400 hover:bg-indigo-50">Jelajahi Fitur <span class="material-symbols-outlined text-[18px]">arrow_forward</span></a>
                    </div>
                    <div class="mt-9 flex items-center gap-3 text-sm text-slate-500"><div class="flex -space-x-2"><span class="h-8 w-8 rounded-full border-2 border-white bg-indigo-300"></span><span class="h-8 w-8 rounded-full border-2 border-white bg-amber-300"></span><span class="h-8 w-8 rounded-full border-2 border-white bg-emerald-300"></span><span class="h-8 w-8 rounded-full border-2 border-white bg-rose-300"></span></div><span>Dipercaya oleh mahasiswa<br>yang ingin lebih teratur</span></div>
                </div>
                <div class="relative dashboard-float lg:pl-4">
                    <div class="absolute -inset-5 rounded-[2rem] bg-indigo-300/20 blur-2xl"></div>
                    <div class="relative overflow-hidden rounded-2xl border border-indigo-200 bg-white shadow-2xl shadow-indigo-900/15">
                        <div class="flex h-11 items-center gap-2 border-b border-slate-100 px-4"><span class="h-2.5 w-2.5 rounded-full bg-rose-300"></span><span class="h-2.5 w-2.5 rounded-full bg-amber-300"></span><span class="h-2.5 w-2.5 rounded-full bg-emerald-300"></span><div class="ml-auto h-6 w-40 rounded-full bg-slate-100"></div></div>
                        <div class="grid min-h-[350px] grid-cols-[112px_1fr]">
                            <aside class="bg-[#151d4a] p-3 text-white"><div class="mb-8 flex items-center gap-2 text-xs font-bold"><img src="{{ asset('images/logo-small.webp') }}" class="h-6 w-6 rounded-md" alt="">TasKuy</div><div class="space-y-2 text-[10px] text-indigo-200"><div class="rounded-lg bg-indigo-600 px-2 py-2 text-white">⌂ Beranda</div><div class="px-2 py-2">✓ Tugas</div><div class="px-2 py-2">▣ Kalender</div><div class="px-2 py-2">▤ Catatan</div><div class="px-2 py-2">▥ Statistik</div></div></aside>
                            <div class="bg-slate-50 p-5"><div class="flex items-start justify-between"><div><p class="text-[10px] font-semibold text-indigo-600">SELAMAT DATANG KEMBALI</p><h2 class="mt-1 text-xl font-extrabold text-slate-800">Halo, Mahasiswa 👋</h2><p class="text-[10px] text-slate-500">Semangat menyelesaikan target hari ini!</p></div><div class="rounded-xl bg-indigo-100 p-2 text-indigo-600">☼</div></div><div class="mt-5 grid grid-cols-3 gap-2"><div class="rounded-xl bg-white p-3 shadow-sm"><p class="text-[9px] text-slate-400">Tugas Aktif</p><strong class="text-xl text-slate-800">8</strong><span class="ml-1 text-[9px] text-emerald-500">+2</span></div><div class="rounded-xl bg-white p-3 shadow-sm"><p class="text-[9px] text-slate-400">Deadline Dekat</p><strong class="text-xl text-slate-800">2</strong></div><div class="rounded-xl bg-white p-3 shadow-sm"><p class="text-[9px] text-slate-400">Catatan</p><strong class="text-xl text-slate-800">12</strong></div></div><div class="mt-4 grid grid-cols-[1.15fr_.85fr] gap-3"><div class="rounded-xl bg-white p-3 shadow-sm"><div class="flex justify-between text-[10px] font-bold"><span>Tugas Terdekat</span><span class="text-indigo-600">Lihat Semua</span></div><div class="mt-3 space-y-2 text-[10px]"><div class="flex justify-between rounded-lg bg-rose-50 p-2"><span>Membuat Laporan UTS</span><b class="text-rose-500">Penting</b></div><div class="flex justify-between rounded-lg bg-blue-50 p-2"><span>Belajar Basis Data</span><b class="text-blue-500">Sedang</b></div><div class="flex justify-between rounded-lg bg-emerald-50 p-2"><span>Presentasi Kelompok</span><b class="text-emerald-500">Rendah</b></div></div></div><div class="rounded-xl bg-white p-3 shadow-sm"><div class="flex justify-between text-[10px] font-bold"><span>Produktivitas</span><span class="text-indigo-600">85%</span></div><div class="mt-3 h-2 rounded-full bg-indigo-100"><div class="h-2 w-[85%] rounded-full bg-indigo-600"></div></div><div class="mt-8 grid grid-cols-7 items-end gap-1"><i class="h-5 rounded-t bg-indigo-200"></i><i class="h-8 rounded-t bg-indigo-300"></i><i class="h-6 rounded-t bg-indigo-400"></i><i class="h-12 rounded-t bg-indigo-600"></i><i class="h-9 rounded-t bg-indigo-400"></i><i class="h-14 rounded-t bg-indigo-600"></i><i class="h-10 rounded-t bg-indigo-400"></i></div></div></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="fitur" class="mx-auto max-w-7xl px-6 py-24 lg:px-10">
            <div class="max-w-2xl"><p class="text-sm font-bold uppercase tracking-[.2em] text-indigo-600">Fitur unggulan</p><h2 class="mt-3 text-3xl font-extrabold tracking-tight text-[#10183d] sm:text-4xl">Semua Kebutuhan Kuliah dalam Satu Tempat</h2><p class="mt-4 text-lg leading-8 text-slate-500">Berbagai alat sederhana untuk membantu aktivitas akademikmu berjalan lebih teratur.</p></div>
            <div class="mt-14 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach ([['task_alt','Manajemen Tugas','Mengatur tugas, prioritas, deadline, dan status pengerjaan.','bg-indigo-50 text-indigo-600'],['calendar_month','Kalender Akademik','Melihat jadwal dan deadline dalam satu kalender.','bg-rose-50 text-rose-500'],['edit_note','Catatan Kuliah','Menyimpan materi, ide, dan catatan perkuliahan.','bg-amber-50 text-amber-600'],['schedule','Jadwal Mata Kuliah','Mengatur jadwal kuliah setiap minggu.','bg-emerald-50 text-emerald-600'],['bar_chart','Statistik Produktivitas','Melihat perkembangan dan aktivitas pengerjaan tugas.','bg-violet-50 text-violet-600'],['view_kanban','Kanban Board','Mengelola tugas berdasarkan tahapan pengerjaan.','bg-sky-50 text-sky-600']] as $feature)
                    <article class="group rounded-2xl border border-slate-200 bg-white p-6 transition hover:-translate-y-1 hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-900/5"><div class="flex items-start justify-between"><div class="flex h-11 w-11 items-center justify-center rounded-xl {{ $feature[3] }}"><span class="material-symbols-outlined">{{ $feature[0] }}</span></div><span class="text-slate-300 transition group-hover:text-indigo-500">↗</span></div><h3 class="mt-6 text-lg font-bold text-slate-800">{{ $feature[1] }}</h3><p class="mt-2 leading-7 text-slate-500">{{ $feature[2] }}</p></article>
                @endforeach
            </div>
        </section>

        <section id="tentang" class="border-y border-slate-100 bg-slate-50/70"><div class="mx-auto max-w-6xl px-6 py-24 lg:px-10"><div class="text-center"><p class="text-sm font-bold uppercase tracking-[.2em] text-indigo-600">Cara kerja</p><h2 class="mt-3 text-3xl font-extrabold text-[#10183d] sm:text-4xl">Mulai Lebih Terorganisir dalam 3 Langkah</h2></div><div class="relative mt-16 grid gap-10 md:grid-cols-3 md:gap-16"><div class="absolute left-[17%] right-[17%] top-7 hidden h-px bg-indigo-200 md:block"></div>@foreach ([['01','Buat akun TasKuy','Daftar dengan mudah dan gratis.'],['02','Masukkan jadwal dan tugas kuliah','Atur semua aktivitas akademikmu.'],['03','Pantau semua aktivitas','Melalui satu dashboard.']] as $step)<div class="relative text-center"><div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full border-8 border-slate-50 bg-indigo-600 text-lg font-extrabold text-white shadow-lg shadow-indigo-600/20">{{ $step[0] }}</div><h3 class="mt-6 font-bold text-slate-800">{{ $step[1] }}</h3><p class="mx-auto mt-2 max-w-xs text-sm leading-6 text-slate-500">{{ $step[2] }}</p></div>@endforeach</div></div></section>

        <section class="mx-auto max-w-7xl px-6 py-20 lg:px-10"><div class="relative overflow-hidden rounded-3xl bg-indigo-700 px-8 py-14 text-center text-white shadow-2xl shadow-indigo-900/20 sm:px-12"><div class="absolute -right-20 -top-24 h-64 w-64 rounded-full border-[36px] border-white/10"></div><div class="absolute -bottom-28 -left-16 h-64 w-64 rounded-full border-[30px] border-violet-300/10"></div><h2 class="relative text-3xl font-extrabold sm:text-4xl">Siap Membuat Aktivitas Kuliah Lebih Teratur?</h2><p class="relative mx-auto mt-4 max-w-xl text-indigo-100">Mulai atur tugas dan jadwal kuliahmu bersama TasKuy.</p><a href="{{ route('register') }}" class="relative mt-8 inline-flex items-center gap-2 rounded-xl bg-white px-6 py-3 font-bold text-indigo-700 transition hover:-translate-y-0.5 hover:bg-indigo-50">Mulai Sekarang — Gratis <span class="material-symbols-outlined text-[18px]">arrow_forward</span></a></div></section>
    </main>

    <footer class="border-t border-slate-200 bg-white"><div class="mx-auto grid max-w-7xl gap-10 px-6 py-12 md:grid-cols-[1.5fr_1fr_1fr] lg:px-10"><div><a href="{{ url('/') }}" class="flex items-center gap-3"><img src="{{ asset('images/logo-small.webp') }}" alt="TasKuy" class="h-9 w-9 rounded-lg"><span class="text-xl font-extrabold text-indigo-700">TasKuy</span></a><p class="mt-4 max-w-xs text-sm leading-6 text-slate-500">Student Workspace untuk mahasiswa agar kuliah lebih teratur dan produktif.</p><span class="mt-5 inline-block rounded-full bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-600">Made for Students ♡</span></div><div><h3 class="font-bold text-slate-800">Navigasi</h3><div class="mt-4 space-y-3 text-sm text-slate-500"><a class="block hover:text-indigo-600" href="#beranda">Beranda</a><a class="block hover:text-indigo-600" href="#fitur">Fitur</a><a class="block hover:text-indigo-600" href="#tentang">Tentang</a></div></div><div><h3 class="font-bold text-slate-800">Lainnya</h3><div class="mt-4 space-y-3 text-sm text-slate-500"><a class="block hover:text-indigo-600" href="{{ route('login') }}">Masuk</a><a class="block hover:text-indigo-600" href="{{ route('register') }}">Daftar akun</a></div></div></div><div class="border-t border-slate-100 px-6 py-5 text-center text-xs text-slate-400">© {{ date('Y') }} TasKuy. Made for Students.</div></footer>
</body>
</html>
