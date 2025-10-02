<x-admin.layout :user="$user" active="jadwal" title="Tambah Jadwal">
    <x-slot:sidebar>
        <x-admin.sidebar :user="$user" active="jadwal" />
    </x-slot:sidebar>

    <x-admin.topbar :user="$user" title="Tambah Jadwal" />

    <div class="px-6 py-8 lg:px-10 lg:py-10 space-y-8" x-data="{ pesertaSearch: '', mentorSearch: '' }">
        @if (session('status'))
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-[0_30px_55px_-35px_rgba(15,23,42,0.35)]">
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-slate-900">Tambah Jadwal Baru</h2>
                <p class="text-sm text-slate-500">Isi form di bawah untuk menambah jadwal kegiatan baru.</p>
            </div>

            <form method="POST" action="{{ route('admin.jadwal.store') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="kegiatan_id" class="mb-1 block text-sm font-medium text-slate-700">Kegiatan</label>
                        <select name="kegiatan_id" id="kegiatan_id" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                            <option value="">Pilih Kegiatan</option>
                            @foreach($kegiatanOptions as $kegiatan)
                                <option value="{{ $kegiatan->id }}" @selected(old('kegiatan_id') == $kegiatan->id)> {{ $kegiatan->tahunKegiatan->tahun }} - {{ $kegiatan->nama_kegiatan }}</option>
                            @endforeach
                        </select>
                        @error('kegiatan_id')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
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
                        <div class="space-y-2 max-h-32 overflow-y-auto border border-slate-200 rounded-xl p-2 bg-slate-50">
                            @foreach($penjabOptions as $penjab)
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox"
                                           value="{{ $penjab->id }}"
                                           @checked(in_array($penjab->id, old('penjab_ids', [])))
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

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="tempat_id" class="mb-1 block text-sm font-medium text-slate-700">Lokasi</label>
                        <select name="tempat_id" id="tempat_id" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                            <option value="">Pilih Lokasi</option>
                            @foreach($tempatOptions as $tempat)
                                <option value="{{ $tempat->id }}" @selected(old('tempat_id') == $tempat->id)>{{ $tempat->name }}</option>
                            @endforeach
                        </select>
                        @error('tempat_id')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                     <div>
                         <label class="mb-1 block text-sm font-medium text-slate-700">Mentor</label>
                         <input type="text" x-model="mentorSearch" placeholder="Cari mentor..." class="mb-2 w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                         <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 max-h-32 overflow-y-auto border border-slate-200 rounded-xl p-2 bg-slate-50">
                             @foreach($mentorOptions as $mentor)
                                 <label x-show="mentorSearch === '' || '{{ $mentor->name }}'.toLowerCase().includes(mentorSearch.toLowerCase())" class="flex items-center gap-2 cursor-pointer">
                                     <input type="checkbox"
                                            value="{{ $mentor->id }}"
                                            @checked(in_array($mentor->id, old('mentor_ids', [])))
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
                     <div>
                         <label class="mb-1 block text-sm font-medium text-slate-700">Peserta</label>
                         <input type="text" x-model="pesertaSearch" placeholder="Cari peserta..." class="mb-2 w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                         <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 max-h-32 overflow-y-auto border border-slate-200 rounded-xl p-2 bg-slate-50">
                             @foreach($pesertaOptions as $peserta)
                                 <label x-show="pesertaSearch === '' || '{{ $peserta->name }}'.toLowerCase().includes(pesertaSearch.toLowerCase())" class="flex items-center gap-2 cursor-pointer">
                                     <input type="checkbox"
                                            value="{{ $peserta->id }}"
                                            @checked(in_array($peserta->id, old('peserta_ids', [])))
                                            name="peserta_ids[]"
                                            class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                                     <span class="text-sm text-slate-700">{{ $peserta->name }}</span>
                                 </label>
                             @endforeach
                        </div>
                        @error('peserta_ids')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                        @error('peserta_ids.*')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div>
                        <label for="tanggal_mulai" class="mb-1 block text-sm font-medium text-slate-700">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                        @error('tanggal_mulai')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="tanggal_selesai" class="mb-1 block text-sm font-medium text-slate-700">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                        @error('tanggal_selesai')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="jam_mulai" class="mb-1 block text-sm font-medium text-slate-700">Jam Mulai</label>
                        <input type="time" name="jam_mulai" id="jam_mulai" value="{{ old('jam_mulai') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                        @error('jam_mulai')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="jam_selesai" class="mb-1 block text-sm font-medium text-slate-700">Jam Selesai</label>
                        <input type="time" name="jam_selesai" id="jam_selesai" value="{{ old('jam_selesai') }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                        @error('jam_selesai')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <a href="{{ route('admin.jadwal.index') }}" class="rounded-full bg-slate-100 px-5 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">Batal</a>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-sky-500 via-sky-400 to-emerald-400 px-5 py-2 text-sm font-semibold text-white shadow-lg transition hover:brightness-110">Simpan Jadwal</button>
                </div>
            </form>
        </section>
    </div>

    <script>
        // Initialize kegiatan auto-populate
        const kegiatanData = {!! json_encode($kegiatanOptions->mapWithKeys(function ($k) {
            return [$k->id => [
                'tahun' => $k->tahunKegiatan->tahun ?? '-',
                'is_active' => $k->tahunKegiatan->is_active ?? false
            ]];
        })) !!};

        const createKegiatanSelect = document.getElementById('kegiatan_id');
        const updateCreateKegiatanInfo = (selectedId) => {
            const data = kegiatanData[selectedId] || { tahun: '-', is_active: false };
            document.getElementById('tahun_kegiatan_display').value = data.tahun;
            const isActiveCheckbox = document.getElementById('is_active_display');
            const isActiveText = document.getElementById('is_active_text');
            isActiveCheckbox.checked = data.is_active;
            isActiveText.textContent = data.is_active ? 'Aktif' : 'Tidak Aktif';
        };

        createKegiatanSelect?.addEventListener('change', function() {
            updateCreateKegiatanInfo(this.value);
        });

        if (createKegiatanSelect && createKegiatanSelect.value) {
            updateCreateKegiatanInfo(createKegiatanSelect.value);
        }
    </script>
</x-admin.layout>
