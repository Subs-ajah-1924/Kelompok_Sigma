<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center text-primary-container shadow-xs">
                <span class="material-symbols-outlined text-[24px]">view_kanban</span>
            </div>
            <h1 class="font-headline-lg text-headline-lg text-on-surface font-bold tracking-tight">Kanban Board</h1>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach (['todo' => 'To Do', 'in_progress' => 'In Progress', 'done' => 'Done'] as $status => $label)
            <div class="rounded-2xl bg-surface-container-low p-4 border border-surface-container-high flex flex-col gap-3">
                <div class="flex items-center justify-between px-1">
                    <h3 class="font-title-md text-title-md text-on-surface font-semibold">{{ $label }}</h3>
                    <span class="px-2 py-0.5 rounded-full bg-surface-container-high text-on-surface-variant font-label-sm text-label-sm font-semibold">{{ $columns[$status]->count() }}</span>
                </div>
                <div class="flex flex-col gap-3">
                    @forelse ($columns[$status] as $task)
                        <a href="{{ route('tasks.show', $task) }}" class="rounded-xl bg-surface-container-lowest p-4 border border-surface-container-high shadow-xs flex flex-col gap-2 hover:border-primary-container transition-colors">
                            <span class="font-title-md text-title-md text-on-surface font-medium">{{ $task->title }}</span>
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-primary-fixed text-primary font-label-sm text-label-sm font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                                    {{ $task->course->name ?? 'Tanpa MK' }}
                                </span>
                                <span class="font-label-sm text-label-sm text-on-surface-variant">{{ $task->deadline->format('d M') }}</span>
                            </div>
                        </a>
                    @empty
                        <div class="rounded-xl border border-dashed border-outline-variant p-6 text-center text-on-surface-variant font-body-sm text-body-sm">
                            Tidak ada tugas.
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
