@props(['type' => 'submit'])

@php
    $baseClasses = 'relative h-14 w-full overflow-hidden rounded-full text-lg font-semibold text-white shadow-[0_25px_45px_-25px_rgba(14,165,233,0.95)] focus:outline-none focus:ring-4 focus:ring-sky-200';
@endphp

<button {{ $attributes->merge(['type' => $type, 'class' => $baseClasses]) }}>
    <span class="absolute inset-0 bg-gradient-to-r from-[#c3972f] via-[#0ea5e9] to-[#22c55e]"></span>
    <span class="absolute -left-8 top-0 h-full w-20 bg-[#c3972f] opacity-50 blur-xl"></span>
    <span class="absolute -right-10 top-0 h-full w-24 bg-[#22c55e] opacity-50 blur-xl"></span>
    <span class="relative">
        {{ $slot }}
    </span>
</button>
