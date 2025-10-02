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
    $isClosed = $status === 'closed';
    $buttonLabel = $isClosed ? 'Ditutup' : ($isCompleted ? 'Selesai' : 'Survey');
    $buttonIcon = ($isClosed || $isCompleted) ? null : asset('icons/sign-in.svg');
@endphp

<article class="flex flex-col gap-4 rounded-3xl border border-slate-200 bg-white px-6 py-5 shadow-[0_20px_40px_-32px_rgba(15,23,42,0.35)]">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="space-y-1">
            <h3 class="text-2xl font-bold uppercase tracking-wide text-slate-900">{{ $title }}</h3>
            
            <div class="mt-3 flex flex-wrap items-center gap-4 text-xs uppercase tracking-wide text-slate-500">
                <span>Berakhir pada: <span class="font-semibold text-slate-700">{{ $deadline ?? '-' }}</span></span>
                <span>Mentor: <span class="font-semibold text-slate-700">{{ $mentor ?? '-' }}</span></span>
            </div>
        </div>

        @if ($action)
            <a href="{{ $action }}"
               @class([
                   'inline-flex items-center gap-3 rounded-full px-6 py-3 text-sm font-semibold transition',
                   'text-white bg-sky-600 hover:bg-sky-700 shadow-lg' => ! $isCompleted,
                   'text-slate-500 bg-slate-200 cursor-default' => $isCompleted,
               ])>
                <span class="text-base font-semibold">{{ $buttonLabel }}</span>
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white/20">
                    @if($isCompleted)
                        <span class="text-lg font-semibold">&#10003;</span>
                    @else
                        <img src="{{ $buttonIcon }}" alt="{{ $buttonLabel }}" class="h-4 w-4">
                    @endif
                </span>
            </a>
        @else
            <span class="inline-flex items-center gap-3 rounded-full bg-slate-200 px-6 py-3 text-sm font-semibold text-slate-500">
                <span class="text-base font-semibold">{{ $buttonLabel }}</span>
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white/20">
                    <span class="text-lg font-semibold">&#10005;</span>
                </span>
            </span>
        @endif
    </div>
</article>
