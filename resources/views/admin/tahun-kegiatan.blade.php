<x-admin.layout :user="$user" active="tahun" title="Tahun Kegiatan">
    <x-slot:sidebar>
        <x-admin.sidebar :user="$user" active="tahun" />
    </x-slot:sidebar>

    <x-admin.topbar :user="$user" title="Tahun Kegiatan" />

    <div class="px-6 py-8 lg:px-10 lg:py-10 space-y-8" x-data="{ modalOpen: {{ $errors->any() ? 'true' : 'false' }} }">
        @if (session('status'))
            <div class="rounded-2xl border border-sky-100 bg-sky-50 px-5 py-3 text-sm text-sky-700">{{ session('status') }}</div>
        @endif

        <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-[0_30px_55px_-35px_rgba(15,23,42,0.35)]">
            <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <h2 class="text-xl font-semibold text-slate-900">Daftar Tahun Kegiatan</h2>
                <button type="button"
                        @click="modalOpen = true"
                        class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-sky-500 via-sky-400 to-emerald-400 px-5 py-2 text-sm font-semibold text-white shadow-lg transition hover:brightness-110">
                    <span class="text-center">Tambah</span>
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-white/25">
                        <img src="{{ asset('icons/calendar-plus.svg') }}" alt="Tambah" class="h-4 w-4">
                    </span>
                </button>
            </div>

            <div>
                <table class="min-w-full border-collapse text-sm text-slate-700">
                    <thead class="bg-sky-700 text-left text-white">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Tahun</th>
                            <th class="px-5 py-3 font-semibold">Status</th>
                            <th class="px-5 py-3 font-semibold text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($years as $year)
                            <tr class="border-b border-slate-200">
                                <td class="px-5 py-3 font-semibold text-slate-800">{{ $year->tahun }}</td>
                                <td class="px-5 py-3">
                                    <span @class([
                                        'inline-flex items-center justify-center gap-2 rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide leading-none',
                                        'bg-emerald-100 text-emerald-700' => $year->is_active,
                                        'bg-rose-100 text-rose-700' => ! $year->is_active,
                                    ])>
                                        <span class="h-2.5 w-2.5 rounded-full" @class(['bg-emerald-500' => $year->is_active, 'bg-rose-500' => ! $year->is_active])></span>
                                        <span class="tracking-wide">{{ $year->is_active ? 'Aktif' : 'Tidak Aktif' }}</span>
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-center">
                                     <button @click="Swal.fire({
                                         title: 'Konfirmasi Hapus',
                                         text: 'Apakah Anda yakin ingin menghapus tahun {{ $year->tahun }}? Tindakan ini tidak dapat dibatalkan.',
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
                                             form.action = '{{ route('admin.tahun.destroy', $year) }}';
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
                                     })"
                                             class="inline-flex items-center gap-2 rounded-full border border-rose-300 px-4 py-2 text-xs font-semibold text-rose-600 transition hover:bg-rose-100"
                                             @disabled($year->is_active && $years->count() === 1)
                                             title="Hapus tahun ini">
                                         Hapus
                                     </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-5 py-6 text-center text-slate-400">Belum ada data tahun kegiatan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div x-show="modalOpen"
             x-cloak
             x-transition.opacity
             class="fixed inset-0 w-screen h-screen z-[100] flex items-center justify-center bg-slate-900/50 backdrop-blur-md"
             @click.self="modalOpen = false">
            <div x-show="modalOpen"
                 x-transition.scale
                 class="w-full max-w-md rounded-3xl bg-gradient-to-b from-sky-700 via-sky-600 to-sky-800 p-[1px] shadow-2xl">
                <div class="rounded-3xl bg-white/95 p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-slate-900">Tambah Tahun</h3>
                        <button type="button" @click="modalOpen = false" class="text-slate-400 transition hover:text-slate-600">✕</button>
                    </div>

                    <form method="POST" action="{{ route('admin.tahun.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="input_tahun" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Tahun</label>
                            <input id="input_tahun" type="number" name="tahun" min="2000" max="2999"
                                   value="{{ old('tahun') }}"
                                   class="mt-3 w-full rounded-full border border-slate-200 bg-slate-50 px-6 py-3 text-center text-lg font-semibold text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                                   placeholder="2025" />
                            @error('tahun')
                                <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-3">
                            <button type="button" @click="modalOpen = false"
                                    class="inline-flex items-center gap-2 rounded-full border border-slate-300 px-6 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">Batal</button>
                            <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-sky-500 via-sky-400 to-emerald-400 px-6 py-2 text-sm font-semibold text-white shadow-lg transition hover:brightness-110">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin.layout>
