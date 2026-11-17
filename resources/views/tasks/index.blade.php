<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center text-primary-container shadow-xs">
                    <span class="material-symbols-outlined text-[24px]">task_alt</span>
                </div>
                <h1 class="font-headline-lg text-headline-lg text-on-surface font-bold tracking-tight">Daftar Tugas</h1>
            </div>
            <a href="{{ route('tasks.create') }}" class="flex items-center gap-2 h-10 px-4 rounded-xl bg-primary-container hover:bg-primary text-on-primary font-label-lg text-label-lg font-medium shadow-sm transition-all">
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
                <a href="{{ route('tasks.create') }}" class="mt-4 inline-flex items-center gap-2 h-10 px-4 rounded-xl bg-primary-container hover:bg-primary text-on-primary font-label-lg text-label-lg font-medium shadow-sm transition-all">
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
