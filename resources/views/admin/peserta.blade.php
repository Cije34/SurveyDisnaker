<x-admin.layout :user="$user" active="peserta" title="Peserta">
    <x-slot:sidebar>
        <x-admin.sidebar :user="$user" active="peserta" />
    </x-slot:sidebar>

    <x-admin.topbar :user="$user" title="Peserta" />

    <div class="px-6 py-8 lg:px-10 lg:py-10 space-y-8" x-data="{ modalOpen: {{ $errors->any() ? 'true' : 'false' }} }">
        @if (session('status'))
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-[0_30px_55px_-35px_rgba(15,23,42,0.35)]">
            <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Peserta</h2>
                    <p class="text-sm text-slate-500">Daftar seluruh peserta yang sudah terdaftar.</p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button"
                            @click="modalOpen = true"
                            class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-sky-500 via-sky-400 to-emerald-400 px-5 py-2 text-sm font-semibold text-white shadow-lg transition hover:brightness-110">
                        <span>Tambah Peserta</span>
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-white/25">
                            <img src="{{ asset('icons/plus.svg') }}" alt="Tambah" class="h-4 w-4">
                        </span>
                    </button>
                    <a href="#"
                       class="inline-flex items-center gap-2 rounded-full bg-sky-700 px-5 py-2 text-sm font-semibold text-white shadow-lg transition hover:brightness-110">
                        <span>Import</span>
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-white/25">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                                <path d="M12 3.75a.75.75 0 01.75.75v8.19l2.47-2.47a.75.75 0 111.06 1.06l-3.75 3.75a.75.75 0 01-1.06 0l-3.75-3.75a.75.75 0 111.06-1.06l2.47 2.47V4.5a.75.75 0 01.75-.75z" />
                                <path d="M4.5 14.25a.75.75 0 01.75.75v2.25A2.25 2.25 0 007.5 19.5h9a2.25 2.25 0 002.25-2.25V15a.75.75 0 011.5 0v2.25A3.75 3.75 0 0116.5 21h-9A3.75 3.75 0 013.75 17.25V15a.75.75 0 01.75-.75z" />
                            </svg>
                        </span>
                    </a>
                </div>
            </div>

            <div class="relative rounded-3xl border border-slate-200 shadow-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead>
                        <tr class="bg-sky-700">
                            <th scope="col" class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wide text-white">Nama</th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wide text-white">NIK</th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wide text-white">Asal Instansi</th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wide text-white">Email</th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wide text-white">No. Telepon</th>
                            <th scope="col" class="px-6 py-4 text-right text-sm font-semibold uppercase tracking-wide text-white">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($pesertas as $peserta)
                            <tr class="hover:bg-slate-50">
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-slate-800">{{ $peserta->user->name ?? '-' }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">{{ $peserta->nik ?? '-' }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">{{ $peserta->pendidikan_terakhir ?? '-' }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">{{ $peserta->email ?? '-' }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">{{ $peserta->no_hp ?? '-' }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                    {{-- Actions (edit, delete) can be added here --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-sm text-slate-400">
                                    Belum ada peserta yang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($pesertas->hasPages())
                <div class="pt-6">
                    {{ $pesertas->links('components.admin.pagination') }}
                </div>
            @endif
        </section>

        <!-- Modal Tambah Peserta -->
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
                        <h3 class="text-lg font-semibold text-slate-900">Tambah Peserta Baru</h3>
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

                    <form method="POST" action="{{ route('admin.peserta.store') }}" class="space-y-4 pt-2">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Nama Lengkap</label>
                                <input id="name" type="text" name="name" value="{{ old('name') }}" required
                                       class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-5 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100" />
                            </div>
                            <div>
                                <label for="nik" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">NIK/NIP</label>
                                <input id="nik" type="text" name="nik" value="{{ old('nik') }}" required
                                       class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-5 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="email" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Alamat Email</label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                       class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-5 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100" />
                            </div>
                            <div>
                                <label for="no_hp" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">No. Telepon</label>
                                <input id="no_hp" type="tel" name="no_hp" value="{{ old('no_hp') }}" required
                                       class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-5 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100" />
                            </div>
                        </div>
                        <div>
                            <label for="pendidikan_terakhir" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Pendidikan Terakhir</label>
                            <input id="pendidikan_terakhir" type="text" name="pendidikan_terakhir" value="{{ old('pendidikan_terakhir') }}" required
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
                        <div>
                            <label for="alamat" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Alamat</label>
                            <textarea id="alamat" name="alamat" rows="2" required
                                      class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-5 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">{{ old('alamat') }}</textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Password</label>
                                <input id="password" type="password" name="password" required
                                       class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-5 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100" />
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Konfirmasi Password</label>
                                <input id="password_confirmation" type="password" name="password_confirmation" required
                                       class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-5 py-2.5 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100" />
                            </div>
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
