@props(['user' => null, 'title' => 'Dashboard Admin'])

<header class="border-b border-slate-200 bg-white px-6 py-4 lg:px-8 lg:py-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-semibold text-slate-900">{{ $title }}</h1>
            <p class="mt-1 text-sm text-slate-500">Halo {{ $user?->name ?? 'Administrator' }}, selamat datang kembali.</p>
        </div>

        <button type="button"
                class="flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 hover:bg-slate-100 lg:hidden"
                @click="$dispatch('toggle-sidebar')">
            <img src="{{ asset('icons/list.svg') }}" alt="Menu" class="h-5 w-5">
        </button>
    </div>
</header>
