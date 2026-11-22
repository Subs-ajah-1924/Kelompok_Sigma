<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TasKuy') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">

        <script>
            (function () {
                try {
                    if (localStorage.getItem('theme') === 'dark') {
                        document.documentElement.classList.add('dark');
                    }
                } catch (e) {}
            })();
        </script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-background text-on-surface">
        @auth
        @php
            $themeColor = auth()->user()->theme_color ?? 'indigo';
            $themePrimary = match($themeColor) {
                'rose' => '225 29 72',     // Rose 600
                'emerald' => '5 150 105',  // Emerald 600
                'amber' => '217 119 6',    // Amber 600
                'sky' => '2 132 199',      // Sky 600
                default => '79 70 229',    // Indigo 600
            };
            $themePrimaryContainer = match($themeColor) {
                'rose' => '244 63 94',
                'emerald' => '16 185 129',
                'amber' => '245 158 11',
                'sky' => '14 165 233',
                default => '79 70 229',
            };
        @endphp
        <style>
            :root {
                --color-primary: {{ $themePrimary }};
                --color-primary-container: {{ $themePrimaryContainer }};
            }
        </style>
        <div x-data="{ sidebarOpen: false }" class="min-h-screen">
            <!-- Sidebar (desktop) -->
            <aside class="fixed left-0 top-0 h-full w-[240px] bg-inverse-surface dark:bg-[#0a0c16] text-inverse-on-surface dark:text-slate-200 z-50 flex flex-col justify-between overflow-y-auto px-4 py-5 border-r border-outline-variant dark:border-slate-800">
                <div class="flex flex-col gap-5">
                    <div class="flex items-center gap-3 px-2">
                        <div class="w-9 h-9 rounded-xl overflow-hidden shadow-sm flex-shrink-0">
                            <img src="{{ asset('images/logo.webp') }}" alt="TasKuy Logo" class="w-full h-full object-cover">
                        </div>
                        <div class="flex flex-col">
                            <span class="font-headline-sm text-headline-sm tracking-tight font-bold">TasKuy</span>
                            <span class="font-label-sm text-label-sm text-outline-variant dark:text-slate-500">Student Workspace</span>
                        </div>
                    </div>

                    <nav class="flex flex-col gap-1">
                        @php
                            $navItems = [
                                ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'dashboard'],
                                ['route' => 'tasks.index', 'label' => 'Tugas', 'icon' => 'task_alt'],
                                ['route' => 'kanban.index', 'label' => 'Kanban', 'icon' => 'view_kanban'],
                                ['route' => 'calendar.index', 'label' => 'Kalender', 'icon' => 'calendar_month'],
                                ['route' => 'notifications.index', 'label' => 'Notifikasi', 'icon' => 'notifications'],
                                ['route' => 'courses.index', 'label' => 'Mata Kuliah', 'icon' => 'auto_stories'],
                            ];
                        @endphp
                        @foreach ($navItems as $item)
                            @php
                                $active = request()->routeIs($item['route'] . '*');
                            @endphp
                            <a href="{{ route($item['route']) }}"
                               class="flex items-center justify-between px-3 py-2.5 rounded-xl font-label-lg text-label-lg transition-all
                                      {{ $active ? 'bg-primary-container text-on-primary font-semibold shadow-sm' : 'text-outline-variant dark:text-slate-400 hover:bg-surface-container-highest/10 dark:hover:bg-slate-800/60 hover:text-inverse-on-surface dark:hover:text-slate-100' }}">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-[20px]">{{ $item['icon'] }}</span>
                                    <span>{{ $item['label'] }}</span>
                                </div>
                                @if ($item['route'] === 'notifications.index')
                                    @php $badge = auth()->user()->tasks()->where('status', '!=', 'done')->where('is_read', false)->count(); @endphp
                                    @if ($badge > 0)
                                        <span class="px-2 py-0.5 rounded-full bg-error text-on-error font-label-sm text-label-sm font-semibold">{{ $badge > 9 ? '9+' : $badge }}</span>
                                    @endif
                                @endif
                            </a>
                        @endforeach
                    </nav>

                    <div class="pt-2">
                        <p class="px-3 font-label-sm text-label-sm text-outline-variant dark:text-slate-500 font-semibold tracking-wider uppercase mb-2">Workspace Views</p>
                        <nav class="flex flex-col gap-1">
                            <a href="{{ route('tasks.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl font-label-lg text-label-lg text-outline-variant dark:text-slate-400 hover:bg-surface-container-highest/10 dark:hover:bg-slate-800/60 hover:text-inverse-on-surface dark:hover:text-slate-100 transition-all">
                                <span class="material-symbols-outlined text-[20px]">format_list_bulleted</span>
                                <span>Semua Tugas</span>
                            </a>
                        </nav>
                    </div>
                </div>

                <div class="flex flex-col gap-3 pt-6">
                    <!-- Theme toggle -->
                    <div class="flex items-center justify-between p-1 rounded-xl bg-surface-container-highest/10 dark:bg-slate-800/60">
                        <button type="button" onclick="setTheme('light')" class="theme-btn-light flex-1 flex items-center justify-center gap-1.5 py-1.5 rounded-lg font-label-sm text-label-sm font-semibold transition-colors">
                            <span class="material-symbols-outlined text-[16px]">light_mode</span>
                            <span>Light</span>
                        </button>
                        <button type="button" onclick="setTheme('dark')" class="theme-btn-dark flex-1 flex items-center justify-center gap-1.5 py-1.5 rounded-lg font-label-sm text-label-sm font-medium transition-colors">
                            <span class="material-symbols-outlined text-[16px]">dark_mode</span>
                            <span>Dark</span>
                        </button>
                    </div>

                    <div class="flex items-center gap-3 px-2 py-2">
                        <div class="w-8 h-8 rounded-full bg-primary-container flex items-center justify-center text-on-primary">
                            <span class="material-symbols-outlined text-[18px]">person</span>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="font-label-md text-label-md font-semibold leading-tight truncate">{{ Auth::user()->name }}</span>
                            <span class="font-label-sm text-label-sm text-tertiary dark:text-emerald-400 font-medium truncate">Mahasiswa Aktif</span>
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl font-label-lg text-label-lg text-outline-variant dark:text-slate-400 hover:bg-surface-container-highest/10 dark:hover:bg-slate-800/60 hover:text-inverse-on-surface dark:hover:text-slate-100 transition-all">
                        <span class="material-symbols-outlined text-[20px]">person</span>
                        <span>Profil</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-xl font-label-lg text-label-lg text-outline-variant dark:text-slate-400 hover:bg-surface-container-highest/10 dark:hover:bg-slate-800/60 hover:text-inverse-on-surface dark:hover:text-slate-100 transition-all">
                            <span class="material-symbols-outlined text-[20px]">logout</span>
                            <span>Keluar</span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Mobile top bar -->
            <div class="lg:hidden fixed top-0 inset-x-0 z-40 bg-surface-container-lowest dark:bg-[#0a0c16] border-b border-outline-variant dark:border-slate-800 flex items-center justify-between px-4 h-14">
                <button @click="sidebarOpen = true" class="p-2 text-on-surface-variant" aria-label="Buka menu">
                    <span class="material-symbols-outlined text-[24px]">menu</span>
                </button>
                <a href="{{ route('dashboard') }}" class="font-headline-sm text-headline-sm font-bold text-primary">TasKuy</a>
                <a href="{{ route('notifications.index') }}" class="relative p-2 text-on-surface-variant" aria-label="Notifikasi">
                    <span class="material-symbols-outlined text-[24px]">notifications</span>
                    @php $notifCount = auth()->user()->tasks()->where('status', '!=', 'done')->where('is_read', false)->count(); @endphp
                    @if ($notifCount > 0)
                        <span class="absolute top-1 right-1 inline-flex items-center justify-center w-4 h-4 text-xs font-bold text-on-error bg-error rounded-full">{{ $notifCount > 9 ? '9+' : $notifCount }}</span>
                    @endif
                </a>
            </div>

            <!-- Mobile drawer -->
            <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" class="lg:hidden fixed inset-0 bg-black/40 z-40" style="display:none;"></div>
            <aside x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="lg:hidden fixed inset-y-0 left-0 z-50 w-[240px] bg-inverse-surface dark:bg-[#0a0c16] text-inverse-on-surface dark:text-slate-200 flex flex-col px-4 py-5 overflow-y-auto" style="display:none;">
                <div class="flex items-center justify-between mb-4">
                    <span class="font-headline-sm text-headline-sm font-bold">TasKuy</span>
                    <button @click="sidebarOpen = false" class="p-1 text-outline-variant">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>
                <nav class="flex flex-col gap-1">
                    @foreach ($navItems as $item)
                        <a href="{{ route($item['route']) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-label-lg text-label-lg text-outline-variant dark:text-slate-400 hover:bg-surface-container-highest/10 dark:hover:bg-slate-800/60 hover:text-inverse-on-surface dark:hover:text-slate-100 transition-all">
                            <span class="material-symbols-outlined text-[20px]">{{ $item['icon'] }}</span>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </nav>
            </aside>

            <!-- Main -->
            <div class="lg:pl-[240px] min-h-screen pt-14 lg:pt-0">
                <header class="hidden lg:flex fixed top-0 left-[240px] right-0 h-16 bg-surface-container-lowest dark:bg-[#0f172a] z-40 px-8 items-center justify-between shadow-[0_1px_8px_rgba(0,0,0,0.04)] dark:shadow-none border-b border-outline-variant dark:border-slate-800">
                    <div class="flex items-center gap-4 flex-1">
                        <form method="GET" action="{{ route('tasks.index') }}" class="relative flex items-center w-72">
                            <span class="material-symbols-outlined absolute left-3 text-outline text-[18px]">search</span>
                            <input name="q" value="{{ request('q') }}" class="w-full h-10 pl-9 pr-4 rounded-xl bg-surface-container-low dark:bg-slate-800 text-on-surface placeholder:text-outline font-body-sm text-body-sm focus:outline-none focus:ring-2 focus:ring-primary-container transition-all border border-transparent focus:border-primary-container" placeholder="Cari tugas..." type="text">
                        </form>
                        <a href="{{ route('tasks.create') }}" class="flex items-center gap-2 h-10 px-4 rounded-xl bg-primary-container hover:bg-primary text-on-primary font-label-lg text-label-lg font-medium shadow-sm transition-all">
                            <span class="material-symbols-outlined text-[18px]">add</span>
                            <span>Buat Tugas</span>
                        </a>
                    </div>
                    <div class="flex items-center gap-4">
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="relative p-2 rounded-xl text-on-surface-variant hover:bg-surface-container-low dark:hover:bg-slate-800 hover:text-on-surface transition-colors" aria-label="Notifikasi">
                                <span class="material-symbols-outlined text-[20px]">notifications</span>
                                @php $hc = auth()->user()->tasks()->where('status', '!=', 'done')->where('is_read', false)->count(); @endphp
                                @if ($hc > 0)
                                    <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-error"></span>
                                @endif
                            </button>

                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-96 bg-surface-container-lowest dark:bg-slate-900 rounded-2xl shadow-xl border border-surface-container-high dark:border-slate-800 z-50 p-5 max-h-[500px] overflow-y-auto" style="display:none;">
                                <h3 class="font-title-lg text-title-lg text-on-surface dark:text-slate-100 mb-4">Tugas Baru</h3>
                                <div class="flex flex-col gap-3">
                                    @php $newTasks = auth()->user()->tasks()->where('status', '!=', 'done')->where('is_read', false)->latest()->limit(5)->get(); @endphp
                                    @forelse ($newTasks as $task)
                                        <a href="{{ route('tasks.markAsRead', $task) }}" class="block p-4 rounded-xl bg-surface-container-low dark:bg-slate-800 hover:bg-surface-container dark:hover:bg-slate-700 transition-colors">
                                            <p class="font-body-md text-body-md text-on-surface dark:text-slate-200 leading-snug">
                                                <span class="font-bold text-primary">Ada Tugas Baru!</span><br>
                                                Cek Sekarang: {{ $task->title }}
                                            </p>
                                        </a>
                                    @empty
                                        <p class="text-sm text-on-surface-variant dark:text-slate-400 p-2">Tidak ada tugas baru.</p>
                                    @endforelse
                                </div>
                                <div class="mt-4 pt-3 border-t border-surface-container-high dark:border-slate-800">
                                    <a href="{{ route('notifications.index') }}" class="block text-left text-sm text-primary font-semibold hover:underline">Lihat Semua</a>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 pl-2 border-l border-surface-container-high dark:border-slate-700">
                            <div class="w-8 h-8 rounded-full bg-primary-container flex items-center justify-center text-on-primary">
                                <span class="material-symbols-outlined text-[18px]">person</span>
                            </div>
                            <div class="flex flex-col text-left">
                                <span class="font-label-md text-label-md text-on-surface font-semibold leading-tight">{{ Auth::user()->name }}</span>
                                <span class="font-label-sm text-label-sm text-tertiary dark:text-emerald-400 font-medium">Mahasiswa Aktif</span>
                            </div>
                        </div>
                    </div>
                </header>

                @isset($header)
                    <div class="hidden lg:block h-16"></div>
                @endisset

                <div class="max-w-[1440px] mx-auto p-4 lg:p-8">
                    <!-- Page header slot -->
                    @isset($header)
                        <div class="mb-6">
                            {{ $header }}
                        </div>
                    @endisset

                    <!-- Flash messages -->
                    @if (session('success'))
                        <div class="mb-4 rounded-xl bg-tertiary-fixed/30 dark:bg-emerald-900/30 border border-tertiary/30 dark:border-emerald-700 text-tertiary dark:text-emerald-300 px-4 py-3 text-sm" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 rounded-xl bg-error-container dark:bg-rose-900/30 border border-error/30 dark:border-rose-700 text-on-error-container dark:text-rose-300 px-4 py-3 text-sm" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="mb-4 rounded-xl bg-error-container dark:bg-rose-900/30 border border-error/30 dark:border-rose-700 text-on-error-container dark:text-rose-300 px-4 py-3 text-sm" role="alert">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </div>
        </div>
        @else
        <div class="min-h-screen">
            <main>
                {{ $slot }}
            </main>
        </div>
        @endauth

        <script>
            function setTheme(mode) {
                if (mode === 'dark') {
                    document.documentElement.classList.add('dark');
                    try { localStorage.setItem('theme', 'dark'); } catch (e) {}
                } else {
                    document.documentElement.classList.remove('dark');
                    try { localStorage.setItem('theme', 'light'); } catch (e) {}
                }
                updateThemeButtons();
            }
            function updateThemeButtons() {
                const dark = document.documentElement.classList.contains('dark');
                document.querySelectorAll('.theme-btn-light').forEach(b => {
                    b.classList.toggle('bg-surface-container-lowest', !dark);
                    b.classList.toggle('text-on-surface', !dark);
                    b.classList.toggle('shadow-xs', !dark);
                    b.classList.toggle('text-outline-variant', dark);
                });
                document.querySelectorAll('.theme-btn-dark').forEach(b => {
                    b.classList.toggle('bg-surface-container-lowest', dark);
                    b.classList.toggle('text-on-surface', dark);
                    b.classList.toggle('shadow-xs', dark);
                    b.classList.toggle('text-outline-variant', !dark);
                });
            }
            document.addEventListener('DOMContentLoaded', updateThemeButtons);
        </script>
    </body>
</html>
