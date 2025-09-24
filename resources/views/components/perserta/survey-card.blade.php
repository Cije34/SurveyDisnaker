@props([
    'title' => 'Survey Pelatihan',
    'description' => 'Berikan masukan untuk pelatihan kemarin',
    'action' => '#',
    'cta' => 'Isi Survey',
    'icon' => null,
])

<div class="flex flex-1 flex-col justify-between rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_25px_45px_-35px_rgba(15,23,42,0.35)]">
    <div class="space-y-2">
        @if ($icon)
            <div class="mb-2 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-100 text-sky-600">
                {{ $icon }}
            </div>
        @endif

        <h3 class="text-lg font-semibold text-slate-900">{{ $title }}</h3>
        <p class="text-sm text-slate-500">{{ $description }}</p>
    </div>

    <a href="{{ $action }}"
       class="group relative mt-6 inline-flex items-center justify-between overflow-hidden rounded-full border border-slate-200 bg-white px-3 py-1.5 text-sm font-semibold text-slate-600">
        <span class="relative flex-1 rounded-full bg-gradient-to-r from-[#016cbd] via-[#0ea5e9] to-[#25a244] px-6 py-3 text-center text-white shadow-[0_15px_35px_-25px_rgba(14,165,233,0.95)]">{{ $cta }}</span>
        <span class="absolute inset-y-0 -left-6 h-full w-16 rounded-full bg-[#c2972d] opacity-70 blur-xl"></span>
        <span class="absolute inset-y-0 -right-10 h-full w-24 rounded-full bg-[#25a244] opacity-60 blur-xl"></span>
        <span class="absolute right-4 inline-flex h-7 w-7 items-center justify-center rounded-full bg-white/30">
            <img src="{{ asset('icons/sign-in.svg') }}" alt="Isi" class="h-4 w-4">
        </span>
    </a>
</div>
