@props(['user' => null])

@php
    $mobileEmail = $user?->email ? \Illuminate\Support\Str::limit($user->email, 13, '') : null;
@endphp

<header class="border-b border-slate-200 bg-white px-6 py-4 lg:px-8 lg:py-6">
    <!-- Mobile header -->
    <div class="flex items-center gap-3 lg:hidden">
        <button type="button"
                class="flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 hover:bg-slate-100"
                @click="$dispatch('toggle-sidebar')">
            <img src="{{ asset('icons/list.svg') }}" alt="Menu" class="h-5 w-5">
        </button>

        <div class="flex-1 leading-tight">
            <h1 class="text-2xl font-semibold text-slate-900 truncate">Halo {{ $user?->name ?? 'Peserta' }}</h1>
            @if ($mobileEmail)
                <p class="mt-1 text-xs text-slate-500">{{ $mobileEmail }}</p>
            @else
                <p class="mt-1 text-xs text-slate-500">Selamat datang kembali di portal Disnaker</p>
            @endif
        </div>
    </div>

    <!-- Desktop header -->
    <div class="hidden items-center justify-between lg:flex">
        <div>
            <h1 class="text-4xl font-semibold text-slate-900">Halo {{ $user?->name ?? 'Peserta' }}</h1>
            <p class="mt-1 text-sm text-slate-500">Selamat datang kembali di portal Disnaker</p>
        </div>
    </div>
</header>
