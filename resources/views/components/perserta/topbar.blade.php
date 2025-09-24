@props(['user' => null])

<header class="flex items-center justify-between border-b border-slate-200 bg-white px-8 py-6">
    <div class="flex items-center gap-4">
        <button type="button" class="flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 hover:bg-slate-100 lg:hidden" @click="$dispatch('toggle-sidebar')">
            <img src="{{ asset('icons/list.svg') }}" alt="Menu" class="h-5 w-5">
        </button>

        <div>
            <h1 class="text-3xl font-semibold text-slate-900">Halo {{ $user?->name ?? 'Peserta' }}</h1>
            <p class="mt-1 text-sm text-slate-500">Selamat datang kembali di portal Disnaker</p>
        </div>
    </div>

    <div class="flex items-center gap-4">
        <button type="button" class="flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 hover:bg-slate-100">
            <img src="{{ asset('icons/bell.svg') }}" alt="Notifikasi" class="h-5 w-5">
        </button>

        <div class="rounded-full border border-slate-200 px-4 py-2">
            <div class="leading-tight text-right">
                <p class="text-sm font-medium text-slate-700">{{ $user?->name }}</p>
                <p class="text-xs text-slate-400">{{ $user?->email }}</p>
            </div>
        </div>
    </div>
</header>
