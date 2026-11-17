<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center text-primary-container shadow-xs">
                <span class="material-symbols-outlined text-[24px]">dashboard</span>
            </div>
            <div class="flex items-center gap-2.5">
                <h1 class="font-headline-lg text-headline-lg text-on-surface font-bold tracking-tight">Dashboard</h1>
            </div>
        </div>
    </x-slot>

    <div class="flex flex-col gap-6">
        <!-- Greeting -->
        <div class="rounded-2xl bg-surface-container-lowest p-6 shadow-xs border border-surface-container-high flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="flex flex-col gap-1">
                <h2 class="font-headline-lg text-headline-lg text-on-surface font-semibold tracking-tight">Halo, {{ auth()->user()->name }}</h2>
                <div class="flex items-center gap-2 text-on-surface-variant font-body-sm text-body-sm">
                    <span class="material-symbols-outlined text-[16px] text-outline">calendar_today</span>
                    <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>
            </div>
            <div class="flex items-center gap-4 bg-surface-container-low/50 px-4 py-2.5 rounded-xl border border-surface-container-high">
                <div class="flex flex-col">
                    <span class="font-label-sm text-label-sm text-on-surface-variant">Total Tugas</span>
                    <span class="font-title-md text-title-md text-on-surface font-semibold">{{ $totalTasks }}</span>
                </div>
                <div class="h-8 w-px bg-surface-container-high"></div>
                <span class="font-title-md text-title-md font-bold text-primary">{{ $doneCount }} selesai</span>
            </div>
        </div>

        <!-- Stat cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ([
                ['label' => 'Total Tugas Aktif', 'value' => $totalTasks - $doneCount, 'icon' => 'assignment', 'hint' => 'Belum selesai'],
                ['label' => 'To Do', 'value' => $todoCount, 'icon' => 'pending_actions', 'hint' => 'Belum dimulai'],
                ['label' => 'In Progress', 'value' => $inProgressCount, 'icon' => 'sync', 'hint' => 'Sedang berjalan'],
                ['label' => 'Selesai', 'value' => $doneCount, 'icon' => 'task_alt', 'hint' => 'Total selesai'],
            ] as $card)
                <div class="rounded-2xl bg-surface-container-lowest p-5 border border-surface-container-high shadow-xs flex flex-col justify-between gap-3">
                    <div class="flex items-center justify-between">
                        <span class="font-label-md text-label-md text-on-surface-variant font-medium">{{ $card['label'] }}</span>
                        <span class="material-symbols-outlined text-outline text-[20px]">{{ $card['icon'] }}</span>
                    </div>
                    <div class="flex items-baseline justify-between">
                        <span class="font-display-lg text-display-lg text-on-surface font-bold">{{ $card['value'] }}</span>
                        <span class="font-label-sm text-label-sm text-on-surface-variant">{{ $card['hint'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($overdueCount > 0)
            <div class="rounded-2xl bg-error-container/40 dark:bg-rose-900/20 border border-error/30 dark:border-rose-700 p-4 flex items-center gap-3">
                <span class="material-symbols-outlined text-error text-[24px]">warning</span>
                <span class="font-body-md text-body-md text-on-error-container dark:text-rose-300">
                    Ada {{ $overdueCount }} tugas yang sudah melewati deadline.
                </span>
            </div>
        @endif

        <!-- Upcoming tasks -->
        <div class="rounded-2xl bg-surface-container-lowest p-6 border border-surface-container-high shadow-xs flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[18px]">calendar_clock</span>
                    </div>
                    <div>
                        <h3 class="font-title-md text-title-md text-on-surface font-semibold">Deadline Terdekat</h3>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">Jadwal pengumpulan tugas paling mendesak</p>
                    </div>
                </div>
                <a href="{{ route('calendar.index') }}" class="font-label-md text-label-md text-primary font-semibold hover:underline flex items-center gap-1">
                    <span>Lihat Kalender</span>
                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                </a>
            </div>
            @if ($upcomingTasks->isEmpty())
                <p class="text-on-surface-variant font-body-sm text-body-sm">Belum ada tugas dengan deadline terdekat.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    @foreach ($upcomingTasks as $task)
                        <a href="{{ route('tasks.show', $task) }}" class="p-3.5 rounded-xl bg-surface-container-low/60 dark:bg-slate-800/60 border border-surface-container-high dark:border-slate-700 flex flex-col justify-between gap-2 hover:border-primary-container transition-colors">
                            <div class="flex items-start justify-between gap-2">
                                <span class="font-title-md text-title-md text-on-surface font-medium truncate">{{ $task->title }}</span>
                                <span class="px-2 py-0.5 rounded-md bg-surface-container text-on-surface-variant font-label-sm text-label-sm font-medium shrink-0">{{ $task->deadline->format('H:i') }}</span>
                            </div>
                            <div class="flex items-center justify-between text-on-surface-variant font-label-sm text-label-sm">
                                <span>{{ $task->course->name ?? 'Tanpa mata kuliah' }}</span>
                                <span class="{{ $task->deadline->isPast() ? 'text-error font-medium' : '' }}">{{ $task->deadline->translatedFormat('d M') }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Recent tasks -->
        <div class="rounded-2xl bg-surface-container-lowest p-6 shadow-sm flex flex-col gap-5 border border-surface-container-high">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-surface-container text-primary-container flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px]">format_list_bulleted</span>
                    </div>
                    <div>
                        <h3 class="font-title-md text-title-md text-on-surface font-semibold">Tugas Terbaru</h3>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">Daftar penugasan aktif semester ini</p>
                    </div>
                </div>
                <a href="{{ route('tasks.index') }}" class="font-label-md text-label-md text-primary font-semibold hover:underline flex items-center gap-1">
                    <span>Lihat Semua</span>
                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                </a>
            </div>
            @if ($recentTasks->isEmpty())
                <p class="text-on-surface-variant font-body-sm text-body-sm">Belum ada tugas. <a href="{{ route('tasks.create') }}" class="text-primary font-semibold">Tambah sekarang</a>.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="font-label-sm text-label-sm text-outline uppercase tracking-wider bg-surface-container-low/50">
                                <th class="py-3 px-4 rounded-l-xl">Judul Tugas</th>
                                <th class="py-3 px-4">Mata Kuliah</th>
                                <th class="py-3 px-4">Deadline</th>
                                <th class="py-3 px-4">Kesulitan</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4 rounded-r-xl text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="font-body-md text-body-md">
                            @foreach ($recentTasks as $task)
                                <tr class="hover:bg-surface-container-low/40 transition-colors group">
                                    <td class="py-3.5 px-4 font-semibold text-on-surface">
                                        <a href="{{ route('tasks.show', $task) }}" class="group-hover:text-primary transition-colors">{{ $task->title }}</a>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-primary-fixed text-primary font-label-sm text-label-sm font-medium">
                                            <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                                            {{ $task->course->name ?? 'Tanpa mata kuliah' }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-on-surface-variant font-label-md text-label-md">{{ $task->deadline->format('d M Y, H:i') }}</td>
                                    <td class="py-3.5 px-4">
                                        <span class="px-2 py-0.5 rounded-full bg-surface-container-high text-on-surface-variant font-label-sm text-label-sm font-semibold">{{ $task->difficulty->label() }}</span>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="px-2.5 py-1 rounded-full bg-surface-container-high text-on-surface font-label-sm text-label-sm font-medium flex items-center gap-1.5 w-fit">
                                            <span class="w-2 h-2 rounded-full bg-primary-container"></span>
                                            {{ $task->status->label() }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        <a href="{{ route('tasks.edit', $task) }}" class="p-1.5 rounded-lg text-outline hover:text-on-surface hover:bg-surface-container transition-colors inline-block">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Recommendations -->
        @if ($recommendations->isNotEmpty())
            <div class="rounded-2xl bg-surface-container-lowest p-6 border border-surface-container-high shadow-xs flex flex-col gap-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[18px]">lightbulb</span>
                    </div>
                    <div>
                        <h3 class="font-title-md text-title-md text-on-surface font-semibold">Rekomendasi Fokus</h3>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">Rekomendasi, bukan urutan otomatis.</p>
                    </div>
                </div>
                <div class="flex flex-col gap-2">
                    @foreach ($recommendations as $item)
                        <a href="{{ route('tasks.show', $item['task']) }}" class="flex items-center justify-between p-3 rounded-xl bg-surface-container-low/60 dark:bg-slate-800/60 border border-surface-container-high dark:border-slate-700 hover:border-primary-container transition-colors">
                            <div class="flex flex-col min-w-0">
                                <span class="font-title-md text-title-md text-on-surface font-medium truncate">{{ $item['task']->title }}</span>
                                <span class="font-body-sm text-body-sm text-on-surface-variant truncate">{{ $item['recommendation']->reason }}</span>
                            </div>
                            <span class="px-2 py-0.5 rounded-full bg-primary-fixed text-primary font-label-sm text-label-sm font-semibold shrink-0 ml-3">{{ $item['recommendation']->priority->label() }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
