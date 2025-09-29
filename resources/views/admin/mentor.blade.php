<x-admin.layout :user="$user" active="mentor" title="Mentor">
    <x-slot:sidebar>
        <x-admin.sidebar :user="$user" active="mentor" />
    </x-slot:sidebar>

    <x-admin.topbar :user="$user" title="Mentor" />

    <div class="px-6 py-8 lg:px-10 lg:py-10 space-y-8" x-data="{ modalOpen: {{ $errors->any() ? 'true' : 'false' }} }">
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
                <button type="button"
                        @click="modalOpen = true"
                        class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-sky-500 via-sky-400 to-emerald-400 px-5 py-2 text-sm font-semibold text-white shadow-lg transition hover:brightness-110">
                    <span>Tambah Mentor</span>
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-white/25">
                        <img src="{{ asset('icons/plus.svg') }}" alt="Tambah" class="h-4 w-4">
                    </span>
                </button>
            </div>

            <div class="relative rounded-3xl border border-slate-200 shadow-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead>
                        <tr class="bg-sky-700">
                            <th scope="col" class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wide text-white">Nama Mentor</th>
                            <th scope="col" class="px-6 py-4 text-right text-sm font-semibold uppercase tracking-wide text-white">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($mentors as $mentor)
                            <tr class="hover:bg-slate-50">
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-slate-800">
                                    {{ $mentor->name ?? '-' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                    {{-- Actions (edit, delete) can be added here --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-6 py-12 text-center text-sm text-slate-400">
                                    Belum ada mentor yang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($mentors->hasPages())
                <div class="pt-6">
                    {{ $mentors->links('components.admin.pagination') }}
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
                        <h3 class="text-lg font-semibold text-slate-900">Tambah Mentor</h3>
                        <button type="button" @click="modalOpen = false" class="text-slate-400 transition hover:text-slate-600">✕</button>
                    </div>

                    <form method="POST" action="{{ route('admin.mentor.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="name" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Nama Mentor</label>
                            <input id="name" type="text" name="name"
                                   value="{{ old('name') }}"
                                   class="mt-3 w-full rounded-full border border-slate-200 bg-slate-50 px-6 py-3 text-base font-semibold text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                                   placeholder="Contoh: John Doe" />
                            @error('name')
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
