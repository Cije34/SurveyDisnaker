<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4 rounded-full bg-white/70 px-5 py-3 text-center text-sm text-slate-600" :status="session('status')" />

    <h1 class="text-3xl font-semibold text-center text-slate-900">Disnaker Administrator</h1>
    <p class="mt-1 text-center text-slate-500">Please enter your detail</p>

    <form class="mt-10 space-y-6" method="POST" action="{{ route('login') }}" x-data="{ showPassword: false }">
        @csrf

        <!-- Email Address -->
        <div>
            <div class="relative">
                <x-text-input id="email"
                              name="email"
                              type="email"
                              placeholder="Email"
                              autocomplete="username"
                              :value="old('email')"
                              required
                              class="pl-6 pr-12" />

                <span class="pointer-events-none absolute inset-y-0 right-5 flex items-center text-sky-400">
                    <img src="{{ asset('icons/envelope.svg') }}" alt="" class="h-5 w-5">
                </span>
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-500" />
        </div>

        <!-- Password -->
        <div>
            <div class="relative">
                <x-text-input id="password"
                              name="password"
                              type="password"
                              x-bind:type="showPassword ? 'text' : 'password'"
                              placeholder="Password"
                              autocomplete="current-password"
                              required
                              class="pl-6 pr-12" />

                <button type="button"
                        class="absolute inset-y-0 right-5 flex items-center text-sky-400"
                        @click="showPassword = !showPassword">
                    <img x-show="!showPassword" x-cloak src="{{ asset('icons/eye.svg') }}" alt="" class="h-5 w-5">
                    <img x-show="showPassword" x-cloak src="{{ asset('icons/eye-slash.svg') }}" alt="" class="h-5 w-5">
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-500" />
        </div>

        <input type="hidden" name="role" value="{{ old('role','admin' ) }}">

        <!-- Remember Me & Forgot -->
        <div class="flex items-center justify-between text-sm text-slate-500">
            <label for="remember_me" class="flex items-center gap-3 cursor-pointer">
                <input id="remember_me" type="checkbox" class="h-4 w-4 rounded-full border-slate-300 text-sky-500 focus:ring-sky-400" name="remember">
                <span>{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="font-medium text-sky-500 hover:text-sky-600" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <x-gradient-button class="mt-4">
            {{ __('Login') }}
        </x-gradient-button>
    </form>
</x-guest-layout>
