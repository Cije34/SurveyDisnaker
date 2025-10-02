<x-admin.layout :user="$user" active="jadwal" title="Jadwal">
    <x-slot:sidebar>
        <x-admin.sidebar :user="$user" active="jadwal" />
    </x-slot:sidebar>

    <x-admin.topbar :user="$user" title="Jadwal" />

    <div class="px-6 py-8 lg:px-10 lg:py-10 space-y-8">
        @if (session('status'))
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-[0_30px_55px_-35px_rgba(15,23,42,0.35)]">
            <div class="mb-6 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Jadwal Kegiatan</h2>
                    <p class="text-sm text-slate-500">Daftar seluruh jadwal kegiatan yang sudah terdaftar.</p>
                </div>
                <a href="{{ route('admin.jadwal.create') }}"
                   class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-sky-500 via-sky-400 to-emerald-400 px-5 py-2 text-sm font-semibold text-white shadow-lg transition hover:brightness-110">
                    <span>Tambah Jadwal</span>
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-white/25">
                        <img src="{{ asset('icons/calendar-plus.svg') }}" alt="Tambah" class="h-4 w-4 invert">
                    </span>
                </a>
             </div>



             <!-- Desktop view -->
            <div class="hidden md:block relative rounded-3xl border border-slate-200 shadow-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                     <thead>
                         <tr class="bg-sky-700">
                             <th scope="col" class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wide text-white">Tahun Kegiatan</th>
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
                                $startTime = $schedule->jam_mulai ? substr($schedule->jam_mulai, 0, 5) : null;
                                $endTime = $schedule->jam_selesai ? substr($schedule->jam_selesai, 0, 5) : null;
                                $timeRange = trim(($startTime ? $startTime : '') . ($endTime ? ' - ' . $endTime : ''));
                                $mentor = $schedule->mentors->pluck('name')->implode(', ') ?? '-';
                            @endphp
                             <tr class="hover:bg-slate-50" x-data="{ open: false }" :class="{ 'relative z-10': open }">
                                 <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-slate-800">
                                    {{ $schedule->kegiatan->tahunKegiatan->tahun ?? '-' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
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
                                    {{ $schedule->tempat->name }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                    <div class="relative inline-flex">
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
                                             class="absolute right-0 top-full z-50 mt-2 w-40 rounded-2xl border border-slate-200 bg-white py-2 shadow-xl">
                                            <a href="{{ route('admin.jadwal.edit', $schedule->id) }}"
                                               class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652l-10.5 10.5a4.5 4.5 0 01-1.897 1.125l-3.562 1.02 1.02-3.562a4.5 4.5 0 011.125-1.897l10.5-10.5z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 7.125L16.875 4.5" />
                                                </svg>
                                                Edit
                                            </a>
                                             <button @click="Swal.fire({
                                                 title: 'Konfirmasi Hapus',
                                                 text: 'Apakah Anda yakin ingin menghapus jadwal {{ addslashes($schedule->kegiatan->nama_kegiatan ?? 'Tanpa Nama') }}? Tindakan ini tidak dapat dibatalkan.',
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
                                                     form.action = '{{ route('admin.jadwal.destroy', $schedule->id) }}';
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
                                             })" class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm font-medium text-rose-500 transition hover:bg-rose-50">
                                                 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4">
                                                     <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                 </svg>
                                                 Hapus
                                             </button>
                                            <a href="{{ route('admin.jadwal.show', $schedule->id) }}"
                                               class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">
                                                <img src="{{ asset('icons/eye.svg') }}" alt="Detail" class="h-4 w-4">
                                                Detail
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-sm text-slate-400">
                                    Belum ada jadwal yang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile view -->
            <div class="md:hidden space-y-4">
                @forelse ($schedules as $schedule)
                    @php
                        $date = optional($schedule->tanggal_mulai)->format('d-m-Y') ?? '-';
                        $startTime = $schedule->jam_mulai ? substr($schedule->jam_mulai, 0, 5) : null;
                        $endTime = $schedule->jam_selesai ? substr($schedule->jam_selesai, 0, 5) : null;
                        $timeRange = trim(($startTime ? $startTime : '') . ($endTime ? ' - ' . $endTime : ''));
                        $mentor = $schedule->mentors->pluck('name')->implode(', ') ?? '-';
                    @endphp
                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-md" x-data="{ open: false }">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold uppercase tracking-wide text-sky-600 truncate">{{ $schedule->kegiatan->tahunKegiatan->tahun ?? '-' }}</p>
                                <h3 class="font-semibold text-slate-800 truncate">{{ $schedule->kegiatan->nama_kegiatan ?? '-' }}</h3>
                            </div>
                            <div class="relative flex-shrink-0">
                                <button type="button"
                                        @click="open = !open"
                                        @click.outside="open = false"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-full border text-slate-500 transition hover:border-slate-300">
                                    <span class="sr-only">Aksi</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                                        <path d="M12 7.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Zm0 6a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Zm0 6a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" />
                                    </svg>
                                </button>
                                <div x-show="open" x-transition.origin.top.right x-cloak class="absolute right-0 top-full z-10 mt-2 w-40 rounded-xl border bg-white py-2 shadow-lg">
                                    <a href="{{ route('admin.jadwal.show', $schedule->id) }}" class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm text-slate-600 hover:bg-slate-100">
                                        <img src="{{ asset('icons/eye.svg') }}" alt="Detail" class="h-4 w-4">
                                        Detail
                                    </a>
                                    <a href="{{ route('admin.jadwal.edit', $schedule->id) }}" class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm text-slate-600 hover:bg-slate-100">
                                        Edit
                                    </a>
                                     <button @click="Swal.fire({
                                         title: 'Konfirmasi Hapus',
                                         text: 'Apakah Anda yakin ingin menghapus jadwal {{ addslashes($schedule->kegiatan->nama_kegiatan ?? 'Tanpa Nama') }}? Tindakan ini tidak dapat dibatalkan.',
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
                                             form.action = '{{ route('admin.jadwal.destroy', $schedule->id) }}';
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
                                     })" class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm text-rose-500 hover:bg-rose-50">
                                         Hapus
                                     </button>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 space-y-2 border-t border-slate-100 pt-4 text-sm">
                            <div class="flex justify-between gap-2">
                                <span class="text-slate-500 flex-shrink-0">Mentor:</span>
                                <span class="font-medium text-slate-700 text-right break-words">{{ $mentor }}</span>
                            </div>
                            <div class="flex justify-between gap-2">
                                <span class="text-slate-500 flex-shrink-0">Tanggal:</span>
                                <span class="font-medium text-slate-700 text-right">{{ $date }}</span>
                            </div>
                            <div class="flex justify-between gap-2">
                                <span class="text-slate-500 flex-shrink-0">Waktu:</span>
                                <span class="font-medium text-slate-700 text-right">{{ $timeRange ?: '-' }}</span>
                            </div>
                            <div class="flex justify-between gap-2">
                                <span class="text-slate-500 flex-shrink-0">Penanggung Jawab:</span>
                                <span class="font-medium text-slate-700 text-right break-words">{{ $schedule->penjabs->pluck('name')->implode(', ') ?: '-' }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-200 p-8 text-center text-sm text-slate-400">
                        Belum ada jadwal yang terdaftar.
                    </div>
                @endforelse
            </div>

            @if ($schedules->hasPages())
                <div class="pt-6">
                    {{ $schedules->links('components.admin.pagination') }}
                </div>
            @endif
        </section>
    </div>
</x-admin.layout>
