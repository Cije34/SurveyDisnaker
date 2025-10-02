<x-admin.layout :user="$user" active="survey" title="Jawaban Survey">
    <x-slot:sidebar>
        <x-admin.sidebar :user="$user" active="survey" />
    </x-slot:sidebar>

    <x-admin.topbar :user="$user" title="Jawaban Survey" />

    <div class="px-6 py-8 lg:px-10 lg:py-10 space-y-8">
        <div class="max-w-6xl mx-auto">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-slate-900">Jawaban Survey: {{ $survey->pertanyaan }}</h1>
                <p class="text-sm text-slate-500 mt-1">Kegiatan: {{ $survey->kegiatan->nama_kegiatan ?? 'N/A' }}</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-lg p-6">
                @if($jawabans->isEmpty())
                    <p class="text-center text-slate-500">Belum ada jawaban untuk survey ini.</p>
                @else
                    <div class="space-y-4">
                        @foreach($jawabans as $jawaban)
                            <div class="border border-slate-200 rounded-xl p-4">
                                <p class="font-medium text-slate-900">{{ $jawaban->peserta->name ?? 'Anonim' }}</p>
                                <p class="text-sm text-slate-700 mt-1">{{ $jawaban->jawaban }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="mt-6">
                <a href="{{ route('admin.survey.index') }}" class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">Kembali</a>
            </div>
        </div>
    </div>
</x-admin.layout>
