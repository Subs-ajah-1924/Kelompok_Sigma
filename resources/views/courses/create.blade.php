<x-app-layout>
    <x-slot name="header">
        <h1 class="font-headline-lg text-headline-lg text-on-surface font-bold tracking-tight">Tambah Mata Kuliah</h1>
    </x-slot>

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('courses.store') }}" class="rounded-2xl bg-surface-container-lowest p-6 border border-surface-container-high shadow-xs flex flex-col gap-5">
            @csrf
            <div class="flex flex-col gap-2">
                <label class="font-label-md text-label-md text-on-surface-variant font-medium">Nama</label>
                <input type="text" name="name" value="{{ old('name') }}" class="h-11 px-4 rounded-lg bg-surface-container-low text-on-surface border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary-container focus:border-primary-container font-body-md text-body-md placeholder:text-outline" required>
                @error('name') <p class="font-body-sm text-body-sm text-error">{{ $message }}</p> @enderror
            </div>
            <div class="flex flex-col gap-2">
                <label class="font-label-md text-label-md text-on-surface-variant font-medium">Kode</label>
                <input type="text" name="code" value="{{ old('code') }}" class="h-11 px-4 rounded-lg bg-surface-container-low text-on-surface border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary-container focus:border-primary-container font-body-md text-body-md placeholder:text-outline">
                @error('code') <p class="font-body-sm text-body-sm text-error">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex flex-col gap-2">
                    <label class="font-label-md text-label-md text-on-surface-variant font-medium">Hari</label>
                    <select name="day" class="h-11 px-4 rounded-lg bg-surface-container-low text-on-surface border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary-container focus:border-primary-container font-body-md text-body-md">
                        <option value="">Pilih Hari</option>
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $day)
                            <option value="{{ $day }}" {{ old('day') == $day ? 'selected' : '' }}>{{ $day }}</option>
                        @endforeach
                    </select>
                    @error('day') <p class="font-body-sm text-body-sm text-error">{{ $message }}</p> @enderror
                </div>
                <div class="flex flex-col gap-2">
                    <label class="font-label-md text-label-md text-on-surface-variant font-medium">Jam Kuliah</label>
                    <div class="flex items-center gap-2" data-time-fields>
                        <input type="time" name="start_time" value="{{ old('start_time') }}" class="h-11 px-3 rounded-lg bg-surface-container-low text-on-surface border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary-container focus:border-primary-container font-body-md text-body-md">
                        <span class="text-on-surface-variant font-medium">s/d</span>
                        <input type="time" name="end_time" value="{{ old('end_time') }}" class="h-11 px-3 rounded-lg bg-surface-container-low text-on-surface border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary-container focus:border-primary-container font-body-md text-body-md">
                        <input type="hidden" name="time" id="time_combined" value="{{ old('time') }}">
                    </div>
                    @error('time') <p class="font-body-sm text-body-sm text-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex flex-col gap-2">
                    <label class="font-label-md text-label-md text-on-surface-variant font-medium">SKS</label>
                    <input type="number" name="sks" value="{{ old('sks') }}" min="1" max="10" placeholder="Contoh: 3" class="h-11 px-4 rounded-lg bg-surface-container-low text-on-surface border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary-container focus:border-primary-container font-body-md text-body-md placeholder:text-outline">
                    @error('sks') <p class="font-body-sm text-body-sm text-error">{{ $message }}</p> @enderror
                </div>
                <div class="flex flex-col gap-2">
                    <label class="font-label-md text-label-md text-on-surface-variant font-medium">Dosen Pengajar</label>
                    <input type="text" name="lecturer" value="{{ old('lecturer') }}" placeholder="Nama Dosen & Gelar" class="h-11 px-4 rounded-lg bg-surface-container-low text-on-surface border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary-container focus:border-primary-container font-body-md text-body-md placeholder:text-outline">
                    @error('lecturer') <p class="font-body-sm text-body-sm text-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <label class="font-label-md text-label-md text-on-surface-variant font-medium">Warna</label>
                <input type="color" name="color" value="{{ old('color', '#4F46E5') }}" class="h-11 w-24 rounded-lg bg-surface-container-low border border-outline-variant cursor-pointer">
                @error('color') <p class="font-body-sm text-body-sm text-error">{{ $message }}</p> @enderror
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="h-10 px-5 rounded-xl bg-primary hover:bg-primary text-white font-label-lg text-label-lg font-medium shadow-sm transition-all">Simpan</button>
                <a href="{{ route('courses.index') }}" class="h-10 px-5 rounded-xl bg-surface-container-low hover:bg-surface-container text-on-surface font-label-lg text-label-lg font-medium transition-all flex items-center">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
