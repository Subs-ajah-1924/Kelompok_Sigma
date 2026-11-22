<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center text-primary-container shadow-xs">
                <span class="material-symbols-outlined text-[24px]">notifications</span>
            </div>
            <h1 class="font-headline-lg text-headline-lg text-on-surface font-bold tracking-tight">Notifikasi Deadline</h1>
        </div>
    </x-slot>

    <div class="flex flex-col gap-4">
        @foreach (['overdue' => 'Terlambat', 'today' => 'Hari Ini', 'soon' => '1-3 Hari Lagi', 'upcoming' => '4-7 Hari Lagi', 'later' => 'Lebih dari 7 Hari'] as $key => $label)
            <div class="rounded-2xl bg-surface-container-lowest p-5 border border-surface-container-high shadow-xs flex flex-col gap-3">
                <div class="flex items-center justify-between">
                    <h3 class="font-title-md text-title-md text-on-surface font-semibold">{{ $label }}</h3>
                    @if ($$key->isNotEmpty())
                        <form method="POST" action="{{ route('notifications.markGroupDone', $key) }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-primary-container/10 hover:bg-primary-container/20 text-primary font-label-sm text-label-sm font-semibold transition-all">
                                <span class="material-symbols-outlined text-[16px]">done_all</span>
                                <span>Tandai Selesai</span>
                            </button>
                        </form>
                    @endif
                </div>
                @forelse ($$key as $task)
                    <a href="{{ route('tasks.show', $task) }}" class="flex items-center justify-between p-3 rounded-xl bg-surface-container-low/60 dark:bg-slate-800/60 border border-surface-container-high dark:border-slate-700 hover:border-primary-container transition-colors">
                        <div class="flex flex-col min-w-0">
                            <span class="font-title-md text-title-md text-on-surface font-medium truncate">{{ $task->title }}</span>
                            <span class="font-body-sm text-body-sm text-on-surface-variant">{{ $task->course->name ?? 'Tanpa mata kuliah' }} • {{ $task->deadline->format('d M Y, H:i') }}</span>
                        </div>
                        <span class="material-symbols-outlined text-outline text-[18px]">chevron_right</span>
                    </a>
                @empty
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Tidak ada tugas.</p>
                @endforelse
            </div>
        @endforeach
    </div>
</x-app-layout>
