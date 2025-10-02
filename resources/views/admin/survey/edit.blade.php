<x-admin.layout :user="$user" active="survey" title="Edit Survey">
    <x-slot:sidebar>
        <x-admin.sidebar :user="$user" active="survey" />
    </x-slot:sidebar>

    <x-admin.topbar :user="$user" title="Edit Survey" />

    <div class="px-6 py-8 lg:px-10 lg:py-10 space-y-8" x-data="{
        questions: @js($questions),
        init() {
            if (!Array.isArray(this.questions) || this.questions.length === 0) {
                this.questions = [{ id: null, type: 'choice', pertanyaan: '' }];
            }
        },
        addQuestion() {
            this.questions.push({ id: null, type: 'choice', pertanyaan: '' });
        },
        removeQuestion(index) {
            if (this.questions.length > 1) {
                this.questions.splice(index, 1);
            }
        }
    }">
        @if (session('status'))
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-3 text-sm text-rose-700">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="max-w-4xl mx-auto">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-slate-900">Edit Survey</h1>
                <p class="text-sm text-slate-500 mt-1">Edit survey untuk kegiatan {{ $survey->kegiatan->nama_kegiatan ?? 'N/A' }}.</p>
            </div>

            <form method="POST" action="{{ route('admin.survey.update', $survey->id) }}" class="bg-white rounded-2xl border border-slate-200 shadow-lg p-6 space-y-6">
                @csrf
                @method('PUT')

                <input type="hidden" name="kegiatan_id" value="{{ $survey->kegiatan_id }}">
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-sm text-slate-600">Kegiatan: <strong>{{ $survey->kegiatan->tahunKegiatan->tahun }} - {{ $survey->kegiatan->nama_kegiatan }}</strong></p>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-slate-900">Pertanyaan Survey</h3>
                        <button type="button" @click="addQuestion()" class="inline-flex items-center gap-2 rounded-full bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-emerald-600">
                            <span>Tambah Pertanyaan</span>
                        </button>
                    </div>

                    <template x-for="(question, index) in questions" :key="index">
                        <div class="border border-slate-200 rounded-xl p-4 space-y-4">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-medium text-slate-700">Pertanyaan #<span x-text="index + 1"></span></h4>
                                <button type="button" @click="removeQuestion(index)" :disabled="questions.length === 1" class="text-rose-500 hover:text-rose-700 disabled:opacity-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.72 6.97a.75.75 0 10-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 101.06 1.06L12 13.06l1.72 1.72a.75.75 0 101.06-1.06L13.06 12l1.72-1.72a.75.75 0 10-1.06-1.06L12 10.94l-1.72-1.72z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <input type="hidden" :name="'questions[' + index + '][id]'" :value="question.id ?? ''">
                                <div>
                                    <label :for="'type_' + index" class="block text-sm font-medium text-slate-700 mb-2">Tipe Survey</label>
                                    <select :name="'questions[' + index + '][type]'" :id="'type_' + index" x-model="question.type" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                                        <option value="choice">Pilihan</option>
                                        <option value="text">Teks</option>
                                    </select>
                                </div>

                                <div>
                                    <label :for="'pertanyaan_' + index" class="block text-sm font-medium text-slate-700 mb-2">Pertanyaan</label>
                                    <input type="text" :name="'questions[' + index + '][pertanyaan]'" :id="'pertanyaan_' + index" x-model="question.pertanyaan" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500" placeholder="Masukkan pertanyaan survey">
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('admin.survey.index') }}" class="rounded-full bg-slate-100 px-5 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">Batal</a>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-sky-500 via-sky-400 to-emerald-400 px-5 py-2 text-sm font-semibold text-white shadow-lg transition hover:brightness-110">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</x-admin.layout>
