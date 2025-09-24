<<<<<<< HEAD
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
=======
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
>>>>>>> 843bcfc (install spatie)
