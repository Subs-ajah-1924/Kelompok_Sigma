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
            <button type="button" @click="agendaModal = true" class="h-10 px-4 rounded-xl bg-primary-container hover:bg-primary text-on-primary font-label-md font-medium shadow-sm transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">add</span>
                <span>Tambah Agenda</span>
            </button>
        </div>
    </x-slot>

    <div x-data="{ activeDate: null, activeItems: [], activeTitle: '', agendaModal: false }" class="rounded-2xl bg-surface-container-lowest p-6 border border-surface-container-high shadow-xs relative">
        <div class="grid grid-cols-7 gap-2 mb-2">
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
                        $itemList[] = '📌 ' . $a->title . ($a->start_time ? ' (' . substr((string) $a->start_time, 0, 5) . ')' : '');
                    }
                @endphp
                <div class="relative rounded-xl bg-surface-container-low/40 dark:bg-slate-800/40 border border-surface-container-high dark:border-slate-700 p-2 min-h-[100px] flex flex-col justify-between {{ $day->isToday() ? 'ring-2 ring-primary-container' : '' }}">
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-label-md text-label-md {{ $day->isToday() ? 'text-primary font-bold' : ($day->isSunday() || $holiday ? 'text-error font-bold' : 'text-on-surface-variant') }}">{{ $day->day }}</span>
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
                            <div class="text-xs bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 rounded-md px-1.5 py-0.5 mt-1 truncate" title="{{ $agenda->title }}">{{ $agenda->title }}</div>
                        @endforeach
                    </div>

                    <!-- Popover kotak kecil di sekitar tanggal -->
                    <template x-if="activeDate === '{{ $dateStr }}'">
                        <div @click.away="activeDate = null" class="absolute z-30 left-2 top-10 w-64 bg-surface-container-lowest rounded-xl shadow-xl border border-surface-container-high p-3 flex flex-col gap-2">
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

    <!-- Modal tambah agenda -->
    <div x-show="agendaModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4" style="display:none;">
        <div @click.away="agendaModal = false" class="w-full max-w-md rounded-2xl bg-surface-container-lowest p-6 border border-surface-container-high shadow-xl flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <h3 class="font-title-md text-title-md text-on-surface font-semibold">Tambah Agenda</h3>
                <button type="button" @click="agendaModal = false" class="text-outline hover:text-on-surface">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            <form method="POST" action="{{ route('agendas.store') }}" class="flex flex-col gap-3">
                @csrf
                <div class="flex flex-col gap-2">
                    <label class="font-label-md text-label-md text-on-surface-variant font-medium">Tanggal</label>
                    <input type="date" name="date" value="{{ old('date') }}" class="h-11 px-4 rounded-lg bg-surface-container-low text-on-surface border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary-container" required>
                    @error('date') <p class="font-body-sm text-body-sm text-error">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="flex flex-col gap-2">
                        <label class="font-label-md text-label-md text-on-surface-variant font-medium">Mulai</label>
                        <input type="time" name="start_time" value="{{ old('start_time') }}" class="h-11 px-4 rounded-lg bg-surface-container-low text-on-surface border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary-container">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="font-label-md text-label-md text-on-surface-variant font-medium">Selesai</label>
                        <input type="time" name="end_time" value="{{ old('end_time') }}" class="h-11 px-4 rounded-lg bg-surface-container-low text-on-surface border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary-container">
                    </div>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="font-label-md text-label-md text-on-surface-variant font-medium">Judul</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="h-11 px-4 rounded-lg bg-surface-container-low text-on-surface border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary-container" required>
                    @error('title') <p class="font-body-sm text-body-sm text-error">{{ $message }}</p> @enderror
                </div>
                <div class="flex flex-col gap-2">
                    <label class="font-label-md text-label-md text-on-surface-variant font-medium">Deskripsi</label>
                    <textarea name="description" rows="3" class="px-4 py-2 rounded-lg bg-surface-container-low text-on-surface border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary-container">{{ old('description') }}</textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="agendaModal = false" class="h-10 px-4 rounded-xl bg-surface-container-low text-on-surface font-label-md font-medium">Batal</button>
                    <button type="submit" class="h-10 px-4 rounded-xl bg-primary-container hover:bg-primary text-on-primary font-label-md font-medium shadow-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    </div>
</x-app-layout>
