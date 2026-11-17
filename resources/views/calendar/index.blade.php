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

    <div class="rounded-2xl bg-surface-container-lowest p-6 border border-surface-container-high shadow-xs">
        <div class="grid grid-cols-7 gap-2 mb-2">
            @foreach (['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $d)
                <div class="text-center font-label-md text-label-md text-outline font-semibold py-2">{{ $d }}</div>
            @endforeach
        </div>
        <div class="grid grid-cols-7 gap-2">
            @foreach ($days as $day)
                <div class="rounded-xl bg-surface-container-low/40 dark:bg-slate-800/40 border border-surface-container-high dark:border-slate-700 p-2 min-h-[100px] {{ $day->isToday() ? 'ring-2 ring-primary-container' : '' }}">
                    <div class="font-label-md text-label-md {{ $day->isToday() ? 'text-primary font-bold' : 'text-on-surface-variant' }} mb-1">{{ $day->day }}</div>
                    @foreach ($tasks->get($day->format('Y-m-d'), collect()) as $task)
                        <div class="text-xs bg-rose-100 dark:bg-rose-900/40 text-rose-700 dark:text-rose-300 rounded-md px-1.5 py-0.5 mt-1 truncate" title="{{ $task->title }}">{{ $task->title }}</div>
                    @endforeach
                    @foreach ($agendas->get($day->format('Y-m-d'), collect()) as $agenda)
                        <div class="text-xs bg-violet-100 dark:bg-violet-900/40 text-violet-700 dark:text-violet-300 rounded-md px-1.5 py-0.5 mt-1 truncate" title="{{ $agenda->title }}">{{ $agenda->title }}</div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
