<x-perserta.layout :user="$profile ?? $user" active="survey">
        <x-slot:sidebar>
            <x-perserta.sidebar :user="$profile ?? $user" active="survey" />
        </x-slot:sidebar>

        <x-perserta.topbar :user="$profile ?? $user" />
        <div class="px-8 py-10">
            <div class="mb-8">
                <h2 class="text-3xl font-semibold text-slate-900">Survey</h2>
                <p class="mt-1 text-sm text-slate-500">Pilih pelatihan yang ingin Anda berikan umpan balik.</p>
            </div>

            @if ($surveys->isNotEmpty())
                <div class="space-y-6">
                    @foreach ($surveys as $survey)
                        <x-perserta.survey-card
                            :title="$survey['title']"
                            :description="$survey['description']"
                            :deadline="$survey['deadline']"
                            :mentor="$survey['mentor']"
                            :status="$survey['status']"
                            :action="$survey['action']" />
                    @endforeach
                </div>
            @else
                <div class="flex min-h-[200px] items-center justify-center rounded-3xl border border-dashed border-slate-200 bg-white text-lg font-semibold text-slate-300">
                    Survey belum tersedia.
                </div>
            @endif
        </div>
</x-perserta.layout>
