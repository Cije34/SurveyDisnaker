<x-admin.layout :user="$user" active="kegiatan" title="Kegiatan">
    <x-slot:sidebar>
        <x-admin.sidebar :user="$user" active="kegiatan" />
    </x-slot:sidebar>

    <x-admin.topbar :user="$user" title="Kegiatan" />

    @php
        $shouldOpenCreate = old('mode') === 'create' || $errors->hasBag('createKegiatan');
        $shouldOpenEdit = old('mode') === 'update' || $errors->hasBag('updateKegiatan');
        $createName = $shouldOpenCreate ? old('nama_kegiatan') : '';
        $createYear = $shouldOpenCreate ? old('tahun_kegiatan_id') : '';
        $initialEdit = [
            'id' => $shouldOpenEdit ? old('kegiatan_id') : null,
            'nama_kegiatan' => $shouldOpenEdit ? old('nama_kegiatan') : '',
            'tahun_kegiatan_id' => $shouldOpenEdit && old('tahun_kegiatan_id') !== null
                ? (string) old('tahun_kegiatan_id')
                : '',
        ];
    @endphp

    <div class="px-6 py-8 lg:px-10 lg:py-10 space-y-8"
        x-data="{
            createOpen: @js($shouldOpenCreate),
            editOpen: @js($shouldOpenEdit),
            editTemplate: '{{ route('admin.kegiatan.update', '__ID__') }}',
            editData: @js($initialEdit),
            init() {
                this.editData = this.normalize(this.editData);
            },
            normalize(data) {
                const source = data ?? {};
                const tahun = source.tahun_kegiatan_id ?? '';

                return {
                    id: source.id ?? null,
                    nama_kegiatan: source.nama_kegiatan ?? '',
                    tahun_kegiatan_id: tahun === null || tahun === undefined ? '' : String(tahun),
                };
            },
            editAction() {
                if (!this.editData.id) {
                    return this.editTemplate.replace('__ID__', '');
                }

                return this.editTemplate.replace('__ID__', this.editData.id);
            },
            editAction() {
                if (!this.editData.id) {
                    return this.editTemplate.replace('__ID__', '');
                }

                return this.editTemplate.replace('__ID__', this.editData.id);
            },
            openCreateModal() {
                this.createOpen = true;
            },
            closeCreateModal() {
                this.createOpen = false;
            },
            openEditModal(data) {
                this.editData = this.normalize(data);
                this.editOpen = true;
            },
            closeEditModal() {
                this.editOpen = false;
            },
            submitEdit(event) {
                const form = this.$refs.editForm ?? event?.target;

                if (!form) {
                    return;
                }

                form.action = this.editAction();
                form.submit();
            },
        }">
        @if (session('status'))
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-[0_30px_55px_-35px_rgba(15,23,42,0.35)]">
            <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <h2 class="text-xl font-semibold text-slate-900">List Kegiatan</h2>
                <button type="button"
                        @click="openCreateModal()"
                        class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-sky-500 via-sky-400 to-emerald-400 px-5 py-2 text-sm font-semibold text-white shadow-lg transition hover:brightness-110">
                    <span>Tambah Kegiatan</span>
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-white/25">
                        <img src="{{ asset('icons/plus.svg') }}" alt="Tambah" class="h-4 w-4">
                    </span>
                </button>
            </div>

            <div class="space-y-5">
                @forelse ($years as $year)
                    <article class="rounded-3xl bg-gradient-to-r from-sky-700 via-sky-600 to-sky-700 p-[1px] shadow-lg">
                        <div class="rounded-3xl bg-sky-700/95 px-6 py-5 text-white">
                            <div class="mb-4 flex items-center justify-between">
                                <div>
                                    <p class="text-sm uppercase tracking-wider text-sky-200">Tahun Kegiatan</p>
                                    <h3 class="text-2xl font-semibold">{{ $year->tahun }}</h3>
                                </div>
                                @if ($year->is_active)
                                    <span class="inline-flex items-center gap-2 rounded-full bg-emerald-500/20 px-4 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-100">
                                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-300"></span>
                                        Aktif
                                    </span>
                                @endif
                            </div>

                            @if ($year->kegiatan->isEmpty())
                                <p class="rounded-2xl bg-white/10 px-5 py-6 text-center text-sm text-sky-100/80">
                                    Belum ada kegiatan di tahun {{ $year->tahun }}.
                                </p>
                            @else
                                <ul class="space-y-3">
                                    @foreach ($year->kegiatan as $kegiatan)
                                        <li class="flex flex-col gap-3 rounded-2xl bg-white/10 px-5 py-4 text-slate-50 md:flex-row md:items-center md:justify-between">
                                            <div class="flex items-center gap-3 text-base font-medium">
                                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/15 text-sm font-semibold">
                                                    {{ $loop->iteration }}
                                                </span>
                                                <span class="leading-tight">{{ $kegiatan->nama_kegiatan }}</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <button type="button"
                                                        @click="openEditModal({
                                                            id: {{ $kegiatan->id }},
                                                            nama_kegiatan: @js($kegiatan->nama_kegiatan),
                                                            tahun_kegiatan_id: '{{ $kegiatan->tahun_kegiatan_id === null ? '' : (string) $kegiatan->tahun_kegiatan_id }}',
                                                        })"
                                                        class="inline-flex items-center gap-2 rounded-full border border-white/60 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-white transition hover:bg-white/15">
                                                    Edit
                                                </button>
                                                <form method="POST"
                                                      action="{{ route('admin.kegiatan.destroy', $kegiatan) }}"
                                                      class="inline-flex"
                                                      onsubmit="return confirm('Hapus kegiatan {{ $kegiatan->nama_kegiatan }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="inline-flex items-center gap-2 rounded-full border border-rose-200 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-rose-100 transition hover:bg-rose-500/20">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="rounded-3xl border border-dashed border-slate-300 px-6 py-12 text-center text-slate-400">
                        @if ($yearOptions->isEmpty())
                            Belum ada tahun kegiatan yang terdaftar.
                        @else
                            Belum ada kegiatan yang terdaftar.
                        @endif
                    </div>
                @endforelse

                @if (isset($kegiatanPaginator) && $kegiatanPaginator->hasPages())
                    <div class="pt-6">
                        {{ $kegiatanPaginator->links('components.admin.pagination') }}
                    </div>
                @endif
            </div>
        </section>

        <!-- Modal Tambah -->
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
                        <h3 class="text-lg font-semibold text-slate-900">Tambah Kegiatan</h3>
                        <button type="button" @click="closeCreateModal()" class="text-slate-400 transition hover:text-slate-600">✕</button>
                    </div>

                    <form method="POST" action="{{ route('admin.kegiatan.store') }}" class="space-y-5">
                        @csrf
                        <input type="hidden" name="mode" value="create">
                        <div>
                            <label for="create_nama" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Nama Kegiatan
                            </label>
                            <input id="create_nama"
                                   type="text"
                                   name="nama_kegiatan"
                                   value="{{ $createName }}"
                                   class="mt-3 w-full rounded-full border border-slate-200 bg-slate-50 px-6 py-3 text-base font-semibold text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                                   placeholder="Contoh: Digital Marketing" />
                            @error('nama_kegiatan', 'createKegiatan')
                                <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="create_tahun" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Tahun Kegiatan
                            </label>
                            <select id="create_tahun"
                                    name="tahun_kegiatan_id"
                                    class="mt-3 w-full rounded-full border border-slate-200 bg-slate-50 px-6 py-3 text-base font-semibold text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                <option value="" disabled {{ $createYear ? '' : 'selected' }}>Pilih tahun</option>
                                @foreach ($yearOptions as $option)
                                    <option value="{{ $option->id }}" {{ (string) $option->id === (string) $createYear ? 'selected' : '' }}>
                                        {{ $option->tahun }}{{ $option->is_active ? ' • Aktif' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tahun_kegiatan_id', 'createKegiatan')
                                <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-2">
                            <button type="button" @click="closeCreateModal()"
                                    class="inline-flex items-center gap-2 rounded-full border border-slate-300 px-6 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                                Batal
                            </button>
                            <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-sky-500 via-sky-400 to-emerald-400 px-6 py-2 text-sm font-semibold text-white shadow-lg transition hover:brightness-110">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit -->
        <div x-show="editOpen"
             x-cloak
             x-transition.opacity
             class="fixed inset-0 z-[100] flex h-screen w-screen items-center justify-center bg-slate-900/50 backdrop-blur-md"
             @click.self="closeEditModal()">
            <div x-show="editOpen"
                 x-transition.scale
                 class="w-full max-w-lg rounded-3xl bg-gradient-to-b from-sky-700 via-sky-600 to-sky-800 p-[1px] shadow-2xl">
                <div class="rounded-3xl bg-white/95 p-6" x-data>
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-slate-900">Edit Kegiatan</h3>
                        <button type="button" @click="closeEditModal()" class="text-slate-400 transition hover:text-slate-600">✕</button>
                    </div>

                    <form method="POST"
                          x-ref="editForm"
                          @submit.prevent="submitEdit($event)"
                          class="space-y-5">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="mode" value="update">
                        <input type="hidden" name="kegiatan_id" :value="editData.id">
                        <div>
                            <label for="edit_nama" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Nama Kegiatan
                            </label>
                            <input id="edit_nama"
                                   type="text"
                                   name="nama_kegiatan"
                                   x-model="editData.nama_kegiatan"
                                   class="mt-3 w-full rounded-full border border-slate-200 bg-slate-50 px-6 py-3 text-base font-semibold text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"
                                   placeholder="Contoh: Digital Marketing" />
                            @error('nama_kegiatan', 'updateKegiatan')
                                <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="edit_tahun" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Tahun Kegiatan
                            </label>
                            <select id="edit_tahun"
                                    name="tahun_kegiatan_id"
                                    x-model="editData.tahun_kegiatan_id"
                                    class="mt-3 w-full rounded-full border border-slate-200 bg-slate-50 px-6 py-3 text-base font-semibold text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                <option value="" disabled>Pilih tahun</option>
                                @foreach ($yearOptions as $option)
                                    <option value="{{ $option->id }}">{{ $option->tahun }}{{ $option->is_active ? ' • Aktif' : '' }}</option>
                                @endforeach
                            </select>
                            @error('tahun_kegiatan_id', 'updateKegiatan')
                                <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-2">
                            <button type="button" @click="closeEditModal()"
                                    class="inline-flex items-center gap-2 rounded-full border border-slate-300 px-6 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                                Batal
                            </button>
                            <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-sky-500 via-sky-400 to-emerald-400 px-6 py-2 text-sm font-semibold text-white shadow-lg transition hover:brightness-110">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin.layout>
