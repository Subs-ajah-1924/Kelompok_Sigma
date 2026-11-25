<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center text-primary-container shadow-xs">
                    <span class="material-symbols-outlined text-[24px]">task_alt</span>
                </div>
                <h1 class="font-headline-lg text-headline-lg text-on-surface font-bold tracking-tight">Daftar Tugas</h1>
            </div>
            <a href="{{ route('tasks.create') }}" class="flex items-center gap-2 h-10 px-4 rounded-xl bg-primary hover:bg-primary text-white font-label-lg text-label-lg font-medium shadow-sm transition-all">
                <span class="material-symbols-outlined text-[18px]">add</span>
                <span>Tambah Tugas</span>
            </a>
        </div>
    </x-slot>

    <div class="rounded-2xl bg-surface-container-lowest p-6 border border-surface-container-high shadow-xs flex flex-col gap-5">
        @if ($tasks->isEmpty())
            <div class="text-center py-10">
                <span class="material-symbols-outlined text-[48px] text-outline">task_alt</span>
                <p class="mt-3 font-body-md text-body-md text-on-surface-variant">Belum ada tugas.</p>
                <a href="{{ route('tasks.create') }}" class="mt-4 inline-flex items-center gap-2 h-10 px-4 rounded-xl bg-primary hover:bg-primary text-white font-label-lg text-label-lg font-medium shadow-sm transition-all">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    <span>Tambah sekarang</span>
                </a>
            </div>
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
                        @foreach ($tasks as $task)
                            <tr class="hover:bg-surface-container-low/40 transition-colors group">
                                <td class="py-3.5 px-4 font-semibold text-on-surface">
                                    <a href="{{ route('tasks.show', $task) }}" class="group-hover:text-primary transition-colors">{{ $task->title }}</a>
                                </td>
                                 <td class="py-3.5 px-4">
                                     @if ($task->course)
                                         <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full font-label-sm text-label-sm font-medium" style="background-color: {{ $task->course->color ?? '#4F46E5' }}20; color: {{ $task->course->color ?? '#4F46E5' }}">
                                             <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $task->course->color ?? '#4F46E5' }}"></span>
                                             {{ $task->course->name }}
                                         </span>
                                     @else
                                         <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-surface-container-high text-on-surface-variant font-label-sm text-label-sm font-medium">
                                             Tanpa mata kuliah
                                         </span>
                                     @endif
                                 </td>
                                <td class="py-3.5 px-4 text-on-surface-variant font-label-md text-label-md">{{ $task->deadline->format('d M Y, H:i') }}</td>
                                <td class="py-3.5 px-4">
                                     @php
                                         $difficultyClass = match ($task->difficulty->value) {
                                             'mudah' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300',
                                             'sedang' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
                                             'sulit' => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
                                         };
                                     @endphp
                                     <span class="px-2.5 py-1 rounded-full {{ $difficultyClass }} font-label-sm text-label-sm font-semibold">{{ $task->difficulty->label() }}</span>
                                </td>
                                 <td class="py-3.5 px-4">
                                     @php
                                         $statusClass = match ($task->status->value) {
                                             'todo' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
                                             'in_progress' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
                                             'done' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300',
                                         };
                                     @endphp
                                     @if ($task->status->value === 'done')
                                         <span class="px-2.5 py-1 rounded-full {{ $statusClass }} font-label-sm text-label-sm font-medium flex items-center gap-1.5 w-fit">
                                             <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                             {{ $task->status->label() }}
                                         </span>
                                     @else
                                         <form method="POST" action="{{ route('tasks.status', $task) }}" class="inline-flex">
                                             @csrf
                                             @method('PATCH')
                                             <label class="sr-only" for="task-status-{{ $task->id }}">Ubah status {{ $task->title }}</label>
                                             <select id="task-status-{{ $task->id }}" name="status" onchange="this.form.submit()" class="appearance-none cursor-pointer rounded-full border-0 {{ $statusClass }} py-1 pl-2.5 pr-7 font-label-sm text-label-sm font-medium focus:ring-2 focus:ring-primary-container">
                                                 <option value="todo" {{ $task->status->value === 'todo' ? 'selected' : '' }}>To Do</option>
                                                 <option value="in_progress" {{ $task->status->value === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                 <option value="done">Done</option>
                                             </select>
                                         </form>
                                     @endif
                                 </td>
                                <td class="py-3.5 px-4 text-right">
                                    <a href="{{ route('tasks.edit', $task) }}" class="p-1.5 rounded-lg text-outline hover:text-on-surface hover:bg-surface-container transition-colors inline-block" aria-label="Edit">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </a>
                                    <form method="POST" action="{{ route('tasks.destroy', $task) }}" class="inline" onsubmit="return confirm('Hapus tugas ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-outline hover:text-error hover:bg-error-container/40 transition-colors" aria-label="Hapus">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pt-2">
                {{ $tasks->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
