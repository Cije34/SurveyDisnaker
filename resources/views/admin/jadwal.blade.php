<x-admin.layout :user="$user" active="jadwal" title="Jadwal">
    <x-slot:sidebar>
        <x-admin.sidebar :user="$user" active="jadwal" />
    </x-slot:sidebar>

    <x-admin.topbar :user="$user" title="Jadwal" />

    <div class="px-6 py-8 lg:px-10 lg:py-10 space-y-8"
         x-data="jadwalPage()">
        @if (session('status'))
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-[0_30px_55px_-35px_rgba(15,23,42,0.35)]">
            <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Jadwal Kegiatan</h2>
                    <p class="text-sm text-slate-500">Daftar seluruh jadwal kegiatan yang sudah terdaftar.</p>
                </div>
                <button type="button"
                        @click="openCreateModal()"
                        class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-sky-500 via-sky-400 to-emerald-400 px-5 py-2 text-sm font-semibold text-white shadow-lg transition hover:brightness-110">
                    <span>Tambah Jadwal</span>
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-white/25">
                        <img src="{{ asset('icons/calendar-plus.svg') }}" alt="Tambah" class="h-4 w-4 invert">
                    </span>
                </button>
            </div>

            <div class="overflow-hidden rounded-3xl border border-slate-200 shadow-lg">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead>
                        <tr class="bg-sky-700">
                            <th scope="col" class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wide text-white">Kegiatan</th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wide text-white">Mentor</th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wide text-white">Tanggal</th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wide text-white">Waktu</th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wide text-white">Lokasi</th>
                            <th scope="col" class="px-6 py-4 text-right text-sm font-semibold uppercase tracking-wide text-white">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($schedules as $schedule)
                            @php
                                $date = optional($schedule->tanggal_mulai)->format('d-m-Y') ?? '-';
                                $startTime = $schedule->jam_mulai
                                    ? \Illuminate\Support\Carbon::createFromFormat('H:i:s', $schedule->jam_mulai)->format('H:i')
                                    : optional($schedule->tanggal_mulai)->format('H:i');
                                $endReference = $schedule->jam_selesai
                                    ? \Illuminate\Support\Carbon::createFromFormat('H:i:s', $schedule->jam_selesai)
                                    : optional($schedule->tanggal_selesai ?? $schedule->tanggal_mulai);
                                $endTime = $endReference ? $endReference->format('H:i') : null;
                                $timeRange = trim(($startTime ? $startTime : '') . ($endTime ? ' - ' . $endTime : ''));
                                $mentor = $schedule->mentors->pluck('name')->implode(', ') ?? '-';
                            @endphp
                            <tr class="hover:bg-slate-50">
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-slate-800">
                                    {{ $schedule->kegiatan->nama_kegiatan ?? '-' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                    {{ $mentor }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                    {{ $date }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                    {{ $timeRange ?: '-' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                    {{ $schedule->tempat->name ?? '-' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                    <div x-data="{ open: false }" class="relative inline-flex">
                                        <button type="button"
                                                @click="open = !open"
                                                @click.outside="open = false"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-slate-500 transition hover:border-sky-300 hover:text-sky-600">
                                            <span class="sr-only">Aksi</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                                                <path d="M12 7.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Zm0 6a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Zm0 6a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" />
                                            </svg>
                                        </button>
                                        <div x-show="open"
                                             x-transition.origin.top.right
                                             x-cloak
                                             class="absolute right-0 top-full mt-2 w-40 rounded-2xl border border-slate-200 bg-white py-2 shadow-xl">
                                            <button type="button"
                                                    @click="open = false; openEditModal({
                                                        id: {{ $schedule->id }},
                                                        nama: @js($schedule->kegiatan->nama_kegiatan ?? 'Tanpa Nama'),
                                                        tanggal: '{{ $date }}',
                                                        waktu: '{{ $timeRange ?: '-' }}',
                                                        lokasi: @js($schedule->tempat->name ?? '-')
                                                    })"
                                                    class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652l-10.5 10.5a4.5 4.5 0 01-1.897 1.125l-3.562 1.02 1.02-3.562a4.5 4.5 0 011.125-1.897l10.5-10.5z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 7.125L16.875 4.5" />
                                                </svg>
                                                Edit
                                            </button>
                                            <button type="button"
                                                    @click="open = false; confirmDelete({ id: {{ $schedule->id }}, nama: @js($schedule->kegiatan->nama_kegiatan ?? 'Tanpa Nama') })"
                                                    class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm font-medium text-rose-500 transition hover:bg-rose-50">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-sm text-slate-400">
                                    Belum ada jadwal yang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($schedules->hasPages())
                <div class="pt-6">
                    {{ $schedules->links('components.admin.pagination') }}
                </div>
            @endif
        </section>

        <!-- Placeholder Modal Tambah -->
        <div x-show="createOpen"
             x-cloak
             x-transition.opacity
             class="fixed inset-0 z-[100] flex h-screen w-screen items-center justify-center bg-slate-900/50 backdrop-blur-md"
             @click.self="closeCreateModal()">
            <div x-show="createOpen"
                 x-transition.scale
                 class="w-full max-w-lg rounded-3xl bg-gradient-to-b from-sky-700 via-sky-600 to-sky-800 p-[1px] shadow-2xl">
                <div class="rounded-3xl bg-white/95 p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-slate-900">Tambah Jadwal</h3>
                        <button type="button" @click="closeCreateModal()" class="text-slate-400 transition hover:text-slate-600">✕</button>
                    </div>
                    <p class="text-sm text-slate-600">Form penambahan jadwal akan tersedia pada langkah berikutnya.</p>
                </div>
            </div>
        </div>

        <!-- Placeholder Modal Edit -->
        <div x-show="editOpen"
             x-cloak
             x-transition.opacity
             class="fixed inset-0 z-[100] flex h-screen w-screen items-center justify-center bg-slate-900/50 backdrop-blur-md"
             @click.self="closeEditModal()">
            <div x-show="editOpen"
                 x-transition.scale
                 class="w-full max-w-lg rounded-3xl bg-gradient-to-b from-sky-700 via-sky-600 to-sky-800 p-[1px] shadow-2xl">
                <div class="rounded-3xl bg-white/95 p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-slate-900">Edit Jadwal</h3>
                        <button type="button" @click="closeEditModal()" class="text-slate-400 transition hover:text-slate-600">✕</button>
                    </div>
                    <template x-if="editMeta">
                        <div class="space-y-1 text-sm text-slate-600">
                            <p><span class="font-semibold text-slate-800">Kegiatan:</span> <span x-text="editMeta.nama"></span></p>
                            <p><span class="font-semibold text-slate-800">Tanggal:</span> <span x-text="editMeta.tanggal"></span></p>
                            <p><span class="font-semibold text-slate-800">Waktu:</span> <span x-text="editMeta.waktu"></span></p>
                            <p><span class="font-semibold text-slate-800">Lokasi:</span> <span x-text="editMeta.lokasi"></span></p>
                        </div>
                    </template>
                    <p class="text-sm text-slate-500">Form pengubahan jadwal akan disiapkan pada tahap berikutnya.</p>
                </div>
            </div>
        </div>
    </div>
</x-admin.layout>

<script>
    function jadwalPage() {
        return {
            createOpen: false,
            editOpen: false,
            editMeta: null,
            openCreateModal() {
                this.createOpen = true;
            },
            closeCreateModal() {
                this.createOpen = false;
            },
            openEditModal(meta) {
                this.editMeta = meta;
                this.editOpen = true;
            },
            closeEditModal() {
                this.editOpen = false;
                this.editMeta = null;
            },
            confirmDelete(meta) {
                // Placeholder; aksi hapus akan diimplementasikan pada tahap berikutnya.
                this.editMeta = meta;
                alert(`Fitur hapus akan tersedia segera untuk jadwal "${meta.nama}".`);
            },
        };
    }
</script>
