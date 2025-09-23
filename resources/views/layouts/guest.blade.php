<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased min-h-screen bg-gradient-to-b from-sky-400 via-sky-500 to-blue-900 flex items-center justify-center p-6">
        <div class="w-full max-w-md rounded-[36px] bg-white/95 px-12 py-12 shadow-[0_30px_60px_-25px_rgba(15,23,42,0.65)]">
            {{ $slot }}
        </div>
    </body>
</html>
