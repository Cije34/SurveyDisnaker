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
                @if ($participantRows->isEmpty())
                    <p class="text-center text-slate-500">Belum ada data peserta untuk survey ini.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-slate-600">
                            <thead class="bg-sky-700 text-white">
                                <tr>
                                    <th class="px-5 py-3 text-left font-semibold">Nama Peserta</th>
                                    <th class="px-4 py-3 text-center font-semibold">Status</th>
                                    <th class="px-5 py-3 text-left font-semibold">Jawaban</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach ($participantRows as $row)
                                    <tr>
                                        <td class="px-5 py-3 font-medium text-slate-800">{{ $row['peserta']->name ?? 'Anonim' }}</td>
                                        <td class="px-4 py-3 text-center">
                                            @if ($row['status'] === 'answered')
                                                <span class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Sudah menjawab</span>
                                            @else
                                                <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">Belum menjawab</span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-3 text-slate-700">{{ $row['jawaban'] ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="mt-6">
                <a href="{{ route('admin.survey.index') }}" class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">Kembali</a>
            </div>
        </div>
    </div>
</x-admin.layout>
