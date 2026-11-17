<x-app-layout>
    <x-slot name="header">
        <h1 class="font-headline-lg text-headline-lg text-on-surface font-bold tracking-tight">Detail Tugas</h1>
    </x-slot>

    <div class="max-w-2xl">
        <div class="rounded-2xl bg-surface-container-lowest p-6 border border-surface-container-high shadow-xs flex flex-col gap-4">
            <h2 class="font-headline-md text-headline-md text-on-surface font-semibold">{{ $task->title }}</h2>
            @if ($task->description)
                <p class="font-body-md text-body-md text-on-surface-variant">{{ $task->description }}</p>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div class="flex flex-col gap-1">
                    <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Mata Kuliah</span>
                    <span class="font-body-md text-body-md text-on-surface">{{ $task->course->name ?? 'Tanpa mata kuliah' }}</span>
                </div>
                <div class="flex flex-col gap-1">
                    <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Deadline</span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-surface-container-high text-on-surface font-label-sm text-label-sm font-medium w-fit">{{ $task->deadline->format('d M Y, H:i') }}</span>
                </div>
                <div class="flex flex-col gap-1">
                    <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Kesulitan</span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-primary-fixed text-primary font-label-sm text-label-sm font-medium w-fit">{{ $task->difficulty->label() }}</span>
                </div>
                <div class="flex flex-col gap-1">
                    <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Status</span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-surface-container-high text-on-surface font-label-sm text-label-sm font-medium w-fit">
                        <span class="w-2 h-2 rounded-full bg-primary-container"></span>
                        {{ $task->status->label() }}
                    </span>
                </div>
                <div class="flex flex-col gap-1">
                    <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Prioritas</span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-primary-fixed text-primary font-label-sm text-label-sm font-medium w-fit">{{ $recommendation->priority->label() }}</span>
                </div>
            </div>

            <div class="pt-2 border-t border-surface-container-high">
                <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Alasan Rekomendasi</span>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1">{{ $recommendation->reason }}</p>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <a href="{{ route('tasks.edit', $task) }}" class="h-10 px-5 rounded-xl bg-primary-container hover:bg-primary text-on-primary font-label-lg text-label-lg font-medium shadow-sm transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">edit</span>
                    <span>Edit</span>
                </a>
                <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Hapus tugas ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="h-10 px-5 rounded-xl bg-error-container/60 hover:bg-error-container text-on-error-container font-label-lg text-label-lg font-medium transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">delete</span>
                        <span>Hapus</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
