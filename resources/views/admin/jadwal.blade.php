<x-admin.layout :user="$user" active="jadwal" title="Jadwal">
    <x-slot:sidebar>
        <x-admin.sidebar :user="$user" active="jadwal" />
    </x-slot:sidebar>

    <x-admin.topbar :user="$user" title="Jadwal" />

     <div class="px-6 py-8 lg:px-10 lg:py-10 space-y-8"
          x-data="jadwalPage()"
          x-init="init()">
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

            <!-- Desktop view -->
            <div class="hidden md:block relative rounded-3xl border border-slate-200 shadow-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead>
                        <tr
                        class="bg-sky-700">
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
                                             <button type="button"
                                                     @click="open = false; openEditModal({
                                                         id: {{ $schedule->id }},
                                                         kegiatan_id: '{{ $schedule->kegiatan_id }}',
                                                          penjab_ids: {!! json_encode($schedule->penjabs ? $schedule->penjabs->pluck('id') : []) !!},
                                                         tempat_id: '{{ $schedule->tempat_id }}',
                                                          mentor_ids: @json($schedule->mentors ? $schedule->mentors->pluck('id') : []),
                                                         tanggal_mulai: '{{ optional($schedule->tanggal_mulai)->format('Y-m-d') }}',
                                                         tanggal_selesai: '{{ optional($schedule->tanggal_selesai)->format('Y-m-d') }}',
                                                         jam_mulai: '{{ $schedule->jam_mulai ? substr($schedule->jam_mulai, 0, 5) : '' }}',
                                                         jam_selesai: '{{ $schedule->jam_selesai ? substr($schedule->jam_selesai, 0, 5) : '' }}'
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
                         <div class="flex items-start justify-between gap-3">
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
                                     <button type="button" @click="open = false; openEditModal({
                                              id: {{ $schedule->id }},
                                              kegiatan_id: '{{ $schedule->kegiatan_id }}',
                                               penjab_ids: @json($schedule->penjabs ? $schedule->penjabs->pluck('id') : []),
                                              tempat_id: '{{ $schedule->tempat_id }}',
                                               mentor_ids: @json($schedule->mentors ? $schedule->mentors->pluck('id') : []),
                                              tanggal_mulai: '{{ optional($schedule->tanggal_mulai)->format('Y-m-d') }}',
                                              tanggal_selesai: '{{ optional($schedule->tanggal_selesai)->format('Y-m-d') }}',
                                              jam_mulai: '{{ $schedule->jam_mulai ? substr($schedule->jam_mulai, 0, 5) : '' }}',
                                              jam_selesai: '{{ $schedule->jam_selesai ? substr($schedule->jam_selesai, 0, 5) : '' }}'
                                         })" class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm text-slate-600 hover:bg-slate-100">
                                         Edit
                                     </button>
                                    <button type="button" @click="open = false; confirmDelete({ id: {{ $schedule->id }}, nama: @js($schedule->kegiatan->nama_kegiatan ?? 'Tanpa Nama') })" class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm text-rose-500 hover:bg-rose-50">
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

        <!-- Modal Tambah Jadwal -->
        <div x-show="createOpen"
             x-cloak
             x-transition.opacity
             class="fixed inset-0 z-[100] flex h-screen w-screen items-center justify-center bg-slate-900/50 backdrop-blur-md"
             @click.self="closeCreateModal()">
             <div x-show="createOpen"
                  x-transition.scale
                  class="w-full max-w-2xl mx-4 rounded-3xl bg-gradient-to-b from-sky-700 via-sky-600 to-sky-800 p-[1px] shadow-2xl sm:mx-0">
                 <form method="POST" action="{{ route('admin.jadwal.store') }}" class="rounded-3xl bg-white/95 p-4 sm:p-6 space-y-4 sm:space-y-5">
                    @csrf
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-semibold text-slate-900">Tambah Jadwal Baru</h3>
                        <button type="button" @click="closeCreateModal()" class="text-slate-400 transition hover:text-slate-600">✕</button>
                    </div>

                    @if ($errors->any())
                        <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                            <h4 class="font-semibold">Terjadi Kesalahan</h4>
                            <ul class="mt-2 list-inside list-disc">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label for="kegiatan_id" class="mb-1 block text-sm font-medium text-slate-700">Kegiatan</label>
                            <select name="kegiatan_id" id="kegiatan_id" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                                <option value="">Pilih Kegiatan</option>
                                @foreach($kegiatanOptions as $kegiatan)
                                    <option value="{{ $kegiatan->id }}" @selected(old('kegiatan_id') == $kegiatan->id)> {{ $kegiatan->tahunKegiatan->tahun }} - {{ $kegiatan->nama_kegiatan }}</option>
                                @endforeach
                            </select>
                         </div>
                         <div>
                             <label for="tahun_kegiatan_display" class="mb-1 block text-sm font-medium text-slate-700">Tahun Kegiatan</label>
                             <input type="text" id="tahun_kegiatan_display" class="w-full rounded-xl border-slate-300 bg-slate-100 text-sm shadow-sm" readonly placeholder="Pilih kegiatan terlebih dahulu">
                         </div>
                         <div>
                             <label class="mb-1 block text-sm font-medium text-slate-700">Status Aktif</label>
                             <div class="flex items-center gap-2">
                                 <input type="checkbox" id="is_active_display" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500" disabled>
                                 <span class="text-sm text-slate-700" id="is_active_text">Tidak Aktif</span>
                             </div>
                         </div>
                          <div>
                              <label class="mb-1 block text-sm font-medium text-slate-700">Penanggung Jawab</label>
                             <div class="space-y-2 max-h-40 overflow-y-auto border border-slate-200 rounded-xl p-3 bg-slate-50">
                                 @foreach($penjabOptions as $penjab)
                                     <label class="flex items-center gap-3 cursor-pointer">
                                         <input type="checkbox"
                                                value="{{ $penjab->id }}"
                                                x-model="selectedPenjabIds"
                                                name="penjab_ids[]"
                                                class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                                         <span class="text-sm text-slate-700">{{ $penjab->name }}</span>
                                     </label>
                                 @endforeach
                             </div>
                             @error('penjab_ids')
                                 <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                             @enderror
                             @error('penjab_ids.*')
                                 <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                             @enderror
                         </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label for="tempat_id" class="mb-1 block text-sm font-medium text-slate-700">Lokasi</label>
                            <select name="tempat_id" id="tempat_id" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                                <option value="">Pilih Lokasi</option>
                                @foreach($tempatOptions as $tempat)
                                    <option value="{{ $tempat->id }}" @selected(old('tempat_id') == $tempat->id)>{{ $tempat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                         <div>
                             <label class="mb-1 block text-sm font-medium text-slate-700">Mentor</label>
                             <div class="space-y-2 max-h-40 overflow-y-auto border border-slate-200 rounded-xl p-3 bg-slate-50">
                                 @foreach($mentorOptions as $mentor)
                                     <label class="flex items-center gap-3 cursor-pointer">
                                         <input type="checkbox"
                                                value="{{ $mentor->id }}"
                                                x-model="selectedMentorIds"
                                                name="mentor_ids[]"
                                                class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                                         <span class="text-sm text-slate-700">{{ $mentor->name }}</span>
                                     </label>
                                 @endforeach
                             </div>
                             @error('mentor_ids')
                                 <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                             @enderror
                             @error('mentor_ids.*')
                                 <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                             @enderror
                         </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label for="tanggal_mulai" class="mb-1 block text-sm font-medium text-slate-700">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                        </div>
                        <div>
                            <label for="tanggal_selesai" class="mb-1 block text-sm font-medium text-slate-700">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label for="jam_mulai" class="mb-1 block text-sm font-medium text-slate-700">Jam Mulai</label>
                            <input type="time" name="jam_mulai" id="jam_mulai" value="{{ old('jam_mulai') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                        </div>
                        <div>
                            <label for="jam_selesai" class="mb-1 block text-sm font-medium text-slate-700">Jam Selesai</label>
                            <input type="time" name="jam_selesai" id="jam_selesai" value="{{ old('jam_selesai') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="closeCreateModal()" class="rounded-full bg-slate-100 px-5 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">Batal</button>
                        <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-sky-500 via-sky-400 to-emerald-400 px-5 py-2 text-sm font-semibold text-white shadow-lg transition hover:brightness-110">Simpan Jadwal</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Ganti modal edit placeholder (baris 240-263) dengan form lengkap -->
<div x-show="editOpen"
x-cloak
x-transition.opacity
class="fixed inset-0 z-[100] flex h-screen w-screen items-center justify-center bg-slate-900/50 backdrop-blur-md"
@click.self="closeEditModal()">
 <div x-show="editOpen"
     x-transition.scale
     class="w-full max-w-2xl mx-4 rounded-3xl bg-gradient-to-b from-sky-700 via-sky-600 to-sky-800 p-[1px] shadow-2xl sm:mx-0">
    <div class="rounded-3xl bg-white/95 p-4 sm:p-6">
       <div class="mb-4 flex items-center justify-between">
           <h3 class="text-lg font-semibold text-slate-900">Edit Jadwal</h3>
           <button type="button" @click="closeEditModal()" class="text-slate-400 transition hover:text-slate-600">✕</button>
       </div>

       <form method="POST" :action="`/admin/jadwal/${editMeta?.id}`" class="space-y-4">
           @csrf
           @method('PUT')

           <div>
               <label for="edit_kegiatan" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Kegiatan</label>
               <select id="edit_kegiatan" name="kegiatan_id"
                       class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-700 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                   <option value="">Pilih kegiatan</option>
                    @foreach ($kegiatanOptions as $option)
                        <option value="{{ $option->id }}" :selected="editMeta?.kegiatan_id == {{ $option->id }}">{{ $option->tahunKegiatan->tahun }} - {{ $option->nama_kegiatan }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label for="edit_tahun_kegiatan_display" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Tahun Kegiatan</label>
                    <input type="text" id="edit_tahun_kegiatan_display" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-700" readonly placeholder="Pilih kegiatan terlebih dahulu">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Status Aktif</label>
                    <div class="mt-2 flex items-center gap-2">
                        <input type="checkbox" id="edit_is_active_display" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500" disabled>
                        <span class="text-sm text-slate-700" id="edit_is_active_text">Tidak Aktif</span>
                    </div>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Penanggung Jawab</label>
                    <div class="mt-2 space-y-2 max-h-40 overflow-y-auto border border-slate-200 rounded-2xl bg-slate-50 p-3">
                        @foreach ($penjabOptions as $penjab)
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox"
                                       value="{{ $penjab->id }}"
                                       x-model="selectedPenjabIdsEdit"
                                       name="penjab_ids[]"
                                       class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                                <span class="text-sm text-slate-700">{{ $penjab->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

               <div>
                   <label for="edit_tempat" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Tempat</label>
                   <select id="edit_tempat" name="tempat_id"
                           class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-700 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                       <option value="">Pilih tempat</option>
                       @foreach ($tempatOptions as $tempat)
                           <option value="{{ $tempat->id }}" :selected="editMeta?.tempat_id == {{ $tempat->id }}">{{ $tempat->name }}</option>
                       @endforeach
                   </select>
               </div>
           </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Mentor</label>
                <div class="mt-2 space-y-2 max-h-40 overflow-y-auto border border-slate-200 rounded-2xl bg-slate-50 p-3">
                    @foreach ($mentorOptions as $mentor)
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox"
                                   value="{{ $mentor->id }}"
                                   x-model="selectedMentorIdsEdit"
                                   name="mentor_ids[]"
                                   class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                            <span class="text-sm text-slate-700">{{ $mentor->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

           <div class="grid gap-4 md:grid-cols-2">
               <div>
                   <label for="edit_tanggal_mulai" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Tanggal Mulai</label>
                   <input id="edit_tanggal_mulai" type="date" name="tanggal_mulai"
                          :value="editMeta?.tanggal_mulai"
                          class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-700 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
               </div>

               <div>
                   <label for="edit_tanggal_selesai" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Tanggal Selesai</label>
                   <input id="edit_tanggal_selesai" type="date" name="tanggal_selesai"
                          :value="editMeta?.tanggal_selesai"
                          class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-700 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
               </div>
           </div>

           <div class="grid gap-4 md:grid-cols-2">
               <div>
                   <label for="edit_jam_mulai" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Jam Mulai</label>
                   <input id="edit_jam_mulai" type="time" name="jam_mulai"
                          :value="editMeta?.jam_mulai"
                          class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-700 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
               </div>

               <div>
                   <label for="edit_jam_selesai" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Jam Selesai</label>
                   <input id="edit_jam_selesai" type="time" name="jam_selesai"
                          :value="editMeta?.jam_selesai"
                          class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-700 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
               </div>
           </div>

           <div class="flex items-center justify-end gap-3 pt-2">
               <button type="button" @click="closeEditModal()"
                       class="inline-flex items-center gap-2 rounded-full border border-slate-300 px-5 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">Batal</button>
               <button type="submit"
                       class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-sky-500 via-sky-400 to-emerald-400 px-6 py-2 text-sm font-semibold text-white shadow-lg transition hover:brightness-110">
                   Simpan Perubahan
               </button>
           </div>
       </form>
   </div>
</div>
</div>

        <!-- Modal Hapus Jadwal -->
        <div x-show="deleteOpen"
             x-cloak
             x-transition.opacity
             class="fixed inset-0 z-[100] flex h-screen w-screen items-center justify-center bg-slate-900/50 backdrop-blur-md"
             @click.self="closeDeleteModal()">
             <div x-show="deleteOpen"
                  x-transition.scale
                  class="w-full max-w-lg mx-4 rounded-3xl bg-gradient-to-b from-rose-700 via-rose-600 to-rose-800 p-[1px] shadow-2xl sm:mx-0">
                 <form method="POST" :action="deleteAction()" class="rounded-3xl bg-white/95 p-4 sm:p-6 space-y-4 sm:space-y-5">
                    @csrf
                    @method('DELETE')
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-semibold text-slate-900">Konfirmasi Hapus</h3>
                        <button type="button" @click="closeDeleteModal()" class="text-slate-400 transition hover:text-slate-600">✕</button>
                    </div>
                    <p>
                        Apakah Anda yakin ingin menghapus jadwal kegiatan <strong x-text="editMeta?.nama" class="font-semibold text-slate-800"></strong>?
                        Tindakan ini tidak dapat diurungkan.
                    </p>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="closeDeleteModal()" class="rounded-full bg-slate-100 px-5 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">Batal</button>
                        <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-rose-500 via-rose-500 to-red-500 px-5 py-2 text-sm font-semibold text-white shadow-lg transition hover:brightness-110">Ya, Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-admin.layout>

<script>
     function jadwalPage() {
         return {
             createOpen: {{ $errors->any() ? 'true' : 'false' }},
             editOpen: false,
             deleteOpen: false,
             editMeta: null,
             deleteTemplate: '{{ route('admin.jadwal.destroy', '__ID__') }}',
             selectedPenjabIds: {{ old('penjab_ids', '[]') }},
             selectedPenjabIdsEdit: [],
             selectedMentorIds: {{ old('mentor_ids', '[]') }},
             selectedMentorIdsEdit: [],
            openCreateModal() {
                this.createOpen = true;
            },
            closeCreateModal() {
                this.createOpen = false;
            },
              openEditModal(meta) {
                  this.editMeta = meta;
                  this.selectedPenjabIdsEdit = (meta.penjab_ids || []).map(id => id.toString());
                  this.selectedMentorIdsEdit = (meta.mentor_ids || []).map(id => id.toString());
                  this.editOpen = true;
              },
            closeEditModal() {
                this.editOpen = false;
                this.editMeta = null;
            },
            confirmDelete(meta) {
                this.editMeta = meta;
                this.deleteOpen = true;
            },
            closeDeleteModal() {
                this.deleteOpen = false;
                this.editMeta = null;
            },
             deleteAction() {
                 if (!this.editMeta?.id) return '#';
                 return this.deleteTemplate.replace('__ID__', this.editMeta.id);
             },
              init() {
                  // Initialize edit penjab ids when opening edit modal
                  // Data kegiatan untuk lookup
                  const kegiatanData = {!! json_encode($kegiatanOptions->mapWithKeys(function ($k) {
                      return [$k->id => [
                          'tahun' => $k->tahunKegiatan->tahun ?? '-',
                          'is_active' => $k->tahunKegiatan->is_active ?? false
                      ]];
                  })) !!};

                  // Event listener untuk auto-populate di create modal
                  document.getElementById('kegiatan_id')?.addEventListener('change', function() {
                      const selectedId = this.value;
                      const data = kegiatanData[selectedId] || { tahun: '-', is_active: false };
                      document.getElementById('tahun_kegiatan_display').value = data.tahun;
                      const isActiveCheckbox = document.getElementById('is_active_display');
                      const isActiveText = document.getElementById('is_active_text');
                      isActiveCheckbox.checked = data.is_active;
                      isActiveText.textContent = data.is_active ? 'Aktif' : 'Tidak Aktif';
                  });

                  // Event listener untuk auto-populate di edit modal
                  document.getElementById('edit_kegiatan')?.addEventListener('change', function() {
                      const selectedId = this.value;
                      const data = kegiatanData[selectedId] || { tahun: '-', is_active: false };
                      document.getElementById('edit_tahun_kegiatan_display').value = data.tahun;
                      const isActiveCheckbox = document.getElementById('edit_is_active_display');
                      const isActiveText = document.getElementById('edit_is_active_text');
                      isActiveCheckbox.checked = data.is_active;
                      isActiveText.textContent = data.is_active ? 'Aktif' : 'Tidak Aktif';
                  });
              },
        };
    }
</script>
