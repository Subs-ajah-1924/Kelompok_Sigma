<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p>{{ $message }}</p>

                    <p class="mt-4 text-sm text-gray-500">
                        Controller dan route sudah aktif. Implementasi data, form, dan
                        tampilan final akan ditambahkan pada tahap berikutnya.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
