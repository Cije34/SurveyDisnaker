@props([
    'title' => 'SURVEY',
    'description' => 'Berikan masukan untuk pelatihan kemarin',
    'deadline' => null,
    'mentor' => null,
    'status' => 'pending',
    'action' => '#',
])

@php
    $isCompleted = $status === 'completed';
    $buttonLabel = $isCompleted ? 'Selesai' : 'Survey';
    $buttonIcon = $isCompleted ? null : asset('icons/sign-in.svg');
    $gradientStops = $isCompleted ? '#cbd5f5,#94a3b8,#64748b' : '#016cbd,#0ea5e9,#25a244';
@endphp

<article class="flex flex-col gap-3 rounded-3xl bg-gradient-to-r from-sky-900/90 to-sky-700/80 p-1 shadow-[0_25px_45px_-35px_rgba(15,23,42,0.4)]">
    <div class="flex flex-wrap items-center justify-between gap-4 rounded-3xl bg-white px-6 py-5">
        <div class="space-y-1">
            <h3 class="text-2xl font-bold uppercase tracking-wide text-slate-900">{{ $title }}</h3>
            <p class="text-sm text-slate-600">{{ $description }}</p>
            <div class="mt-3 flex flex-wrap items-center gap-4 text-xs uppercase tracking-wide text-slate-500">
                <span>Berakhir pada: <span class="font-semibold text-slate-700">{{ $deadline ?? '-' }}</span></span>
                <span>Mentor: <span class="font-semibold text-slate-700">{{ $mentor ?? '-' }}</span></span>
            </div>
        </div>

        <a href="{{ $action }}"
           @class([
               'inline-flex items-center gap-3 rounded-full px-6 py-3 text-sm font-semibold shadow-lg transition',
               'text-white' => ! $isCompleted,
               'text-slate-100' => $isCompleted,
               'pointer-events-none opacity-80' => $isCompleted,
           ])
           style="background-image: linear-gradient(to right, {{ $gradientStops }});">
            <span class="text-base font-semibold">{{ $buttonLabel }}</span>
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white/20">
                @if($isCompleted)
                    <span class="text-lg font-semibold">&#10003;</span>
                @else
                    <img src="{{ $buttonIcon }}" alt="{{ $buttonLabel }}" class="h-4 w-4">
                @endif
            </span>
        </a>
    </div>
</article>
