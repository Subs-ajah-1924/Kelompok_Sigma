<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center text-primary-container shadow-xs">
                    <span class="material-symbols-outlined text-[24px]">auto_stories</span>
                </div>
                <h1 class="font-headline-lg text-headline-lg text-on-surface font-bold tracking-tight">Mata Kuliah</h1>
            </div>
            <a href="{{ route('courses.create') }}" class="flex items-center gap-2 h-10 px-4 rounded-xl bg-primary-container hover:bg-primary text-on-primary font-label-lg text-label-lg font-medium shadow-sm transition-all">
                <span class="material-symbols-outlined text-[18px]">add</span>
                <span>Tambah Mata Kuliah</span>
            </a>
        </div>
    </x-slot>

    <div class="flex flex-col gap-4">
        @forelse ($courses as $course)
            <div class="rounded-2xl bg-surface-container-lowest p-5 border border-surface-container-high shadow-xs flex items-center justify-between gap-4 hover:border-primary-container transition-colors">
                <div class="flex items-center gap-4 min-w-0">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background-color: {{ $course->color ?? '#4F46E5' }}20; color: {{ $course->color ?? '#4F46E5' }}">
                        <span class="material-symbols-outlined text-[20px]">menu_book</span>
                    </div>
                    <div class="min-w-0">
                        <div class="font-title-md text-title-md text-on-surface font-semibold truncate">{{ $course->name }}</div>
                        <div class="font-body-sm text-body-sm text-on-surface-variant flex flex-wrap items-center gap-2 mt-0.5">
                            @if($course->code)<span>{{ $course->code }}</span> • @endif
                            <span>{{ $course->tasks_count }} tugas</span>
                            @if($course->sks) • <span>{{ $course->sks }} SKS</span> @endif
                            @if($course->day || $course->time) • <span>{{ $course->day }} {{ $course->time }}</span> @endif
                            @if($course->lecturer) • <span>{{ $course->lecturer }}</span> @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('courses.edit', $course) }}" class="p-2 rounded-lg text-outline hover:text-on-surface hover:bg-surface-container transition-colors" aria-label="Edit">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                    </a>
                    <form method="POST" action="{{ route('courses.destroy', $course) }}" onsubmit="return confirm('Hapus mata kuliah ini? Tugas terkait tidak terhapus, hanya kehilangan label mata kuliah.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 rounded-lg text-outline hover:text-error hover:bg-error-container/40 transition-colors" aria-label="Hapus">
                            <span class="material-symbols-outlined text-[18px]">delete</span>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="rounded-2xl bg-surface-container-lowest p-10 border border-surface-container-high shadow-xs text-center">
                <span class="material-symbols-outlined text-[48px] text-outline">auto_stories</span>
                <p class="mt-3 font-body-md text-body-md text-on-surface-variant">Belum ada mata kuliah.</p>
                <a href="{{ route('courses.create') }}" class="mt-4 inline-flex items-center gap-2 h-10 px-4 rounded-xl bg-primary-container hover:bg-primary text-on-primary font-label-lg text-label-lg font-medium shadow-sm transition-all">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    <span>Tambah sekarang</span>
                </a>
            </div>
        @endforelse
    </div>
</x-app-layout>
