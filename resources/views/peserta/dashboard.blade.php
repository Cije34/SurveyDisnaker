<x-perserta.layout :user="$profile ?? $user" active="dashboard">
    <x-slot:sidebar>
        <x-perserta.sidebar :user="$profile ?? $user" active="dashboard" />
    </x-slot:sidebar>

    <x-perserta.topbar :user="$profile ?? $user" />

    <div class="px-8 py-10 space-y-10">
        <x-perserta.schedule-table :items="$jadwal" />
        <div class="grid gap-6 md:grid-cols-2">
            @foreach ($surveys as $survey)
                <x-perserta.survey-card
                    :title="$survey['title']"
                    :description="$survey['description']"
                    :action="$survey['action']" />
            @endforeach
        </div>
    </div>
</x-perserta.layout>
