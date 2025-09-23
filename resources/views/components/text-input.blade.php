@props(['disabled' => false])

<input @disabled($disabled)
    {{ $attributes->class([
        'w-full h-14 rounded-full border-2 border-sky-400/70 bg-white/95 text-lg text-slate-700 placeholder-slate-400',
        'shadow-md shadow-sky-500/10 focus:border-sky-500 focus:ring-4 focus:ring-sky-200 focus:outline-none transition ease-out'
    ]) }}>
