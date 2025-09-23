<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Login | {{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
            <script>
                tailwind.config = {
                    theme: {
                        extend: {
                            colors: {
                                primary: '#0f74b3',
                                accent: '#d59a2f',
                                success: '#33a652',
                            },
                        },
                    },
                };
            </script>
        @endif
    </head>
    <body class="relative min-h-screen font-sans text-neutral-100 bg-gradient-to-br from-[#0a4f84] via-[#0b64a4] to-[#0c3d73]">
        {{-- <div class="absolute -top-24 -left-16 h-80 w-80 rounded-full bg-[#d8a42c] blur-[2px] opacity-90"></div> --}}
        {{-- <div class="absolute -bottom-24 -right-8 h-96 w-96 rounded-full bg-[#3ba64b] blur-[3px] opacity-90"></div> --}}
        <div class="absolute top-32 right-1/3 h-64 w-64 rounded-full bg-[#105f9d] opacity-80"></div>

        <div class="relative z-10 flex min-h-screen items-center justify-center px-4 py-12">
            <div class="w-full max-w-md rounded-[28px] bg-white/95 p-10 text-neutral-900 shadow-2xl">
                <div class="mb-6 text-center">
                    <h1 class="text-3xl font-semibold">Disnaker</h1>
                    <p class="text-sm text-neutral-500">Please enter your detail</p>
                </div>
                <form class="space-y-6">
                    <label class="block">
                        <span class="mb-2 block text-sm font-medium text-neutral-600">Email</span>
                        <div class="flex items-center gap-3 rounded-lg border border-neutral-200 bg-white px-4 py-3 shadow-sm focus-within:border-[#0f74b3] focus-within:ring-2 focus-within:ring-[#0f74b3]/30">
                            <input
                                type="email"
                                placeholder="Email"
                                class="w-full border-none bg-transparent text-sm text-neutral-800 focus:outline-none focus:ring-0"
                            />
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 8.25v7.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25v-7.5m19.5 0A2.25 2.25 0 0019.5 6h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.5a2.25 2.25 0 01-2.26 0l-7.5-4.5A2.25 2.25 0 013 8.493V8.25" />
                            </svg>
                        </div>
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-sm font-medium text-neutral-600">Password</span>
                        <div class="flex items-center gap-3 rounded-lg border border-neutral-200 bg-white px-4 py-3 shadow-sm focus-within:border-[#0f74b3] focus-within:ring-2 focus-within:ring-[#0f74b3]/30">
                            <input
                                type="password"
                                placeholder="Password"
                                class="w-full border-none bg-transparent text-sm text-neutral-800 focus:outline-none focus:ring-0"
                                data-password-field
                            />
                            <button type="button" class="text-neutral-400 transition hover:text-[#0f74b3]" data-password-toggle aria-label="Toggle password visibility">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" data-password-icon="hidden">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" data-password-icon="visible">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1 1 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178a1 1 0 010 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                        </div>
                    </label>
                    <div class="flex items-center justify-between text-xs text-neutral-500">
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" class="h-4 w-4 rounded border-neutral-300 text-[#0f74b3] focus:ring-[#0f74b3]" />
                            Remember me
                        </label>
                        <a href="#" class="font-medium text-[#0f74b3] hover:underline">Forgot password?</a>
                    </div>
                    <button type="submit" class="relative w-full overflow-hidden rounded-full px-5 py-3 text-sm font-semibold text-white shadow-lg transition focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-offset-white focus-visible:ring-[#0f74b3]">
                        <span class="absolute inset-0 bg-gradient-to-r from-[#d59a2f] via-[#0f74b3] to-[#33a652]"></span>
                        <span class="absolute inset-y-0 left-0 w-16 -translate-x-8 rounded-r-full bg-[#d59a2f]/30 blur-xl"></span>
                        <span class="absolute inset-y-0 right-0 w-20 translate-x-8 rounded-l-full bg-[#33a652]/30 blur-xl"></span>
                        <span class="relative">Login</span>
                    </button>
                </form>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const toggle = document.querySelector('[data-password-toggle]');
                if (!toggle) return;

                const field = document.querySelector('[data-password-field]');
                const iconHidden = toggle.querySelector('[data-password-icon="hidden"]');
                const iconVisible = toggle.querySelector('[data-password-icon="visible"]');

                toggle.addEventListener('click', () => {
                    if (!field) return;

                    const isPassword = field.type === 'password';
                    field.type = isPassword ? 'text' : 'password';
                    iconHidden.classList.toggle('hidden', !isPassword);
                    iconVisible.classList.toggle('hidden', isPassword);
                });
            });
        </script>
    </body>
</html>
