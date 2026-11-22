<x-app-layout>
    <x-slot name="header">
        <h1 class="font-headline-lg text-headline-lg text-on-surface font-bold tracking-tight">Edit Mata Kuliah</h1>
    </x-slot>

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('courses.update', $course) }}" class="rounded-2xl bg-surface-container-lowest p-6 border border-surface-container-high shadow-xs flex flex-col gap-5">
            @csrf @method('PUT')
            <div class="flex flex-col gap-2">
                <label class="font-label-md text-label-md text-on-surface-variant font-medium">Nama</label>
                <input type="text" name="name" value="{{ old('name', $course->name) }}" class="h-11 px-4 rounded-lg bg-surface-container-low text-on-surface border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary-container focus:border-primary-container font-body-md text-body-md" required>
                @error('name') <p class="font-body-sm text-body-sm text-error">{{ $message }}</p> @enderror
            </div>
            <div class="flex flex-col gap-2">
                <label class="font-label-md text-label-md text-on-surface-variant font-medium">Kode</label>
                <input type="text" name="code" value="{{ old('code', $course->code) }}" class="h-11 px-4 rounded-lg bg-surface-container-low text-on-surface border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary-container focus:border-primary-container font-body-md text-body-md">
                @error('code') <p class="font-body-sm text-body-sm text-error">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex flex-col gap-2">
                    <label class="font-label-md text-label-md text-on-surface-variant font-medium">Hari</label>
                    <select name="day" class="h-11 px-4 rounded-lg bg-surface-container-low text-on-surface border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary-container focus:border-primary-container font-body-md text-body-md">
                        <option value="">Pilih Hari</option>
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $day)
                            <option value="{{ $day }}" {{ old('day', $course->day) == $day ? 'selected' : '' }}>{{ $day }}</option>
                        @endforeach
                    </select>
                    @error('day') <p class="font-body-sm text-body-sm text-error">{{ $message }}</p> @enderror
                </div>
                <div class="flex flex-col gap-2">
                    <label class="font-label-md text-label-md text-on-surface-variant font-medium">Jam Kuliah</label>
                    <div class="flex items-center gap-2">
                        <input type="time" name="start_time" class="h-11 px-3 rounded-lg bg-surface-container-low text-on-surface border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary-container focus:border-primary-container font-body-md text-body-md">
                        <span class="text-on-surface-variant font-medium">s/d</span>
                        <input type="time" name="end_time" class="h-11 px-3 rounded-lg bg-surface-container-low text-on-surface border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary-container focus:border-primary-container font-body-md text-body-md">
                        <input type="hidden" name="time" id="time_combined" value="{{ old('time', $course->time) }}">
                    </div>
                    @error('time') <p class="font-body-sm text-body-sm text-error">{{ $message }}</p> @enderror
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const startInput = document.querySelector('input[name="start_time"]');
                        const endInput = document.querySelector('input[name="end_time"]');
                        const combinedInput = document.getElementById('time_combined');

                        const existingVal = combinedInput.value;
                        if (existingVal && existingVal.includes('-')) {
                            const parts = existingVal.split('-').map(s => s.trim());
                            if (parts[0]) startInput.value = parts[0];
                            if (parts[1]) endInput.value = parts[1];
                        } else if (existingVal) {
                            startInput.value = existingVal.trim();
                        }

                        function updateTime() {
                            if (startInput.value && endInput.value) {
                                combinedInput.value = startInput.value + ' - ' + endInput.value;
                            } else if (startInput.value) {
                                combinedInput.value = startInput.value;
                            } else {
                                combinedInput.value = '';
                            }
                        }

                        startInput.addEventListener('change', updateTime);
                        endInput.addEventListener('change', updateTime);
                    });
                </script>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex flex-col gap-2">
                    <label class="font-label-md text-label-md text-on-surface-variant font-medium">SKS</label>
                    <input type="number" name="sks" value="{{ old('sks', $course->sks) }}" min="1" max="10" placeholder="Contoh: 3" class="h-11 px-4 rounded-lg bg-surface-container-low text-on-surface border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary-container focus:border-primary-container font-body-md text-body-md placeholder:text-outline">
                    @error('sks') <p class="font-body-sm text-body-sm text-error">{{ $message }}</p> @enderror
                </div>
                <div class="flex flex-col gap-2">
                    <label class="font-label-md text-label-md text-on-surface-variant font-medium">Dosen Pengajar</label>
                    <input type="text" name="lecturer" value="{{ old('lecturer', $course->lecturer) }}" placeholder="Nama Dosen & Gelar" class="h-11 px-4 rounded-lg bg-surface-container-low text-on-surface border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary-container focus:border-primary-container font-body-md text-body-md placeholder:text-outline">
                    @error('lecturer') <p class="font-body-sm text-body-sm text-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <label class="font-label-md text-label-md text-on-surface-variant font-medium">Warna</label>
                <input type="color" name="color" value="{{ old('color', $course->color ?? '#4F46E5') }}" class="h-11 w-24 rounded-lg bg-surface-container-low border border-outline-variant cursor-pointer">
                @error('color') <p class="font-body-sm text-body-sm text-error">{{ $message }}</p> @enderror
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="h-10 px-5 rounded-xl bg-primary-container hover:bg-primary text-on-primary font-label-lg text-label-lg font-medium shadow-sm transition-all">Simpan</button>
                <a href="{{ route('courses.index') }}" class="h-10 px-5 rounded-xl bg-surface-container-low hover:bg-surface-container text-on-surface font-label-lg text-label-lg font-medium transition-all flex items-center">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
