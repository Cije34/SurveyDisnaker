<x-admin.layout :user="$user" active="mentor" title="Mentor">
    <x-slot:sidebar>
        <x-admin.sidebar :user="$user" active="mentor" />
    </x-slot:sidebar>

    <x-admin.topbar :user="$user" title="Mentor" />

    <div class="px-6 py-8 lg:px-10 lg:py-10 space-y-8" x-data="mentorPage()">
        @if (session('status'))
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-[0_30px_55px_-35px_rgba(15,23,42,0.35)]">
            <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                 <div>
                     <h2 class="text-xl font-semibold text-slate-900">Mentor</h2>
                     <p class="text-sm text-slate-500">Daftar seluruh mentor yang sudah terdaftar.</p>
                 </div>
                 <div class="flex items-center gap-3">
                     <button type="button"
                        @click="importOpen = true"
                        class="inline-flex items-center gap-2 rounded-full bg-sky-700 px-5 py-2 text-sm font-semibold text-white shadow-lg transition hover:brightness-110">
                         <span>Import</span>
                         <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-white/25">
                             <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                                 <path d="M12 3.75a.75.75 0 01.75.75v8.19l2.47-2.47a.75.75 0 111.06 1.06l-3.75 3.75a.75.75 0 01-1.06 0l-3.75-3.75a.75.75 0 111.06-1.06l2.47 2.47V4.5a.75.75 0 01.75-.75z" />
                                 <path d="M4.5 14.25a.75.75 0 01.75.75v2.25A2.25 2.25 0 007.5 19.5h9a2.25 2.25 0 002.25-2.25V15a.75.75 0 011.5 0v2.25A3.75 3.75 0 0116.5 21h-9A3.75 3.75 0 013.75 17.25V15a.75.75 0 01.75-.75z" />
                             </svg>
                         </span>
                     </button>
                     <button type="button"
                             @click="modalOpen = true"
                             class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-sky-500 via-sky-400 to-emerald-400 px-5 py-2 text-sm font-semibold text-white shadow-lg transition hover:brightness-110">
                         <span>Tambah Mentor</span>
                         <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-white/25">
                             <img src="{{ asset('icons/plus.svg') }}" alt="Tambah" class="h-4 w-4">
                         </span>
                     </button>
                 </div>
            </div>

            <!-- Desktop view -->
            <div class="hidden md:block relative rounded-3xl border border-slate-200 shadow-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead>
                        <tr class="bg-sky-700">
                            <th scope="col" class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wide text-white">Nama</th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wide text-white">Email</th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wide text-white">No. Telepon</th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wide text-white">Jenis Kelamin</th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wide text-white">Materi</th>
                            <th scope="col" class="px-6 py-4 text-right text-sm font-semibold uppercase tracking-wide text-white">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($mentors as $mentor)
                             <tr class="hover:bg-slate-50" x-data="{ open: false }" :class="{ 'relative z-10': open }">
                                 <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-slate-800">{{ $mentor->name ?? '-' }}</td>
                                 <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">{{ $mentor->email ?? '-' }}</td>
                                 <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">{{ $mentor->no_hp ?? '-' }}</td>
                                 <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">{{ $mentor->jenis_kelamin ?? '-' }}</td>
                                 <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">{{ $mentor->materi ?? '-' }}</td>
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
                                                     data-id="{{ $mentor->id }}"
                                                     data-name="{{ $mentor->name }}"
                                                     data-email="{{ $mentor->email }}"
                                                     data-no_hp="{{ $mentor->no_hp }}"
                                                     data-jenis_kelamin="{{ $mentor->jenis_kelamin }}"
                                                     data-alamat="{{ $mentor->alamat }}"
                                                     data-materi="{{ $mentor->materi }}"
                                                     class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">
                                                 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4">
                                                     <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652l-10.5 10.5a4.5 4.5 0 01-1.897 1.125l-3.562 1.02 1.02-3.562a4.5 4.5 0 011.125-1.897l10.5-10.5z" />
                                                     <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 7.125L16.875 4.5" />
                                                 </svg>
                                                 Edit
                                             </button>
                                             <button type="button"
                                                     @click="open = false; confirmDelete($event.target.dataset)"
                                                     data-id="{{ $mentor->id }}"
                                                     data-nama="{{ $mentor->name }}"
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
                                <td colspan="6" class="px-6 py-12 text-center text-sm text-slate-400">
                                    Belum ada mentor yang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile view -->
            <div class="md:hidden space-y-4">
                @forelse ($mentors as $mentor)
                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-md" x-data="{ open: false }">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="font-semibold text-slate-800">{{ $mentor->name ?? '-' }}</h3>
                                <p class="text-sm text-slate-500">{{ $mentor->email ?? '-' }}</p>
                            </div>
                            <div class="relative flex-shrink-0">
                                <button type="button"
                                        @click="open = !open"
                                        @click.outside="open = false"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-full border text-slate-500 transition hover:border-slate-300">
                                    <span class="sr-only">Aksi</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                                        <path d="M12 7.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Zm0 6a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Zm0 6a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" />
                                    </svg>
                                </button>
                                 <div x-show="open" x-transition.origin.top.right x-cloak class="absolute right-0 top-full z-10 mt-2 w-40 rounded-xl border bg-white py-2 shadow-lg">
                                     <button type="button" @click="open = false; openEditModal($event.target.dataset)" data-id="{{ $mentor->id }}" data-name="{{ $mentor->name }}" data-email="{{ $mentor->email }}" data-no_hp="{{ $mentor->no_hp }}" data-jenis_kelamin="{{ $mentor->jenis_kelamin }}" data-alamat="{{ $mentor->alamat }}" data-materi="{{ $mentor->materi }}" class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm text-slate-600 hover:bg-slate-100">Edit</button>
                                     <button type="button" @click="open = false; confirmDelete($event.target.dataset)" data-id="{{ $mentor->id }}" data-nama="{{ $mentor->name }}" class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm text-rose-500 hover:bg-rose-50">Hapus</button>
                                 </div>
                            </div>
                        </div>
                        <div class="mt-4 space-y-2 border-t border-slate-100 pt-4 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-500">No. Telepon:</span>
                                <span class="font-medium text-slate-700">{{ $mentor->no_hp ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Jenis Kelamin:</span>
                                <span class="font-medium text-slate-700">{{ $mentor->jenis_kelamin ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Materi:</span>
                                <span class="font-medium text-slate-700">{{ $mentor->materi ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-200 p-8 text-center text-sm text-slate-400">
                        Belum ada mentor yang terdaftar.
                    </div>
                @endforelse
             </div>

             @if ($mentors->hasPages())
                 <div class="pt-6">
                     {{ $mentors->links('components.admin.pagination') }}
                 </div>
             @endif
         </section>

         <!-- Modal Import Mentor -->
         <div x-show="importOpen"
              x-cloak
              x-transition.opacity
              class="fixed inset-0 z-[110] flex items-center justify-center bg-slate-900/50 backdrop-blur-md p-4 sm:p-6"
              @click.self="importOpen = false">
             <div x-show="importOpen"
                  x-transition.scale
                  class="w-full max-w-3xl rounded-3txl bg-gradient-to-b from-sky-900 via-sky-800 to-sky-900 p-[1px] shadow-2xl">
                 <div class="rounded-2xl sm:rounded-3xl bg-sky-900/95 max-h-[85vh] overflow-y-auto">
                     <div class="p-4 sm:p-6">
                         <h3 class="text-2xl font-semibold text-white">Download Template</h3>
                         <p class="mt-1 text-slate-200">Unduh templates excel terlebih dahulu sebelum mengimport data</p>

                         <div class="mt-5">
                             <a href="{{ route('admin.mentor.template') }}"
                                class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-yellow-500 via-lime-400 to-emerald-400 px-4 sm:px-5 py-2.5 sm:py-3 text-sm font-semibold text-white shadow-lg transition hover:brightness-110">
                                 Download Template
                                 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                                     <path d="M12 3.75a.75.75 0 01.75.75v8.19l2.47-2.47a.75.75 0 111.06 1.06l-3.75 3.75a.75.75 0 01-1.06 0l-3.75-3.75a.75.75 0 111.06-1.06l2.47 2.47V4.5a.75.75 0 01.75-.75z" />
                                     <path d="M4.5 14.25a.75.75 0 01.75.75v2.25A2.25 2.25 0 007.5 19.5h9a2.25 2.25 0 002.25-2.25V15a.75.75 0 011.5 0v2.25A3.75 3.75 0 0116.5 21h-9A3.75 3.75 0 013.75 17.25V15a.75.75 0 01.75-.75z" />
                                 </svg>
                             </a>
                         </div>
                     </div>

                     <div class="px-4 sm:px-6 pb-4 sm:pb-6">
                         <div class="rounded-2xl sm:rounded-3xl bg-sky-800/70 p-6 sm:p-8 text-center text-slate-100">
                             <div class="mx-auto mb-4 flex h-12 w-12 sm:h-14 sm:w-14 items-center justify-center rounded-2xl bg-sky-700/60">
                                 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-8 w-8">
                                     <path d="M9 8.25a.75.75 0 011.5 0V15l2.47-2.47a.75.75 0 111.06 1.06l-3.75 3.75a.75.75 0 01-1.06 0l-3.75-3.75a.75.75 0 111.06-1.06L9 15V8.25z" />
                                     <path d="M6.75 3A2.25 2.25 0 004.5 5.25v13.5A2.25 2.25 0 006.75 21h10.5A2.25 2.25 0 0019.5 18.75V5.25A2.25 2.25 0 0017.25 3H6.75z" />
                                 </svg>
                             </div>
                             <p class="text-lg">Upload File atau drag and drop</p>
                             <p class="text-sm opacity-80">Excel atau CSV 10MB</p>

                             <form method="POST" action="{{ route('admin.mentor.import') }}" enctype="multipart/form-data" class="mt-6">
                                 @csrf
                                 <label for="import_file" class="block">
                                     <input id="import_file" name="file" type="file" accept=".xlsx,.xls,.csv" class="hidden" @change="importFileName = $event.target.files[0]?.name || ''">
                                     <div class="cursor-pointer rounded-2xl border-2 border-dashed border-sky-600/60 px-4 sm:px-6 py-8 sm:py-10 text-slate-200 hover:border-sky-400/80">
                                         <span x-text="importFileName || 'Pilih file untuk diunggah'"></span>
                                     </div>
                                 </label>

                                 <div class="mt-6 grid grid-cols-1 sm:flex sm:items-center sm:justify-between gap-3">
                                     <button type="button" @click="importOpen = false" class="w-full sm:w-auto rounded-full bg-sky-700 px-8 py-3 font-semibold text-white hover:brightness-110">BATAL</button>
                                     <button type="submit" class="w-full sm:w-auto rounded-full bg-gradient-to-r from-yellow-500 via-lime-400 to-emerald-400 px-8 py-3 font-semibold text-white shadow-lg hover:brightness-110">IMPORT</button>
                                 </div>
                             </form>
                         </div>
                     </div>
                 </div>
             </div>
         </div>

        <!-- Modal Tambah Mentor -->
        <div x-show="modalOpen"
             x-cloak
             x-transition.opacity
             class="fixed inset-0 w-screen h-screen z-[100] flex items-center justify-center bg-slate-900/50 backdrop-blur-md"
             @click.self="modalOpen = false">
            <div x-show="modalOpen"
                 x-transition.scale
                 class="w-full max-w-2xl rounded-3xl bg-gradient-to-b from-sky-700 via-sky-600 to-sky-800 p-[1px] shadow-2xl">
                <div class="rounded-3xl bg-white/95 p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-slate-900">Tambah Mentor Baru</h3>
                        <button type="button" @click="modalOpen = false" class="text-slate-400 transition hover:text-slate-600">✕</button>
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

                    <form method="POST" action="{{ route('admin.mentor.store') }}" class="space-y-4 pt-2">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Nama Lengkap</label>
                                <input id="name" type="text" name="name" value="{{ old('name') }}" required
                                       class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-5 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100" />
                            </div>
                            <div>
                                <label for="email" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Alamat Email</label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                       class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-5 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="no_hp" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">No. Telepon</label>
                                <input id="no_hp" type="tel" name="no_hp" value="{{ old('no_hp') }}" required
                                       class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-5 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100" />
                            </div>
                            <div>
                                <label for="jenis_kelamin" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Jenis Kelamin</label>
                                <select id="jenis_kelamin" name="jenis_kelamin" required
                                        class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-5 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                    <option value="">Pilih</option>
                                    <option value="Laki-laki" @selected(old('jenis_kelamin')==='Laki-laki')>Laki-laki</option>
                                    <option value="Perempuan" @selected(old('jenis_kelamin')==='Perempuan')>Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label for="alamat" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Alamat</label>
                            <textarea id="alamat" name="alamat" rows="2" required
                                      class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-5 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">{{ old('alamat') }}</textarea>
                        </div>
                        <div>
                            <label for="materi" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Materi</label>
                            <input id="materi" type="text" name="materi" value="{{ old('materi') }}" required
                                   class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-5 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100" />
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

        <!-- Modal Edit Mentor -->
        <div x-show="editOpen"
             x-cloak
             x-transition.opacity
             class="fixed inset-0 w-screen h-screen z-[100] flex items-center justify-center bg-slate-900/50 backdrop-blur-md"
             @click.self="closeEditModal()">
            <div x-show="editOpen"
                 x-transition.scale
                 class="w-full max-w-2xl rounded-3xl bg-gradient-to-b from-sky-700 via-sky-600 to-sky-800 p-[1px] shadow-2xl">
                <div class="rounded-3xl bg-white/95 p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-slate-900">Edit Mentor</h3>
                        <button type="button" @click="closeEditModal()" class="text-slate-400 transition hover:text-slate-600">✕</button>
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

                    <form method="POST" :action="`/admin/mentor/${editMeta?.id}`" class="space-y-4 pt-2">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="edit_name" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Nama Lengkap</label>
                                <input id="edit_name" type="text" name="name" :value="editMeta?.name" required
                                       class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-5 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100" />
                            </div>
                            <div>
                                <label for="edit_email" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Alamat Email</label>
                                <input id="edit_email" type="email" name="email" :value="editMeta?.email" required
                                       class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-5 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="edit_no_hp" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">No. Telepon</label>
                                <input id="edit_no_hp" type="tel" name="no_hp" :value="editMeta?.no_hp" required
                                       class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-5 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100" />
                            </div>
                            <div>
                                <label for="edit_jenis_kelamin" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Jenis Kelamin</label>
                                <select id="edit_jenis_kelamin" name="jenis_kelamin" required
                                        class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-5 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                    <option value="">Pilih</option>
                                    <option value="Laki-laki" :selected="editMeta?.jenis_kelamin === 'Laki-laki'">Laki-laki</option>
                                    <option value="Perempuan" :selected="editMeta?.jenis_kelamin === 'Perempuan'">Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label for="edit_alamat" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Alamat</label>
                            <textarea id="edit_alamat" name="alamat" rows="2" required
                                      class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-5 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100" x-text="editMeta?.alamat"></textarea>
                        </div>
                        <div>
                            <label for="edit_materi" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Materi</label>
                            <input id="edit_materi" type="text" name="materi" :value="editMeta?.materi" required
                                   class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-5 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100" />
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

        <!-- Modal Hapus Mentor -->
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
                        Apakah Anda yakin ingin menghapus mentor <strong x-text="editMeta?.nama" class="font-semibold text-slate-800"></strong>?
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

<script>
    function mentorPage() {
        return {
            modalOpen: {{ $errors->any() ? 'true' : 'false' }},
            importOpen: false,
            importFileName: '',
            editOpen: false,
            deleteOpen: false,
            editMeta: null,
            deleteTemplate: '{{ route('admin.mentor.destroy', '__ID__') }}',
            openEditModal(data) {
                console.log('Open edit modal with:', data);
                this.editMeta = {
                    id: data.id,
                    name: data.name,
                    email: data.email,
                    no_hp: data.no_hp,
                    jenis_kelamin: data.jenis_kelamin,
                    alamat: data.alamat,
                    materi: data.materi
                };
                this.editOpen = true;
            },
            closeEditModal() {
                this.editOpen = false;
                this.editMeta = null;
            },
            confirmDelete(data) {
                console.log('Confirm delete called with:', data);
                this.editMeta = { id: data.id, nama: data.nama };
                this.deleteOpen = true;
                console.log('deleteOpen set to true');
            },
            closeDeleteModal() {
                this.deleteOpen = false;
                this.editMeta = null;
            },
            deleteAction() {
                if (!this.editMeta?.id) return '#';
                const action = this.deleteTemplate.replace('__ID__', this.editMeta.id);
                console.log('Delete action URL:', action);
                return action;
            },
        };
    }
</script>

</x-admin.layout>
