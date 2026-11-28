<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center text-primary-container shadow-xs">
                    <span class="material-symbols-outlined text-[24px]">calendar_month</span>
                </div>
                <h1 class="font-headline-lg text-headline-lg text-on-surface font-bold tracking-tight">Kalender</h1>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('calendar.index', ['month' => $month->copy()->subMonth()->format('Y-m')]) }}" class="h-10 w-10 rounded-xl bg-surface-container-low hover:bg-surface-container text-on-surface flex items-center justify-center transition-all">
                    <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                </a>
                <span class="font-title-md text-title-md text-on-surface font-semibold px-3">{{ $month->translatedFormat('F Y') }}</span>
                <a href="{{ route('calendar.index', ['month' => $month->copy()->addMonth()->format('Y-m')]) }}" class="h-10 w-10 rounded-xl bg-surface-container-low hover:bg-surface-container text-on-surface flex items-center justify-center transition-all">
                    <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div x-data="{ activeDate: null, activeItems: [], activeTitle: '' }" class="relative isolate overflow-visible rounded-3xl border-2 border-primary/20 bg-transparent shadow-2xl shadow-primary/10">
        <div class="pointer-events-none absolute inset-0 -z-10 rounded-3xl bg-gradient-to-br from-indigo-50 via-white to-violet-100 dark:from-surface-container-lowest dark:via-surface-container-low dark:to-primary/20"></div>
        <div class="pointer-events-none absolute -right-16 -top-20 z-0 h-64 w-64 rounded-full bg-primary/15 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-28 -left-20 h-64 w-64 rounded-full bg-secondary/10 blur-3xl"></div>
        <div class="pointer-events-none absolute inset-0 -z-10 rounded-3xl opacity-[0.035] [background-image:radial-gradient(rgb(79_70_229)_1px,transparent_1px)] [background-size:18px_18px]"></div>
        <div class="relative flex flex-wrap items-center justify-between gap-3 overflow-hidden rounded-t-3xl border-b border-primary/20 bg-gradient-to-r from-primary/10 via-violet-100/70 to-secondary/10 px-6 py-5 backdrop-blur-sm dark:from-primary/20 dark:via-surface-container dark:to-secondary/20">
            <div>
                <p class="font-label-sm text-label-sm font-semibold uppercase tracking-[0.18em] text-primary">Agenda kamu</p>
                <h2 class="mt-1 font-title-lg text-title-lg font-bold text-on-surface">Atur hari, raih target</h2>
            </div>
            <div class="flex items-center gap-3 text-xs font-medium text-on-surface-variant">
                <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-rose-400"></span>Tugas</span>
                <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-blue-400"></span>Agenda</span>
                <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-error"></span>Libur</span>
            </div>
        </div>
        <div class="relative p-4 sm:p-6">
        <div class="mb-3 grid grid-cols-7 gap-2 rounded-2xl bg-primary/10 px-2 py-2 ring-1 ring-primary/15">

            @foreach (['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $d)
                <div class="text-center font-label-md text-label-md text-outline font-semibold py-2">{{ $d }}</div>
            @endforeach
        </div>
        <div class="grid grid-cols-7 gap-2">
            @foreach ($days as $day)
                @php
                    $dateStr = $day->format('Y-m-d');
                    $holiday = $holidays[$dateStr] ?? null;
                    $dayTasks = $tasks->get($dateStr, collect());
                    $dayAgendas = $agendas->get($dateStr, collect());
                    $hasDetail = $holiday || $dayTasks->count() > 0 || $dayAgendas->count() > 0;
                    
                    $itemList = [];
                    if ($holiday) {
                        $itemList[] = '🔴 ' . $holiday;
                    }
                    foreach ($dayTasks as $t) {
                        $itemList[] = '📚 ' . $t->title . ($t->course && $t->course->name ? ' (' . $t->course->name . ')' : '');
                    }
                    foreach ($dayAgendas as $a) {
                        $itemList[] = '📌 ' . $a->title . ' (' . substr($a->start_time, 0, 5) . '-' . substr($a->end_time, 0, 5) . ')';
                    }
                @endphp
                <div class="group relative rounded-2xl bg-white/75 dark:bg-slate-800/60 border border-indigo-200/70 dark:border-slate-700 p-2.5 min-h-[112px] flex flex-col justify-between shadow-sm transition-all hover:-translate-y-1 hover:border-primary hover:shadow-lg {{ $day->isToday() ? 'bg-primary/15 ring-2 ring-primary shadow-md' : '' }}" :class="activeDate === '{{ $dateStr }}' ? 'z-40 !transform-none' : 'z-0'">
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full font-label-md text-label-md {{ $day->isToday() ? 'bg-primary text-white font-bold shadow-sm' : ($day->isSunday() || $holiday ? 'text-error font-bold' : 'text-on-surface-variant') }}">{{ $day->day }}</span>
                            @if($hasDetail)
                                <button @click.stop="activeDate = (activeDate === '{{ $dateStr }}' ? null : '{{ $dateStr }}'); activeTitle = '{{ $day->translatedFormat('d F Y') }}'; activeItems = {{ json_encode($itemList) }}" class="w-5 h-5 rounded-full bg-primary-container/40 hover:bg-primary-container text-primary flex items-center justify-center transition-colors" title="Lihat Detail">
                                    <span class="material-symbols-outlined text-[14px]">info</span>
                                </button>
                            @endif
                        </div>
                        @if($holiday)
                            <div class="text-[10px] text-error font-medium truncate mb-1" title="{{ $holiday }}">{{ $holiday }}</div>
                        @endif
                        @foreach ($dayTasks as $task)
                            <div class="text-xs bg-rose-100 dark:bg-rose-900/40 text-rose-700 dark:text-rose-300 rounded-md px-1.5 py-0.5 mt-1 truncate" title="{{ $task->title }}">{{ $task->title }}</div>
                        @endforeach
                        @foreach ($dayAgendas as $agenda)
                            <div class="text-xs bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 rounded-md px-1.5 py-0.5 mt-1 truncate" title="{{ $agenda->title }}">{{ $agenda->title }}</div>
                        @endforeach
                    </div>

                    <!-- Popover kotak kecil di sekitar tanggal -->
                    <template x-if="activeDate === '{{ $dateStr }}'">
                        <div @click.stop @click.away="activeDate = null" class="absolute z-50 left-1/2 top-10 w-64 -translate-x-1/2 bg-surface-container-lowest rounded-xl shadow-2xl border border-primary/20 p-3 flex flex-col gap-2">
                            <div class="flex items-center justify-between border-b border-surface-container-high pb-1.5">
                                <span class="font-label-md font-bold text-on-surface" x-text="activeTitle"></span>
                                <button @click="activeDate = null" class="text-outline hover:text-on-surface">
                                    <span class="material-symbols-outlined text-[16px]">close</span>
                                </button>
                            </div>
                            <div class="flex flex-col gap-1.5 max-h-40 overflow-y-auto">
                                <template x-for="item in activeItems">
                                    <div class="p-2 rounded-lg bg-surface-container-low text-on-surface text-xs" x-text="item"></div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            @endforeach
        </div>
        </div>
    </div>
</x-app-layout>
