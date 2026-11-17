<x-app-layout>
    <x-slot name="header">
        <h1 class="font-headline-lg text-headline-lg text-on-surface font-bold tracking-tight">Edit Tugas</h1>
    </x-slot>

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('tasks.update', $task) }}" class="rounded-2xl bg-surface-container-lowest p-6 border border-surface-container-high shadow-xs flex flex-col gap-5">
            @csrf @method('PUT')
            <div class="flex flex-col gap-2">
                <label class="font-label-md text-label-md text-on-surface-variant font-medium">Judul</label>
                <input type="text" name="title" value="{{ old('title', $task->title) }}" class="h-11 px-4 rounded-lg bg-surface-container-low text-on-surface border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary-container focus:border-primary-container font-body-md text-body-md" required>
                @error('title') <p class="font-body-sm text-body-sm text-error">{{ $message }}</p> @enderror
            </div>
            <div class="flex flex-col gap-2">
                <label class="font-label-md text-label-md text-on-surface-variant font-medium">Deskripsi</label>
                <textarea name="description" rows="4" class="px-4 py-3 rounded-lg bg-surface-container-low text-on-surface border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary-container focus:border-primary-container font-body-md text-body-md">{{ old('description', $task->description) }}</textarea>
                @error('description') <p class="font-body-sm text-body-sm text-error">{{ $message }}</p> @enderror
            </div>
            <div class="flex flex-col gap-2">
                <label class="font-label-md text-label-md text-on-surface-variant font-medium">Mata Kuliah</label>
                <select name="course_id" class="h-11 px-4 rounded-lg bg-surface-container-low text-on-surface border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary-container focus:border-primary-container font-body-md text-body-md">
                    <option value="">Tanpa mata kuliah</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id', $task->course_id) == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                    @endforeach
                </select>
                @error('course_id') <p class="font-body-sm text-body-sm text-error">{{ $message }}</p> @enderror
            </div>
            <div class="flex flex-col gap-2">
                <label class="font-label-md text-label-md text-on-surface-variant font-medium">Deadline</label>
                <input type="datetime-local" name="deadline" value="{{ old('deadline', $task->deadline->format('Y-m-d\TH:i')) }}" class="h-11 px-4 rounded-lg bg-surface-container-low text-on-surface border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary-container focus:border-primary-container font-body-md text-body-md" required>
                @error('deadline') <p class="font-body-sm text-body-sm text-error">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex flex-col gap-2">
                    <label class="font-label-md text-label-md text-on-surface-variant font-medium">Tingkat Kesulitan</label>
                    <select name="difficulty" class="h-11 px-4 rounded-lg bg-surface-container-low text-on-surface border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary-container focus:border-primary-container font-body-md text-body-md" required>
                        @foreach (['mudah' => 'Mudah', 'sedang' => 'Sedang', 'sulit' => 'Sulit'] as $value => $label)
                            <option value="{{ $value }}" {{ old('difficulty', $task->difficulty->value) === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('difficulty') <p class="font-body-sm text-body-sm text-error">{{ $message }}</p> @enderror
                </div>
                <div class="flex flex-col gap-2">
                    <label class="font-label-md text-label-md text-on-surface-variant font-medium">Status</label>
                    <select name="status" class="h-11 px-4 rounded-lg bg-surface-container-low text-on-surface border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary-container focus:border-primary-container font-body-md text-body-md">
                        @foreach (['todo' => 'To Do', 'in_progress' => 'In Progress', 'done' => 'Done'] as $value => $label)
                            <option value="{{ $value }}" {{ old('status', $task->status->value) === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status') <p class="font-body-sm text-body-sm text-error">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="h-10 px-5 rounded-xl bg-primary-container hover:bg-primary text-on-primary font-label-lg text-label-lg font-medium shadow-sm transition-all">Simpan</button>
                <a href="{{ route('tasks.show', $task) }}" class="h-10 px-5 rounded-xl bg-surface-container-low hover:bg-surface-container text-on-surface font-label-lg text-label-lg font-medium transition-all flex items-center">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
