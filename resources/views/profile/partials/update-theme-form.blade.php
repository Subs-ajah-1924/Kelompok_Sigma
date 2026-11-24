<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Pengaturan Tema Warna') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Pilih warna tema utama aplikasi TasKuy sesuai preferensi Anda.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.theme') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
            @php
                $themes = [
                    'indigo' => ['name' => 'Indigo (Default)', 'bg' => 'bg-indigo-600'],
                    'rose' => ['name' => 'Rose', 'bg' => 'bg-rose-600'],
                    'emerald' => ['name' => 'Emerald', 'bg' => 'bg-emerald-800'],
                    'amber' => ['name' => 'Amber', 'bg' => 'bg-amber-600'],
                    'sky' => ['name' => 'Sky', 'bg' => 'bg-sky-600'],
                ];
                $currentColor = auth()->user()->theme_color ?? 'indigo';
            @endphp

            @foreach ($themes as $key => $theme)
                <label class="relative flex flex-col items-center p-4 border rounded-xl cursor-pointer hover:bg-gray-50 transition {{ $currentColor === $key ? 'border-primary ring-2 ring-primary/20 bg-primary/5' : 'border-gray-200' }}">
                    <input type="radio" name="theme_color" value="{{ $key }}" class="sr-only" {{ $currentColor === $key ? 'checked' : '' }} onchange="this.form.submit()">
                    <span class="w-8 h-8 rounded-full {{ $theme['bg'] }} mb-2 shadow-inner"></span>
                    <span class="text-sm font-medium text-gray-900">{{ $theme['name'] }}</span>
                </label>
            @endforeach
        </div>

        @if (session('status') === 'theme-updated' || session('success'))
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-green-600"
            >{{ __('Tersimpan.') }}</p>
        @endif
    </form>
</section>
