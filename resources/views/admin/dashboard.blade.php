<x-admin.layout :user="$user" active="dashboard" title="Dashboard Admin">
    <x-slot:sidebar>
        <x-admin.sidebar :user="$user" active="dashboard" />
    </x-slot:sidebar>

    <x-admin.topbar :user="$user" title="Dashboard Administrator" />

    <div class="px-6 py-8 lg:px-10 lg:py-10 space-y-8" x-data="{ modalOpen: {{ $errors->any() ? 'true' : 'false' }} }">
        @if (session('status'))
            <div class="rounded-2xl border border-sky-100 bg-sky-50 px-5 py-3 text-sm text-sky-700">{{ session('status') }}</div>
        @endif
        <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-[0_30px_55px_-35px_rgba(15,23,42,0.35)]">
            <div class="mb-5 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-2xl font-semibold text-slate-900">Statistik Kepuasan Peserta</h2>
                    <p class="text-sm text-slate-500">Pilih kegiatan untuk melihat persentase respon jawaban.</p>
                </div>
                <form method="GET" class="md:w-64">
                    <label class="block text-sm font-medium text-slate-600 mb-2" for="kegiatan_id">Kegiatan</label>
                    <select id="kegiatan_id" name="kegiatan_id" onchange="this.form.submit()"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-700 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                        @foreach ($kegiatanOptions as $option)
                            <option value="{{ $option->id }}" @selected($option->id == $selectedKegiatanId)>
                                {{ $option->nama_kegiatan }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            <div class="flex flex-col gap-6 lg:flex-row lg:items-start">
                <div class="mx-auto w-full max-w-xs lg:mx-0 lg:w-1/2">
                    <div class="relative h-64">
                        <canvas id="surveyChart"></canvas>
                    </div>
                </div>

                <div class="flex-1 rounded-2xl border border-slate-200 bg-slate-50 p-5 text-sm text-slate-700">
                    <h3 class="text-base font-semibold text-slate-900">Ringkasan</h3>
                    <ul class="mt-4 space-y-2">
                        <li class="flex items-center justify-between">
                            <span>Sangat Baik</span>
                            <span class="font-semibold">{{ $chartData['values'][0] ?? 0 }}%</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <span>Baik</span>
                            <span class="font-semibold">{{ $chartData['values'][1] ?? 0 }}%</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <span>Cukup Baik</span>
                            <span class="font-semibold">{{ $chartData['values'][2] ?? 0 }}%</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <span>Buruk</span>
                            <span class="font-semibold">{{ $chartData['values'][3] ?? 0 }}%</span>
                        </li>
                    </ul>
                    <p class="mt-4 text-xs text-slate-500">Total respon: <strong>{{ $chartData['total'] }}</strong></p>
                </div>
            </div>
        </section>

        <div x-show="modalOpen"
             x-cloak
             x-transition.opacity
             class="fixed inset-0 z-[80] flex items-center justify-center px-4 bg-slate-900/30 backdrop-blur-sm"
             @click.self="modalOpen = false">
            <div x-show="modalOpen"
                 x-transition.scale
                 class="w-full max-w-xl rounded-3xl bg-gradient-to-b via-sky-600 to-sky-800 p-[1px] shadow-2xl">
                <div class="rounded-3xl bg-white p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-slate-900">Tambah Jadwal Kegiatan</h3>
                        <button type="button" @click="modalOpen = false" class="text-slate-400 transition hover:text-slate-600">✕</button>
                    </div>

                    <form method="POST" action="{{ route('admin.jadwal.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="modal_kegiatan" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Kegiatan</label>
                            <select id="modal_kegiatan" name="kegiatan_id"
                                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-700 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                <option value="">Pilih kegiatan</option>
                                @foreach ($kegiatanOptions as $option)
                                    <option value="{{ $option->id }}" @selected(old('kegiatan_id', $selectedKegiatanId) == $option->id)>{{ $option->nama_kegiatan }}</option>
                                @endforeach
                            </select>
                            @error('kegiatan_id')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label for="modal_penjab" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Penanggung Jawab</label>
                                <select id="modal_penjab" name="penjab_id"
                                        class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-700 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                    <option value="">Pilih penanggung jawab</option>
                                    @foreach ($penjabOptions as $penjab)
                                        <option value="{{ $penjab->id }}" @selected(old('penjab_id') == $penjab->id)>{{ $penjab->name }}</option>
                                    @endforeach
                                </select>
                                @error('penjab_id')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="modal_tempat" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Tempat</label>
                                <select id="modal_tempat" name="tempat_id"
                                        class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-700 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                    <option value="">Pilih tempat</option>
                                    @foreach ($tempatOptions as $tempat)
                                        <option value="{{ $tempat->id }}" @selected(old('tempat_id') == $tempat->id)>{{ $tempat->name }}</option>
                                    @endforeach
                                </select>
                                @error('tempat_id')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label for="modal_tanggal_mulai" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Tanggal Mulai</label>
                                <input id="modal_tanggal_mulai" type="date" name="tanggal_mulai"
                                       value="{{ old('tanggal_mulai') }}"
                                       class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-700 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                @error('tanggal_mulai')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="modal_tanggal_selesai" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Tanggal Selesai</label>
                                <input id="modal_tanggal_selesai" type="date" name="tanggal_selesai"
                                       value="{{ old('tanggal_selesai') }}"
                                       class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-700 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                @error('tanggal_selesai')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label for="modal_jam_mulai" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Jam Mulai</label>
                                <input id="modal_jam_mulai" type="time" name="jam_mulai"
                                       value="{{ old('jam_mulai') }}"
                                       class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-700 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                @error('jam_mulai')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="modal_jam_selesai" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Jam Selesai</label>
                                <input id="modal_jam_selesai" type="time" name="jam_selesai"
                                       value="{{ old('jam_selesai') }}"
                                       class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-700 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                @error('jam_selesai')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-2">
                            <button type="button" @click="modalOpen = false"
                                    class="inline-flex items-center gap-2 rounded-full border border-slate-300 px-5 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">Batal</button>
                            <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-sky-500 via-sky-400 to-emerald-400 px-6 py-2 text-sm font-semibold text-white shadow-lg transition hover:brightness-110">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-[0_30px_55px_-35px_rgba(15,23,42,0.35)]">
            <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Jadwal Kegiatan Mendatang</h2>
                    <p class="text-sm text-slate-500">Daftar jadwal terkait kegiatan yang dipilih.</p>
                </div>
                <button type="button"
                        @click="modalOpen = true"
                        class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-sky-500 via-sky-400 to-emerald-400 px-5 py-2 text-sm font-semibold text-white shadow-lg transition hover:brightness-110">
                    <span class="text-center">Tambah Jadwal</span>
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-white/25">
                        <img src="{{ asset('icons/calendar-plus.svg') }}" alt="Tambah" class="h-4 w-4 invert">
                    </span>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse text-sm text-slate-700">
                    <thead class="bg-sky-700 text-left text-white">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Kegiatan</th>
                            <th class="px-5 py-3 font-semibold">Tanggal</th>
                            <th class="px-5 py-3 font-semibold">Waktu</th>
                            <th class="px-5 py-3 font-semibold">Lokasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($upcomingSchedules as $schedule)
                            <tr class="border-b border-slate-200">
                                <td class="px-5 py-3 font-medium text-slate-800">{{ $schedule->kegiatan->nama_kegiatan ?? '-' }}</td>
                                <td class="px-5 py-3">{{ optional($schedule->tanggal_mulai)->translatedFormat('d M Y') ?? '-' }}</td>
                                <td class="px-5 py-3">
                                    {{ $schedule->jam_mulai ? substr($schedule->jam_mulai, 0, 5) : '-' }}
                                    @if($schedule->jam_selesai)
                                        - {{ substr($schedule->jam_selesai, 0, 5) }}
                                    @endif
                                </td>
                                <td class="px-5 py-3">{{ $schedule->tempat->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-6 text-center text-slate-400">Belum ada jadwal mendatang.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('surveyChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: @json($chartData['labels']),
                    datasets: [{
                        data: @json($chartData['values']),
                        backgroundColor: ['#0ea5e9', '#38bdf8', '#fde68a', '#f87171'],
                        borderColor: '#ffffff',
                        borderWidth: 1,
                        hoverBorderWidth: 2,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: 10,
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#0f172a',
                                boxWidth: 12,
                                font: { size: 11 }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw ?? 0;
                                    return `${context.label}: ${value}%`;
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
</x-admin.layout>
