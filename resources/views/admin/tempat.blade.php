<x-admin.layout :user="$user" active="tempat" title="Tempat">
    <x-slot:sidebar>
        <x-admin.sidebar :user="$user" active="tempat" />
    </x-slot:sidebar>

    <x-admin.topbar :user="$user" title="Tempat" />

    <div class="px-6 py-8 lg:px-10 lg:py-10 space-y-8" x-data="tempatPage()">
        @if (session('status'))
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-[0_30px_55px_-35px_rgba(15,23,42,0.35)]">
            <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Tempat</h2>
                    <p class="text-sm text-slate-500">Daftar seluruh tempat yang sudah terdaftar.</p>
                </div>
                 <button type="button"
                         @click="modalOpen = true"
                         class="inline-flex items-center justify-center gap-2 rounded-full bg-gradient-to-r from-sky-500 via-sky-400 to-emerald-400 px-4 py-3 text-sm font-semibold text-white shadow-lg transition hover:brightness-110 w-full md:w-auto md:px-5">
                     <span>Tambah Tempat</span>
                     <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-white/25 md:h-6 md:w-6">
                         <img src="{{ asset('icons/plus.svg') }}" alt="Tambah" class="h-3 w-3 md:h-4 md:w-4">
                     </span>
                 </button>
            </div>

            <div class="relative rounded-3xl border border-slate-200 shadow-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead>
                        <tr class="bg-sky-700">
                            <th scope="col" class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wide text-white">Nama Tempat</th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wide text-white">Alamat</th>
                            <th scope="col" class="px-6 py-4 text-right text-sm font-semibold uppercase tracking-wide text-white">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                         @forelse ($tempats as $tempat)
                             <tr class="hover:bg-slate-50" x-data="{ open: false }" :class="{ 'relative z-10': open }">
                                 <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-slate-800">
                                     {{ $tempat->name ?? '-' }}
                                 </td>
                                 <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                     {{ $tempat->alamat ?? '-' }}
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
                                                     @click="open = false; openEditModal($event.target.dataset)"
                                                     data-id="{{ $tempat->id }}"
                                                     data-name="{{ $tempat->name }}"
                                                     data-alamat="{{ $tempat->alamat }}"
                                                     class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">
                                                 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4">
                                                     <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652l-10.5 10.5a4.5 4.5 0 01-1.897 1.125l-3.562 1.02 1.02-3.562a4.5 4.5 0 011.125-1.897l10.5-10.5z" />
                                                     <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 7.125L16.875 4.5" />
                                                 </svg>
                                                 Edit
                                             </button>
                                             <button type="button"
                                                     @click="open = false; confirmDelete($event.target.dataset)"
                                                     data-id="{{ $tempat->id }}"
                                                     data-nama="{{ $tempat->name }}"
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
                                 <td colspan="3" class="px-6 py-12 text-center text-sm text-slate-400">
                                     Belum ada tempat yang terdaftar.
                                 </td>
                             </tr>
                         @endforelse
                    </tbody>
                </table>
            </div>

            @if ($tempats->hasPages())
                <div class="pt-6">
                    {{ $tempats->links('components.admin.pagination') }}
                </div>
            @endif
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
                        <h3 class="text-lg font-semibold text-slate-900">Tambah Tempat</h3>
                        <button type="button" @click="modalOpen = false" class="text-slate-400 transition hover:text-slate-600">✕</button>
                    </div>

                    <form method="POST" action="{{ route('admin.tempat.store') }}" class="space-y-4">
                        @csrf
                        <div>
                             <label for="name" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Nama Tempat</label>
                             <input id="name" type="text" name="name"
                                    value="{{ old('name') }}"
                                    class="mt-3 w-full rounded-full border border-slate-200 bg-slate-50 px-6 py-3 text-base font-semibold text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                                    placeholder="Contoh: Aula Disnaker" />
                             @error('name')
                                 <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                             @enderror
                         </div>

                         <div>
                             <label for="alamat" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Alamat</label>
                             <textarea id="alamat" name="alamat" rows="2"
                                       class="mt-3 w-full rounded-xl border border-slate-200 bg-slate-50 px-6 py-3 text-base font-semibold text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                                       placeholder="Contoh: Jl. Sudirman No. 1, Jakarta">{{ old('alamat') }}</textarea>
                             @error('alamat')
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

        <!-- Modal Edit Tempat -->
        <div x-show="editOpen"
             x-cloak
             x-transition.opacity
             class="fixed inset-0 w-screen h-screen z-[100] flex items-center justify-center bg-slate-900/50 backdrop-blur-md"
             @click.self="closeEditModal()">
            <div x-show="editOpen"
                 x-transition.scale
                 class="w-full max-w-md rounded-3xl bg-gradient-to-b from-sky-700 via-sky-600 to-sky-800 p-[1px] shadow-2xl">
                <div class="rounded-3xl bg-white/95 p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-slate-900">Edit Tempat</h3>
                        <button type="button" @click="closeEditModal()" class="text-slate-400 transition hover:text-slate-600">✕</button>
                    </div>

                    <form method="POST" :action="`/admin/tempat/${editMeta?.id}`" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="edit_name" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Nama Tempat</label>
                            <input id="edit_name" type="text" name="name" :value="editMeta?.name" required
                                   class="mt-3 w-full rounded-full border border-slate-200 bg-slate-50 px-6 py-3 text-base font-semibold text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100" />
                        </div>
                        <div>
                            <label for="edit_alamat" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Alamat</label>
                            <textarea id="edit_alamat" name="alamat" rows="2"
                                      class="mt-3 w-full rounded-xl border border-slate-200 bg-slate-50 px-6 py-3 text-base font-semibold text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                                      x-text="editMeta?.alamat"></textarea>
                        </div>
                        <div class="flex items-center justify-end gap-3 pt-3">
                            <button type="button" @click="closeEditModal()"
                                    class="inline-flex items-center gap-2 rounded-full border border-slate-300 px-6 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">Batal</button>
                            <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-sky-500 via-sky-400 to-emerald-400 px-6 py-2 text-sm font-semibold text-white shadow-lg transition hover:brightness-110">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Hapus Tempat -->
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
                        Apakah Anda yakin ingin menghapus tempat <strong x-text="editMeta?.nama" class="font-semibold text-slate-800"></strong>?
                        Tindakan ini tidak dapat diurungkan.
                    </p>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="closeDeleteModal()" class="rounded-full bg-slate-100 px-5 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">Batal</button>
                        <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-rose-500 via-rose-500 to-red-500 px-5 py-2 text-sm font-semibold text-white shadow-lg transition hover:brightness-110">Ya, Hapus</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
        function tempatPage() {
            return {
                modalOpen: {{ $errors->any() ? 'true' : 'false' }},
                editOpen: false,
                deleteOpen: false,
                editMeta: null,
                deleteTemplate: '{{ route('admin.tempat.destroy', '__ID__') }}',
                openEditModal(data) {
                    this.editMeta = {
                        id: data.id,
                        name: data.name,
                        alamat: data.alamat
                    };
                    this.editOpen = true;
                },
                closeEditModal() {
                    this.editOpen = false;
                    this.editMeta = null;
                },
                confirmDelete(data) {
                    this.editMeta = { id: data.id, nama: data.nama };
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
            };
        }
        </script>
    </div>
</x-admin.layout>
