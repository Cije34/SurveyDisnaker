<x-perserta.layout :user="$profile ?? $user" active="jadwal">
    <x-slot:sidebar>
        <x-perserta.sidebar :user="$profile ?? $user" active="jadwal" />
    </x-slot:sidebar>

    <x-perserta.topbar :user="$profile ?? $user" />

    <div class="px-8 py-10 space-y-10">
        <x-perserta.schedule-table :items="$jadwal" />
        
    </div>
</x-perserta.layout>
