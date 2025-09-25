@props(['active' => 'dashboard', 'user' => null])

<div class="flex h-full flex-col justify-between px-6 py-8">
    <div class="space-y-8">
        <header class="flex items-center gap-3">
            <img src="{{ asset('img/logo_disnaker.svg') }}" alt="Disnaker" class="h-14 w-14 object-contain">
            <div>
                <p class="text-xl font-semibold leading-tight">Disnaker</p>
                <span class="text-xs text-white/80">Kabupaten/Kota</span>
            </div>
        </header>

        <div class="rounded-3xl bg-white/15 px-4 py-5 text-sm shadow-inner shadow-sky-900/10">
            <div class="flex items-center gap-4">
                <img src="{{ asset('icons/user-circle.svg') }}" alt="Profil" class="h-12 w-12 invert">
                <div class="leading-tight max-w-[150px]">
                    <p class="font-medium text-white truncate" title="{{ $user?->name }}">{{ $user?->name }}</p>
                    <p class="text-white/80 text-xs truncate" title="{{ $user?->email }}">{{ $user?->email }}</p>
                </div>
            </div>
        </div>

        <nav class="space-y-2 text-base font-medium">
            @php
                $menus = [
                    [
                        'label' => 'Dashboard',
                        'icon' => asset('icons/list.svg'),
                        'route' => route('dashboard'),
                        'key' => 'dashboard',
                    ],
                    [
                        'label' => 'Jadwal',
                        'icon' => asset('icons/calendar.svg'),
                        'route' => route('peserta.jadwal'),
                        'key' => 'schedule',
                    ],
                    [
                        'label' => 'Survey',
                        'icon' => asset('icons/chalkboard-teacher.svg'),
                        'route' => Route::has('peserta.survey') ? route('peserta.survey') : '#',
                        'key' => 'survey',
                    ],
                ];
            @endphp

            @foreach ($menus as $menu)
                <a href="{{ $menu['route'] }}"
                   @class([
                       'flex items-center gap-3 rounded-2xl px-5 py-3 transition-all duration-200',
                       'bg-white text-sky-700 shadow-lg shadow-sky-900/15' => $active === $menu['key'],
                       'text-white/85 hover:bg-white/15 hover:text-white' => $active !== $menu['key'],
                   ])>
                    <img src="{{ $menu['icon'] }}" alt="{{ $menu['label'] }}" class="h-5 w-5 invert">
                    <span>{{ $menu['label'] }}</span>
                </a>
            @endforeach
        </nav>
    </div>

    <form method="POST" action="{{ route('logout') }}" class="mt-8">
        @csrf
        <button type="submit"
                class="flex w-full items-center justify-center gap-3 rounded-full border border-white/40 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/20">
            <img src="{{ asset('icons/sign-out.svg') }}" alt="Logout" class="h-4 w-4 invert">
            Log out
        </button>
    </form>
</div>
