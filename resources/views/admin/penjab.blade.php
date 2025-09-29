<x-admin.layout :user="$user" active="penjab" title="Penanggung Jawab">
    <x-slot:sidebar>
        <x-admin.sidebar :user="$user" active="penjab" />
    </x-slot:sidebar>

    <x-admin.topbar :user="$user" title="Penanggung Jawab" />

    <div class="px-6 py-8 lg:px-10 lg:py-10 space-y-8">
        @if (session('status'))
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-[0_30px_55px_-35px_rgba(15,23,42,0.35)]">
            <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Penanggung Jawab</h2>
                    <p class="text-sm text-slate-500">Daftar seluruh penanggung jawab yang sudah terdaftar.</p>
                </div>
            </div>

            <div class="relative rounded-3xl border border-slate-200 shadow-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead>
                        <tr class="bg-sky-700">
                            <th scope="col" class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wide text-white">Nama</th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wide text-white">NIM/NIP</th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wide text-white">Email</th>
                            <th scope="col" class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wide text-white">No. Telepon</th>
                            <th scope="col" class="px-6 py-4 text-right text-sm font-semibold uppercase tracking-wide text-white">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($penjabs as $penjab)
                            <tr class="hover:bg-slate-50">
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-slate-800">
                                    {{ $penjab->user->name ?? '-' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                    {{ $penjab->user->nim_nip ?? '-' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                    {{ $penjab->user->email ?? '-' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                    {{ $penjab->user->phone_number ?? '-' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                    {{-- Actions (edit, delete) can be added here --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-sm text-slate-400">
                                    Belum ada penanggung jawab yang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($penjabs->hasPages())
                <div class="pt-6">
                    {{ $penjabs->links('components.admin.pagination') }}
                </div>
            @endif
        </section>
    </div>
</x-admin.layout>
