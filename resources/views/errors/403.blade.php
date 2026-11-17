<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'TasKuy') }} - 403</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-900 min-h-screen flex items-center justify-center">
        <div class="text-center">
            <h1 class="text-6xl font-bold text-rose-600 mb-4">403</h1>
            <p class="text-gray-600 mb-6">Anda tidak memiliki akses ke halaman ini.</p>
            <a href="{{ url('/') }}" class="text-indigo-600 hover:underline">Kembali</a>
        </div>
    </body>
</html>
