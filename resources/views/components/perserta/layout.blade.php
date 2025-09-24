@props(['active' => 'dashboard', 'user' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard Peserta' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none !important;}</style>
</head>
<body class="min-h-screen bg-slate-100 font-sans antialiased" x-data="{ sidebarOpen: false }" x-on:toggle-sidebar.window="sidebarOpen = !sidebarOpen">
    <div class="relative flex min-h-screen">
        @isset($sidebar)
            <!-- Sidebar desktop -->
            <aside class="hidden w-[280px] bg-gradient-to-b from-sky-700 via-sky-600 to-sky-800 text-white lg:flex lg:flex-col">
                {{ $sidebar }}
            </aside>

            <!-- Sidebar mobile -->
            <div class="lg:hidden" x-cloak>
                <div x-show="sidebarOpen"
                     x-transition.opacity
                     class="fixed inset-0 z-40 bg-slate-900/50"
                     @click="sidebarOpen = false"></div>

                <aside x-show="sidebarOpen"
                       x-on:keydown.escape.window="sidebarOpen = false"
                       x-transition:enter="transition ease-out duration-200"
                       x-transition:enter-start="-translate-x-full opacity-0"
                       x-transition:enter-end="translate-x-0 opacity-100"
                       x-transition:leave="transition ease-in duration-150"
                       x-transition:leave-start="translate-x-0 opacity-100"
                       x-transition:leave-end="-translate-x-full opacity-0"
                       class="fixed inset-y-0 left-0 z-50 w-72 max-w-full bg-gradient-to-b from-sky-700 via-sky-600 to-sky-800 text-white shadow-2xl focus:outline-none"
                       tabindex="0">
                    <div class="h-full overflow-y-auto px-6 py-8">
                        {{ $sidebar }}
                    </div>
                </aside>
            </div>
        @endisset

        <main class="flex min-h-screen flex-1 flex-col bg-white">
            {{ $slot }}
        </main>
    </div>
</body>
</html>
