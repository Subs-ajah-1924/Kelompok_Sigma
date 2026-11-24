<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-on-surface dark:text-slate-100 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-surface-container-lowest dark:bg-[#0f172a] shadow sm:rounded-lg border border-outline-variant dark:border-slate-800">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-surface-container-lowest dark:bg-[#0f172a] shadow sm:rounded-lg border border-outline-variant dark:border-slate-800">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-surface-container-lowest dark:bg-[#0f172a] shadow sm:rounded-lg border border-outline-variant dark:border-slate-800">
                <div class="max-w-xl">
                    @include('profile.partials.update-theme-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-surface-container-lowest dark:bg-[#0f172a] shadow sm:rounded-lg border border-outline-variant dark:border-slate-800">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
