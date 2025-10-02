<x-admin.layout :user="$user" active="survey" title="Survey">
    <x-slot:sidebar>
        <x-admin.sidebar :user="$user" active="survey" />
    </x-slot:sidebar>

    <x-admin.topbar :user="$user" title="Kelola Survey" />

    <div class="px-6 py-8 lg:px-10 lg:py-10 space-y-6">
        @if (session('status'))
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: @js(session('status')),
                        confirmButtonColor: '#0284c7'
                    });
                });
            </script>
        @endif

        @if ($errors->any())
            <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-3 text-sm text-rose-700">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-[0_30px_55px_-35px_rgba(15,23,42,0.35)]">
            <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Daftar Survey</h2>
                    <p class="text-sm text-slate-500">Kelola survey untuk setiap kegiatan yang tersedia.</p>
                </div>
                <a href="{{ route('admin.survey.create') }}" class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-sky-500 via-sky-400 to-emerald-400 px-5 py-2 text-sm font-semibold text-white shadow-lg transition hover:brightness-110">
                    <span>Tambah Survey</span>
                </a>
            </div>

            <div class="relative overflow-x-auto rounded-3xl border border-slate-200 shadow-lg">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-sky-700 text-left text-white">
                        <tr>
                            <th class="px-6 py-4 text-center font-semibold uppercase tracking-wide">Kegiatan</th>
                            <th class="px-6 py-4 text-center font-semibold uppercase tracking-wide">Status</th>
                            <th class="px-6 py-4 text-center font-semibold uppercase tracking-wide">Pertanyaan</th>
                            <th class="px-6 py-4 text-center font-semibold uppercase tracking-wide">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($surveyGroups as $kegiatan)
                            @php
                                $primarySurvey = $kegiatan->surveys->first();
                                $activeSurveyCount = $kegiatan->surveys->filter(fn ($survey) => $survey->is_active !== false)->count();
                                $isClosed = $kegiatan->surveys->isNotEmpty() && $activeSurveyCount === 0;
                            @endphp
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-6 py-4 text-center font-medium text-slate-800">
                                    {{ $kegiatan->tahunKegiatan->tahun }} | {{ $kegiatan->nama_kegiatan ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($isClosed)
                                        <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                            <span class="h-2 w-2 rounded-full bg-slate-500"></span>
                                            Ditutup
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                            Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-slate-600">
                                    {{ $kegiatan->surveys_count }} pertanyaan
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-wrap justify-center gap-2 text-xs font-semibold">
                                        @if ($primarySurvey)
                                            <a href="{{ route('admin.survey.answers', $primarySurvey->id) }}" class="inline-flex items-center gap-2 rounded-full bg-emerald-500 px-4 py-2 text-white shadow hover:bg-emerald-600">
                                                Jawaban
                                            </a>
                                            <a href="{{ route('admin.survey.edit', $primarySurvey->id) }}" class="inline-flex items-center gap-2 rounded-full bg-amber-400/90 px-4 py-2 text-slate-900 shadow hover:bg-amber-400">
                                                Edit
                                            </a>
                                            <button @click="Swal.fire({
                                                title: 'Konfirmasi Hapus',
                                                text: 'Apakah Anda yakin ingin menghapus semua pertanyaan survey pada kegiatan ini? Tindakan ini tidak dapat dibatalkan.',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#ef4444',
                                                cancelButtonColor: '#6b7280',
                                                confirmButtonText: 'Hapus',
                                                cancelButtonText: 'Batal'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    const form = document.createElement('form');
                                                    form.method = 'POST';
                                                    form.action = '/admin/survey/{{ $primarySurvey->id }}';
                                                    const csrf = document.createElement('input');
                                                    csrf.type = 'hidden';
                                                    csrf.name = '_token';
                                                    csrf.value = '{{ csrf_token() }}';
                                                    form.appendChild(csrf);
                                                    const method = document.createElement('input');
                                                    method.type = 'hidden';
                                                    method.name = '_method';
                                                    method.value = 'DELETE';
                                                    form.appendChild(method);
                                                    document.body.appendChild(form);
                                                    form.submit();
                                                }
                                            })" class="inline-flex items-center gap-2 rounded-full bg-rose-500 px-4 py-2 text-white shadow hover:bg-rose-600">
                                                Hapus Semua
                                            </button>
                                            @if ($isClosed)
                                                <button type="button" class="inline-flex items-center gap-2 rounded-full bg-slate-400 px-4 py-2 text-white opacity-60 cursor-not-allowed" disabled>
                                                    Ditutup
                                                </button>
                                            @else
                                                <button type="button" @click="Swal.fire({
                                                    title: 'Konfirmasi Tutup',
                                                    text: 'Menutup survey akan menghentikan peserta untuk mengisi lagi. Lanjutkan?',
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#1f2937',
                                                    cancelButtonColor: '#6b7280',
                                                    confirmButtonText: 'Tutup',
                                                    cancelButtonText: 'Batal'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        const form = document.createElement('form');
                                                        form.method = 'POST';
                                                        form.action = '{{ route('admin.survey.close', $primarySurvey->id) }}';
                                                        const csrf = document.createElement('input');
                                                        csrf.type = 'hidden';
                                                        csrf.name = '_token';
                                                        csrf.value = '{{ csrf_token() }}';
                                                        form.appendChild(csrf);
                                                        document.body.appendChild(form);
                                                        form.submit();
                                                    }
                                                })" class="inline-flex items-center gap-2 rounded-full bg-slate-800 px-4 py-2 text-white shadow hover:bg-slate-900">
                                                    Tutup
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-sm text-slate-500">Tidak ada survey.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        @if ($surveyGroups->hasPages())
            <div class="pt-6">
                {{ $surveyGroups->links('components.admin.pagination') }}
            </div>
        @endif
    </div>
</x-admin.layout>
