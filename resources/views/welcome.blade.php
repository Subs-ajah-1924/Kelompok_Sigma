<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'TasKuy') }} - Student Workspace</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes floatSlow1 {
            0%, 100% { transform: translate(0px, 0px) scale(1); }
            50% { transform: translate(80px, 120px) scale(1.1); }
        }
        @keyframes floatSlow2 {
            0%, 100% { transform: translate(0px, 0px) scale(1); }
            50% { transform: translate(-90px, -100px) scale(1.15); }
        }
        @keyframes floatSlow3 {
            0%, 100% { transform: translate(0px, 0px) scale(1); }
            50% { transform: translate(-70px, 90px) scale(1.2); }
        }
        @keyframes floatSlow4 {
            0%, 100% { transform: translate(0px, 0px) scale(1); }
            50% { transform: translate(90px, -80px) scale(1.1); }
        }
        @keyframes pulseGlow {
            0%, 100% { opacity: 0.35; transform: scale(1); }
            50% { opacity: 0.65; transform: scale(1.12); }
        }
        @keyframes logoFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-6px) rotate(-2deg); }
        }
        .animate-fade-down { animation: fadeInDown 0.8s ease-out forwards; }
        .animate-fade-up { animation: fadeInUp 0.9s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .logo-float { animation: logoFloat 4s ease-in-out infinite; }
        .blob-1 { animation: floatSlow1 20s ease-in-out infinite; }
        .blob-2 { animation: floatSlow2 25s ease-in-out infinite; }
        .blob-3 { animation: floatSlow3 22s ease-in-out infinite; }
        .blob-4 { animation: floatSlow4 18s ease-in-out infinite; }
        .glow-effect { animation: pulseGlow 9s ease-in-out infinite; }

        @media (prefers-reduced-motion: reduce) {
            .blob-1, .blob-2, .blob-3, .blob-4, .glow-effect {
                animation: none !important;
            }
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-sans antialiased bg-[#FCFAFF] dark:bg-[#0a0c16] text-slate-900 dark:text-slate-100 flex flex-col justify-between selection:bg-indigo-500 selection:text-white relative overflow-x-hidden">
    
    <!-- Fixed Ambient Background Covering Full Viewport -->
    <div class="fixed inset-0 w-screen h-screen pointer-events-none z-0 overflow-hidden bg-[#FCFAFF] dark:bg-[#0a0c16]">
        <!-- Radial Gradient Blobs with Soft Purple #E9D5FF, Lavender #DDD6FE, Soft Blue #DBEAFE, Soft Pink #FCE7F3 -->
        <div class="absolute -top-32 -left-32 w-[600px] h-[600px] bg-[#E9D5FF]/60 dark:bg-purple-950/30 rounded-full blur-[130px] blob-1"></div>
        <div class="absolute top-1/4 -right-32 w-[650px] h-[650px] bg-[#DBEAFE]/70 dark:bg-indigo-950/30 rounded-full blur-[140px] blob-2"></div>
        <div class="absolute top-2/3 -left-40 w-[600px] h-[600px] bg-[#FCE7F3]/60 dark:bg-pink-950/20 rounded-full blur-[130px] blob-3"></div>
        <div class="absolute -bottom-32 right-1/4 w-[600px] h-[600px] bg-[#DDD6FE]/60 dark:bg-violet-950/30 rounded-full blur-[140px] blob-4"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[600px] bg-gradient-to-tr from-[#E9D5FF]/40 via-[#DDD6FE]/40 to-[#DBEAFE]/40 dark:from-purple-900/10 dark:to-indigo-900/10 rounded-full blur-[160px] glow-effect"></div>
    </div>

    <!-- Header / Navbar -->
    <header class="w-full max-w-7xl mx-auto px-6 py-6 flex items-center justify-between animate-fade-down relative z-10">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl overflow-hidden shadow-md shadow-indigo-500/20 flex-shrink-0 bg-indigo-600 logo-float">
                <img src="{{ asset('images/logo.webp') }}" alt="TasKuy Logo" class="w-full h-full object-cover">
            </div>
            <span class="font-bold text-xl tracking-tight bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">TasKuy</span>
        </div>
        <div class="flex items-center gap-3">
            @auth
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm transition-all duration-300 shadow-md shadow-indigo-500/20 hover:scale-105">
                    <span class="material-symbols-outlined text-[18px]">dashboard</span>
                    <span>Dashboard</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="flex items-center gap-1.5 px-5 py-2.5 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-200/60 dark:hover:bg-slate-800 font-medium text-sm transition-all duration-300 hover:scale-105">
                    <span class="material-symbols-outlined text-[18px]">login</span>
                    <span>Masuk</span>
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm transition-all duration-300 shadow-md shadow-indigo-500/20 hover:scale-105">
                        <span class="material-symbols-outlined text-[18px]">person_add</span>
                        <span>Daftar Akun</span>
                    </a>
                @endif
            @endauth
        </div>
    </header>

    <!-- Main Hero -->
    <main class="w-full max-w-5xl mx-auto px-6 py-12 md:py-20 flex flex-col items-center text-center my-auto animate-fade-up relative z-10">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/80 dark:bg-indigo-950/50 backdrop-blur-md text-indigo-600 dark:text-indigo-400 font-medium text-xs mb-8 border border-indigo-100 dark:border-indigo-900/50 shadow-xs transition-transform hover:scale-105 duration-300 cursor-default">
            <span class="material-symbols-outlined text-[16px]">verified</span>
            Student Workspace Terintegrasi
        </div>
        
        <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight mb-6 max-w-3xl leading-[1.15]">
            Kelola Tugas & Kuliah Jadi Lebih <span class="bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">Mudah & Terstruktur</span>
        </h1>
        
        <p class="text-lg md:text-xl text-slate-600 dark:text-slate-400 max-w-2xl mb-10 leading-relaxed">
            Platform produktivitas mahasiswa untuk mencatat tugas, memantau kalender deadline, dan mengatur jadwal mata kuliah dalam satu tempat.
        </p>
        
        <div class="flex flex-col sm:flex-row items-center gap-4 w-full justify-center">
            <a href="{{ route('register') }}" class="w-full sm:w-auto h-12 px-8 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold flex items-center justify-center gap-2 transition-all duration-300 shadow-lg shadow-indigo-500/25 hover:scale-105 hover:shadow-indigo-500/40">
                <span class="material-symbols-outlined text-[20px]">rocket_launch</span>
                <span>Mulai Sekarang - Gratis</span>
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
            <a href="{{ route('login') }}" class="w-full sm:w-auto h-12 px-8 rounded-2xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/80 text-slate-700 dark:text-slate-300 font-semibold flex items-center justify-center gap-2 transition-all duration-300 shadow-xs hover:scale-105">
                <span class="material-symbols-outlined text-[20px]">login</span>
                <span>Masuk ke Akun</span>
            </a>
        </div>

        <!-- Features Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-20 w-full text-left">
            <div class="p-6 rounded-3xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1.5 group">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-4 transition-transform duration-300 group-hover:scale-110">
                    <span class="material-symbols-outlined text-[24px]">task_alt</span>
                </div>
                <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                    <span>Manajemen Tugas</span>
                </h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Atur daftar tugas, prioritas, status pengerjaan, dan deadline dengan tampilan modern.</p>
            </div>

            <div class="p-6 rounded-3xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1.5 group">
                <div class="w-12 h-12 rounded-2xl bg-violet-50 dark:bg-violet-950/60 text-violet-600 dark:text-violet-400 flex items-center justify-center mb-4 transition-transform duration-300 group-hover:scale-110">
                    <span class="material-symbols-outlined text-[24px]">calendar_month</span>
                </div>
                <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                    <span>Kalender & Kanban</span>
                </h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Visualisasikan jadwal dan progress tugas menggunakan papan Kanban dan kalender interaktif.</p>
            </div>

            <div class="p-6 rounded-3xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1.5 group">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-4 transition-transform duration-300 group-hover:scale-110">
                    <span class="material-symbols-outlined text-[24px]">auto_stories</span>
                </div>
                <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                    <span>Mata Kuliah & Statistik</span>
                </h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Simpan data mata kuliah dan pantau statistik produktivitas mingguan Anda.</p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full max-w-7xl mx-auto px-6 py-6 text-center text-xs text-slate-500 dark:text-slate-400 border-t border-slate-200/80 dark:border-slate-800/80 relative z-10">
        &copy; {{ date('Y') }} TasKuy Student Workspace. All rights reserved.
    </footer>
</body>
</html>
