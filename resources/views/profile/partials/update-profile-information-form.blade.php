<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-slate-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-slate-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data" x-data="{ avatarPreview: '{{ $user->avatar ? asset('storage/' . $user->avatar) : '' }}', userName: '{{ $user->name }}' }">
        @csrf
        @method('patch')

        <div class="flex items-center gap-4 mb-4">
            <template x-if="avatarPreview">
                <img :src="avatarPreview" alt="Avatar" class="w-16 h-16 rounded-full object-cover border border-gray-300 dark:border-slate-700">
            </template>
            <template x-if="!avatarPreview">
                <div class="w-16 h-16 rounded-full bg-indigo-100 dark:bg-slate-700 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold text-xl" x-text="userName.charAt(0).toUpperCase()">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            </template>
            <div class="flex-1">
                <x-input-label for="avatar" :value="__('Avatar')" class="dark:text-slate-300" />
                <input id="avatar" name="avatar" type="file" accept="image/*" @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = e => avatarPreview = e.target.result; reader.readAsDataURL(file); }" class="mt-1 block w-full text-sm text-gray-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-slate-700 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-slate-600" />
                <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                @if ($user->avatar)
                    <label class="mt-2 flex items-center gap-2 text-sm text-red-600 dark:text-rose-400 cursor-pointer">
                        <input type="checkbox" name="remove_avatar" value="1" @change="if($el.checked) avatarPreview = ''; else avatarPreview = '{{ asset('storage/' . $user->avatar) }}';" class="rounded border-gray-300 dark:border-slate-700 text-red-600 shadow-sm focus:ring-red-500">
                        <span>Hapus foto profil</span>
                    </label>
                @endif
            </div>
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" class="dark:text-slate-300" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" class="dark:text-slate-300" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full dark:bg-slate-800 dark:border-slate-700 dark:text-slate-100" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-slate-400">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-slate-200 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-emerald-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="dark:bg-indigo-600 dark:hover:bg-indigo-500">{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-slate-400"
                >{{ __('Berhasil disimpan.') }}</p>
            @endif
        </div>
    </form>
</section>
