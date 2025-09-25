@props([
    'items' => collect(),
    'title' => 'Jadwal Kegiatan',
])

@php
    $scheduleUrl = \Illuminate\Support\Facades\Route::has('peserta.jadwal')
        ? route('peserta.jadwal')
        : '#';
@endphp

<section class="rounded-2x4  bg-white p-6 shadow-[0_35px_55px_-30px_rgba(15,23,42,0.35)]">
    <header class="mb-5 flex items-center justify-between">
        <h2 class="text-3xl font-semibold text-slate-900">{{ $title }}</h2>
        <a href="{{ $scheduleUrl }}"
           class="text-sm font-medium text-sky-600 hover:text-sky-700">
            Lihat semua
        </a>
    </header>

    <div class="overflow-hidden rounded-3xl border border-slate-200">
        <table class="min-w-full border-collapse text-sm text-slate-700">
            <thead class="bg-sky-700 text-white text-left">
                <tr>
                    <th scope="col" class="px-6 py-4 font-semibold">Kegiatan</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Tanggal</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Waktu</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Lokasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $index => $item)
                    <tr @class([
                        'bg-white' => $index % 2 === 0,
                        'bg-slate-100' => $index % 2 === 1,
                    ])>
                        <td class="px-6 py-4 font-medium text-slate-800">{{ $item['kegiatan'] ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $item['tanggal'] ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $item['waktu'] ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $item['lokasi'] ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-6 text-center text-slate-400">
                            Belum ada jadwal kegiatan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
