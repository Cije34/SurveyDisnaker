<x-perserta.layout :user="$profile ?? $user" active="survey">
    <x-slot:sidebar>
        <x-perserta.sidebar :user="$profile ?? $user" active="survey" />
    </x-slot:sidebar>

    <x-perserta.topbar :user="$profile ?? $user" />

    <div class="px-8 py-10">
        <a href="{{ route('peserta.survey') }}" class="inline-flex items-center gap-2 text-sm font-medium text-sky-600 hover:text-sky-700">
            <span class="inline-block rotate-180">&rarr;</span>
            Kembali ke daftar survey
        </a>

        <section class="mt-6 max-w-4xl rounded-3xl border border-slate-200 bg-white p-8 shadow-[0_35px_55px_-35px_rgba(15,23,42,0.25)]">
            <header class="space-y-2 border-b border-slate-200 pb-6">
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Survey Kegiatan</p>
                <h2 class="text-3xl font-semibold text-slate-900">{{ $kegiatan->nama_kegiatan }}</h2>
                <p class="text-sm text-slate-500">Isi setiap pertanyaan untuk membantu peningkatan program kami.</p>
            </header>

            @if ($kegiatan->surveys->isEmpty())
                <p class="mt-8 text-center text-slate-400">Belum ada pertanyaan untuk kegiatan ini.</p>
            @else
                <form method="POST" action="{{ route('peserta.survey.submit', $kegiatan) }}" class="mt-8 space-y-8">
                    @csrf
                    @foreach ($kegiatan->surveys as $index => $question)
                    @php
                        $fieldIndex = $question->id;
                        $fieldName = "answers[{$fieldIndex}]";
                        $fieldError = "answers.{$fieldIndex}";
                        $existing = optional($question->jawabans->first())->jawaban;
                    @endphp
                    <div class="rounded-2xl border border-slate-200 px-6 py-5 shadow-[0_15px_35px_-30px_rgba(15,23,42,0.25)]">
                        <h3 class="text-base font-semibold text-slate-900">{{ $index + 1 }}. {{ $question->pertanyaan }}</h3>

                        @if ($question->isChoice())
                            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                @foreach (['Sangat Baik', 'Baik', 'Cukup Baik', 'Buruk'] as $indexOption => $option)
                                    <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 hover:border-sky-400">
                                        <input type="radio"
                                               name="{{ $fieldName }}"
                                               value="{{ $option }}"
                                               class="h-4 w-4 border-slate-300 text-sky-500 focus:ring-sky-400"
                                               @checked(old($fieldError, $existing) === $option)>
                                        <span><strong>{{ chr(65 + $indexOption) }}.</strong> {{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <textarea
                                name="{{ $fieldName }}"
                                rows="4"
                                class="mt-3 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 shadow-inner focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                                placeholder="Tulis jawaban Anda di sini...">{{ old($fieldError, $existing) }}</textarea>
                        @endif

                        @error($fieldError)
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    @endforeach

                    <div class="flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-sky-600 via-sky-500 to-emerald-500 px-6 py-3 text-sm font-semibold text-white shadow-lg transition hover:brightness-110">
                            <span>Kirim Jawaban</span>
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-white/25">
                                <img src="{{ asset('icons/sign-in.svg') }}" alt="Submit" class="h-3.5 w-3.5">
                            </span>
                        </button>
                    </div>
                </form>
            @endif
        </section>
    </div>
</x-perserta.layout>
