<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TasKuy') }}</title>

        <script>
            (function () {
                var dark = false;
                try {
                    dark = localStorage.getItem('theme') === 'dark';
                } catch (e) {}
                document.documentElement.classList.toggle('dark', dark);
                document.documentElement.style.colorScheme = dark ? 'dark' : 'light';
                function markFontsLoaded() {
                    document.documentElement.classList.add('fonts-loaded');
                }
                if (document.fonts && typeof document.fonts.check === 'function' && document.fonts.check('24px "Material Symbols Outlined"')) {
                    markFontsLoaded();
                } else if (document.fonts && typeof document.fonts.ready !== 'undefined') {
                    document.fonts.ready.then(markFontsLoaded);
                    setTimeout(markFontsLoaded, 3000);
                } else {
                    markFontsLoaded();
                }
            })();

            function setTheme(mode) {
                var dark = mode === 'dark';
                document.documentElement.classList.toggle('dark', dark);
                document.documentElement.style.colorScheme = dark ? 'dark' : 'light';
                try {
                    localStorage.setItem('theme', dark ? 'dark' : 'light');
                } catch (e) {}
            }
        </script>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="preload" as="font" type="font/ttf" crossorigin href="https://fonts.gstatic.com/s/materialsymbolsoutlined/v385/kJF1BvYX7BgnkSrUwT8OhrdQw4oELdPIeeII9v6oDMzByHX9rA6RzaxHMPdY43zj-jCxv3fzvRNU22ZXGJpEpjC_1v-p_4MrImHCIJIZrDCvHOem.ttf">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=block" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-background text-on-surface">
        @auth
        @php
            $themeColor = auth()->user()->theme_color ?? 'indigo';
            $themePrimary = match($themeColor) {
                'rose' => '225 29 72',     // Rose 600
                'emerald' => '6 95 70',     // Emerald 800 (hijau tua pekat/tebal)
                'amber' => '217 119 6',    // Amber 600
                'sky' => '2 132 199',      // Sky 600
                default => '79 70 229',    // Indigo 600
            };
            $themePrimaryContainer = match($themeColor) {
                'rose' => '244 63 94',
                'emerald' => '4 120 87',   // Emerald 700 container
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
        <div x-data="{ sidebarOpen: false, sidebarExpanded: true, confirmLogout: false }" class="min-h-screen">
            <div x-show="confirmLogout" class="fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-black/40 backdrop-blur-3xs" style="display:none;" x-cloak>
                <div class="bg-white dark:bg-[#111729] p-3 rounded-2xl shadow-xl w-56 border border-gray-200 dark:border-slate-800 text-center flex flex-col items-center">
                    <p class="text-xs font-bold text-gray-900 dark:text-slate-100 mb-6">Anda yakin ingin keluar?</p>
                    <div class="flex gap-2 justify-center">
                        <button type="button" @click="confirmLogout = false" class="w-24 px-3 py-1.5 rounded-xl border border-gray-300 dark:border-slate-700 text-xs font-semibold text-gray-700 dark:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">No</button>
                        <form method="POST" action="{{ route('logout') }}" class="w-24">
                            @csrf
                            <button type="submit" class="w-full px-3 py-1.5 rounded-xl bg-red-600 text-white text-xs font-semibold hover:bg-red-700 transition-colors">Yes</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Sidebar (desktop) -->
            <aside x-show="sidebarExpanded" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed left-0 top-0 h-full w-[240px] bg-inverse-surface dark:bg-[#0a0c16] text-inverse-on-surface dark:text-slate-200 z-50 flex flex-col justify-between overflow-y-auto px-4 py-5 border-none dark:border-r dark:border-slate-800">
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
                                ['route' => 'courses.index', 'label' => 'Mata Kuliah', 'icon' => 'auto_stories'],
                            ];
                        @endphp
                        @foreach ($navItems as $item)
                            @php
                                $active = request()->routeIs($item['route'] . '*');
                            @endphp
                            <a href="{{ route($item['route']) }}"
                               class="flex items-center justify-between px-3 py-2.5 rounded-xl font-label-lg text-label-lg transition-all
                                      {{ $active ? 'bg-primary text-white font-semibold shadow-sm' : 'text-outline-variant dark:text-slate-400 hover:bg-surface-container-highest/10 dark:hover:bg-slate-800/60 hover:text-inverse-on-surface dark:hover:text-slate-100' }}">
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
                        <p class="px-3 font-label-sm text-label-sm text-outline-variant dark:text-slate-500 font-semibold tracking-wider uppercase mb-2">TOOLS</p>
                        <nav class="flex flex-col gap-1">
                            @php
                                $statActive = request()->routeIs('statistics.index');
                            @endphp
                            <a href="{{ route('statistics.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-label-lg text-label-lg transition-all {{ $statActive ? 'bg-primary text-white font-semibold shadow-sm' : 'text-outline-variant dark:text-slate-400 hover:bg-surface-container-highest/10 dark:hover:bg-slate-800/60 hover:text-inverse-on-surface dark:hover:text-slate-100' }}">
                                <span class="material-symbols-outlined text-[20px]">bar_chart</span>
                                <span>Statistik</span>
                            </a>
                        </nav>
                    </div>
                </div>

                <div class="flex flex-col gap-3 pt-6">
                    <!-- Theme toggle -->
                    <div class="flex items-center justify-between p-1 rounded-xl bg-surface-container-highest/10 dark:bg-slate-800/60">
                        <button type="button" onclick="setTheme('light')" class="theme-btn-light flex-1 flex items-center justify-center gap-1.5 py-1.5 rounded-lg font-label-sm text-label-sm font-semibold transition-colors bg-surface-container-lowest text-on-surface shadow-xs dark:bg-transparent dark:text-outline-variant dark:shadow-none">
                            <span class="material-symbols-outlined text-[16px]">light_mode</span>
                            <span>Light</span>
                        </button>
                        <button type="button" onclick="setTheme('dark')" class="theme-btn-dark flex-1 flex items-center justify-center gap-1.5 py-1.5 rounded-lg font-label-sm text-label-sm font-semibold transition-colors text-outline-variant dark:bg-surface-container-lowest dark:text-on-surface dark:shadow-xs">
                            <span class="material-symbols-outlined text-[16px]">dark_mode</span>
                            <span>Dark</span>
                        </button>
                    </div>


                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl font-label-lg text-label-lg text-outline-variant dark:text-slate-400 hover:bg-surface-container-highest/10 dark:hover:bg-slate-800/60 hover:text-inverse-on-surface dark:hover:text-slate-100 transition-all">
                        <span class="material-symbols-outlined text-[20px]">person</span>
                        <span>Profil</span>
                    </a>
                    <button type="button" @click="confirmLogout = true" class="w-full flex items-center gap-3 px-3 py-2 rounded-xl font-label-lg text-label-lg text-outline-variant dark:text-slate-400 hover:bg-surface-container-highest/10 dark:hover:bg-slate-800/60 hover:text-inverse-on-surface dark:hover:text-slate-100 transition-all text-left">
                        <span class="material-symbols-outlined text-[20px]">logout</span>
                        <span>Keluar</span>
                    </button>
                </div>
            </aside>

            <!-- Mobile top bar -->
            <div class="lg:hidden fixed top-0 inset-x-0 z-40 bg-surface-container-lowest dark:bg-[#0a0c16] border-b border-outline-variant dark:border-slate-800 flex items-center justify-between px-4 h-14">
                <button @click="sidebarOpen = true" class="p-2 text-on-surface-variant" aria-label="Buka menu">
                    <span class="material-symbols-outlined text-[24px]">menu</span>
                </button>
                <a href="{{ route('dashboard') }}" class="font-headline-sm text-headline-sm font-bold text-primary">TasKuy</a>
                <div class="w-10"></div>
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
            <div class="lg:pl-[240px] min-h-screen pt-14 lg:pt-0" :class="!sidebarExpanded ? 'lg:!pl-0' : ''">
                <header class="hidden lg:flex fixed top-0 left-[240px] right-0 h-16 bg-primary dark:bg-primary text-white z-40 px-8 items-center justify-between shadow-sm dark:shadow-none border-b-0" :class="!sidebarExpanded ? 'lg:!left-0' : ''">
                    <div class="flex items-center gap-4 flex-1">
                        <button @click="sidebarExpanded = !sidebarExpanded" class="p-2 text-white dark:text-slate-300 hover:bg-white/10 rounded-lg transition-colors">
                            <span class="material-symbols-outlined text-[24px]">menu</span>
                        </button>
                        <form method="GET" action="{{ route('tasks.index') }}" class="relative flex items-center w-72" x-data="{ query: '{{ request('q') }}' }">
                            <span class="material-symbols-outlined absolute left-3 text-white/70 dark:text-outline text-[18px]">search</span>
                            <input name="q" x-model="query" @input.debounce.300ms="$el.form.submit()" class="w-full h-10 pl-9 pr-4 rounded-xl bg-white/10 dark:bg-slate-800 text-white dark:text-on-surface placeholder:text-white/60 dark:placeholder:text-outline font-body-sm text-body-sm focus:outline-none focus:ring-2 focus:ring-white dark:focus:ring-[color:var(--color-primary-container)] transition-all border border-transparent focus:border-white" placeholder="Cari tugas..." type="text">
                        </form>
                    </div>
                    <div class="flex items-center gap-4">
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="relative p-2 rounded-xl text-white/80 dark:text-on-surface-variant hover:bg-white/10 dark:hover:bg-slate-800 hover:text-white dark:hover:text-on-surface transition-colors" aria-label="Notifikasi">
                                <span class="material-symbols-outlined text-[20px]">notifications</span>
                                @php $unreadCount = auth()->user()->tasks()->where('status', '!=', 'done')->where('is_read', false)->count(); @endphp
                                @if ($unreadCount > 0)
                                    <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-white dark:bg-error"></span>
                                @endif
                            </button>

                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-80 bg-surface-container-lowest rounded-2xl shadow-xl border border-surface-container-high z-50 p-4 max-h-[380px] overflow-y-auto" style="display:none;">
                                <h3 class="font-title-lg text-title-lg text-on-surface mb-3">Tugas Baru</h3>
                                <div class="flex flex-col gap-2.5">
                                    @php $newTasks = auth()->user()->tasks()->with('course')->where('status', '!=', 'done')->where('is_read', false)->latest()->limit(5)->get(); @endphp
                                    @forelse ($newTasks as $task)
                                        <form method="POST" action="{{ route('tasks.markAsRead', $task) }}" class="block">
                                            @csrf
                                            <button type="submit" class="w-full text-left block p-3 rounded-xl bg-surface-container-low hover:bg-surface-container transition-colors">
                                                <p class="font-body-sm text-body-sm text-on-surface leading-snug">
                                                    <span class="font-bold text-primary">Ada Tugas Baru!</span><br>
                                                    Cek Sekarang: {{ $task->title }}
                                                    @if($task->course && $task->course->name)
                                                        <span class="block text-xs text-outline font-medium mt-0.5">Mata Kuliah: {{ $task->course->name }}</span>
                                                    @endif
                                                </p>
                                            </button>
                                        </form>
                                    @empty
                                        <p class="text-sm text-on-surface-variant p-2">Tidak ada tugas baru.</p>
                                    @endforelse
                                </div>
                                <div class="mt-3 pt-2.5 border-t border-surface-container-high">
                                    <a href="{{ route('notifications.index') }}" class="block text-left text-sm text-primary font-semibold hover:underline">Lihat Semua</a>
                                </div>
                            </div>
                        </div>
                                <div x-data="{ profileOpen: false }" class="relative pl-2 border-l border-white/20 dark:border-slate-700">
                            <button @click="profileOpen = !profileOpen" class="flex items-center gap-3 cursor-pointer focus:outline-none" aria-label="Profil Menu">
                                @if (Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-white/20 dark:bg-primary-container flex items-center justify-center text-white dark:text-on-primary">
                                        <span class="material-symbols-outlined text-[18px]">person</span>
                                    </div>
                                @endif
                                <div class="flex flex-col text-left">
                                    <span class="font-label-md text-label-md text-white dark:text-on-surface font-semibold leading-tight">{{ Auth::user()->name }}</span>
                                </div>
                            </button>

                            <div x-show="profileOpen" @click.away="profileOpen = false" class="absolute right-0 mt-2 w-64 bg-surface-container-lowest rounded-2xl shadow-xl border border-surface-container-high z-50 p-4 flex flex-col items-center text-center gap-2" style="display:none;">
                                <div class="flex flex-col items-center border-b border-surface-container-high pb-3 w-full">
                                    @if (Auth::user()->avatar)
                                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="w-14 h-14 rounded-full object-cover mb-2 shadow-sm border-2 border-primary/20">
                                    @else
                                        <div class="w-14 h-14 rounded-full bg-primary-container flex items-center justify-center text-on-primary mb-2 shadow-sm">
                                            <span class="material-symbols-outlined text-[28px]">person</span>
                                        </div>
                                    @endif
                                    <span class="font-title-md text-title-md font-bold text-on-surface w-full break-words">{{ Auth::user()->name }}</span>
                                    <span class="font-label-sm text-label-sm text-tertiary dark:text-emerald-400 font-medium">Mahasiswa Aktif</span>
                                </div>
                                <div class="flex flex-col gap-1 w-full text-left">
                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl font-label-md text-label-md text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface transition-all">
                                        <span class="material-symbols-outlined text-[18px]">person</span>
                                        <span>Profil</span>
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-xl font-label-md text-label-md text-error hover:bg-error-container/20 transition-all text-left">
                                            <span class="material-symbols-outlined text-[18px]">logout</span>
                                            <span>Keluar</span>
                                        </button>
                                    </form>
                                </div>
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
    </body>
</html>
