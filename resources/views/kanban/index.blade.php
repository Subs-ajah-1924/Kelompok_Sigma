<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center text-primary-container shadow-xs">
                <span class="material-symbols-outlined text-[24px]">view_kanban</span>
            </div>
            <h1 class="font-headline-lg text-headline-lg text-on-surface font-bold tracking-tight">Kanban Board</h1>
        </div>
    </x-slot>

    <div id="kanban-board" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach (['todo' => 'To Do', 'in_progress' => 'In Progress', 'done' => 'Done'] as $status => $label)
            <section class="rounded-2xl bg-surface-container-low p-4 border border-surface-container-high flex flex-col gap-3" aria-labelledby="kanban-{{ $status }}">
                <div class="flex items-center justify-between px-1">
                    <h3 id="kanban-{{ $status }}" class="font-title-md text-title-md text-on-surface font-semibold">{{ $label }}</h3>
                    <span class="kanban-count px-2 py-0.5 rounded-full bg-surface-container-high text-on-surface-variant font-label-sm text-label-sm font-semibold">{{ $columns[$status]->count() }}</span>
                </div>
                <div class="kanban-column flex flex-col gap-3 min-h-20" data-status="{{ $status }}" aria-label="Tugas {{ $label }}">
                    @forelse ($columns[$status] as $task)
                        <article class="kanban-card rounded-xl bg-surface-container-lowest p-4 border border-surface-container-high shadow-xs flex flex-col gap-2 hover:border-primary-container transition-colors" data-task-id="{{ $task->id }}">
                            <a href="{{ route('tasks.show', $task) }}" class="font-title-md text-title-md text-on-surface font-medium">{{ $task->title }}</a>
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-primary-fixed text-primary font-label-sm text-label-sm font-medium"><span class="w-1.5 h-1.5 rounded-full bg-primary"></span>{{ $task->course->name ?? 'Tanpa MK' }}</span>
                                <span class="font-label-sm text-label-sm text-on-surface-variant">{{ $task->deadline->format('d M') }}</span>
                            </div>
                            <form method="POST" action="{{ route('tasks.status', $task) }}" class="flex items-center gap-2 pt-1">
                                @csrf @method('PATCH')
                                <label class="sr-only" for="status-{{ $task->id }}">Ubah status {{ $task->title }}</label>
                                <select id="status-{{ $task->id }}" name="status" class="min-w-0 flex-1 rounded-lg border-surface-container-high text-xs" aria-label="Status {{ $task->title }}">
                                    @foreach (['todo' => 'To Do', 'in_progress' => 'In Progress', 'done' => 'Done'] as $option => $optionLabel)
                                        <option value="{{ $option }}" @selected($task->status->value === $option)>{{ $optionLabel }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="rounded-lg bg-primary px-2 py-1 text-xs font-semibold text-white">Ubah</button>
                            </form>
                        </article>
                    @empty
                        <div class="kanban-empty rounded-xl border border-dashed border-outline-variant p-6 text-center text-on-surface-variant font-body-sm text-body-sm">Tidak ada tugas.</div>
                    @endforelse
                </div>
            </section>
        @endforeach
    </div>
</x-app-layout>
