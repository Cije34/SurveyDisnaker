<x-admin.layout :user="$user" active="jadwal" title="Detail Jadwal">
    <x-slot:sidebar>
        <x-admin.sidebar :user="$user" active="jadwal" />
    </x-slot:sidebar>

    <x-admin.topbar :user="$user" title="Detail Jadwal" />

    <div class="px-6 py-8 lg:px-10 lg:py-10">
        <div class="mb-6">
            <a href="{{ route('admin.jadwal.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Daftar Jadwal
            </a>
        </div>

        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_30px_55px_-35px_rgba(15,23,42,0.35)]">
            <div class="mb-6 flex items-center gap-3">
                <img src="{{ asset('icons/calendar.svg') }}" alt="Jadwal" class="h-8 w-8 text-sky-600">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">{{ $jadwal->kegiatan->nama_kegiatan ?? 'Tanpa Nama' }}</h2>
                    <p class="text-sm text-slate-500">Detail jadwal kegiatan</p>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <h3 class="mb-3 text-lg font-medium text-slate-800">Informasi Kegiatan</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Tahun Kegiatan</dt>
                            <dd class="text-sm text-slate-700">{{ $jadwal->kegiatan->tahunKegiatan->tahun ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Kegiatan</dt>
                            <dd class="text-sm text-slate-700">{{ $jadwal->kegiatan->nama_kegiatan ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Lokasi</dt>
                            <dd class="text-sm text-slate-700">{{ $jadwal->tempat->name ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h3 class="mb-3 text-lg font-medium text-slate-800">Waktu</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Tanggal Mulai</dt>
                            <dd class="text-sm text-slate-700">{{ $jadwal->tanggal_mulai?->format('d M Y') ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Tanggal Selesai</dt>
                            <dd class="text-sm text-slate-700">{{ $jadwal->tanggal_selesai?->format('d M Y') ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Jam Mulai</dt>
                            <dd class="text-sm text-slate-700">{{ $jadwal->jam_mulai ? substr($jadwal->jam_mulai, 0, 5) : '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Jam Selesai</dt>
                            <dd class="text-sm text-slate-700">{{ $jadwal->jam_selesai ? substr($jadwal->jam_selesai, 0, 5) : '-' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="mt-6 grid gap-6 md:grid-cols-2">
                <div>
                    <h3 class="mb-3 text-lg font-medium text-slate-800">Penanggung Jawab</h3>
                    <div class="space-y-2">
                        @forelse ($jadwal->penjabs as $penjab)
                            <div class="flex items-center gap-3">
                                <img src="{{ asset('icons/user-check.svg') }}" alt="Penjab" class="h-5 w-5 text-sky-600">
                                <span class="text-sm text-slate-700">{{ $penjab->name }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">Tidak ada penanggung jawab</p>
                        @endforelse
                    </div>
                </div>

                <div>
                    <h3 class="mb-3 text-lg font-medium text-slate-800">Mentor</h3>
                    <div class="space-y-2">
                        @forelse ($jadwal->mentors as $mentor)
                            <div class="flex items-center gap-3">
                                <img src="{{ asset('icons/chalkboard-teacher.svg') }}" alt="Mentor" class="h-5 w-5 text-sky-600">
                                <span class="text-sm text-slate-700">{{ $mentor->name }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">Tidak ada mentor</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div>
                <h3 class="mb-3 text-lg font-medium text-slate-800">Peserta</h3>
                <div class="space-y-2">
                    @forelse ($jadwal->pesertas as $peserta)
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('icons/chalkboard-teacher.svg') }}" alt="Mentor" class="h-5 w-5 text-sky-600">
                            <span class="text-sm text-slate-700">{{ $peserta->name }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Tidak ada peserta</p>
                    @endforelse
                </div>
            </div>

        </section>
    </div>
</x-admin.layout>
