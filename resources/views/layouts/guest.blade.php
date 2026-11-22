<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TasKuy') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="preload" as="font" type="font/ttf" crossorigin href="https://fonts.gstatic.com/s/materialsymbolsoutlined/v385/kJF1BvYX7BgnkSrUwT8OhrdQw4oELdPIeeII9v6oDMzByHX9rA6RzaxHMPdY43zj-jCxv3fzvRNU22ZXGJpEpjC_1v-p_4MrImHCIJIZrDCvHOem.ttf">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=block" rel="stylesheet">

        <script>
            (function () {
                var dark = false;
                try {
                    dark = localStorage.getItem('theme') === 'dark';
                } catch (e) {}
                document.documentElement.classList.toggle('dark', dark);
                document.documentElement.style.colorScheme = dark ? 'dark' : 'light';
                function markFontsLoaded() {
                    document.documentElement.classList.add('fonts-loaded');
                }
                if (document.fonts && typeof document.fonts.check === 'function' && document.fonts.check('24px "Material Symbols Outlined"')) {
                    markFontsLoaded();
                } else if (document.fonts && typeof document.fonts.ready !== 'undefined') {
                    document.fonts.ready.then(markFontsLoaded);
                    setTimeout(markFontsLoaded, 3000);
                } else {
                    markFontsLoaded();
                }
            })();
        </script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-background text-on-surface">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="mb-6 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl overflow-hidden shadow-sm flex-shrink-0">
                    <img src="{{ asset('images/logo.webp') }}" alt="TasKuy Logo" class="w-full h-full object-cover">
                </div>
                <span class="font-headline-sm text-headline-sm font-bold text-on-surface">TasKuy</span>
            </div>

            <div class="w-full sm:max-w-md px-6 py-6 rounded-2xl bg-surface-container-lowest border border-surface-container-high shadow-xs">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
