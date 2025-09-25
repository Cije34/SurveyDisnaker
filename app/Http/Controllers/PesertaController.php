<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Survey;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PesertaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $peserta = $user?->peserta;

        if (! $peserta) {
            abort(403, 'Data peserta tidak ditemukan.');
        }

        $jadwal = $peserta->jadwals()
            ->with(['kegiatan', 'tempat'])
            ->orderByDesc('tanggal_mulai')
            ->take(4)
            ->get()
            ->map(function (Jadwal $jadwal) {
                $waktuMulai = $jadwal->jam_mulai ? substr($jadwal->jam_mulai, 0, 5) : null;
                $waktuSelesai = $jadwal->jam_selesai ? substr($jadwal->jam_selesai, 0, 5) : null;

                return [
                    'kegiatan' => $jadwal->kegiatan->nama_kegiatan ?? '-',
                    'tanggal' => $jadwal->tanggal_mulai?->format('d M Y') ?? '-',
                    'waktu' => $waktuMulai && $waktuSelesai
                        ? $waktuMulai.' - '.$waktuSelesai
                        : ($waktuMulai ?? '-'),
                    'lokasi' => $jadwal->tempat->name ?? '-',
                ];
            });

        $surveys = Survey::query()
            ->latest()
            ->take(4)
            ->get()
            ->map(fn (Survey $survey) => [
                'title' => $survey->pertanyaan,
                'description' => Str::limit($survey->pertanyaan, 120),
                'action' => '#',
            ]);

        return view('peserta.dashboard', [
            'user' => $user,
            'profile' => $peserta,
            'jadwal' => $jadwal,
            'surveys' => $surveys,
        ]);
    }

    public function jadwal()
    {

        $user = Auth::user();
        $peserta = $user?->peserta;

        if (! $peserta) {
            abort(403, 'peserta tidak di temukan');
        }

        $jadwal = $peserta->jadwals()->get();
        // ->with(['kegiatan', 'tempat'])
        // ->orderByDesc('tanggal_mulai')
        // ->get()
        // ->map(function (Jadwal $jadwal) {
        //     $waktuMulai = $jadwal->jam_mulai ? substr($jadwal->jam_mulai, 0, 5) : null;
        //     $waktuSelesai = $jadwal->jam_selesai ? substr($jadwal->jam_selesai, 0, 5) : null;

        //     return [
        //         'kegiatan' => $jadwal->kegiatan->nama_kegiatan ?? '-',
        //         'tanggal' => $jadwal->tanggal_mulai?->format('d M Y') ?? '-',
        //         'waktu' => $waktuMulai && $waktuSelesai
        //             ? $waktuMulai.' - '.$waktuSelesai
        //             : ($waktuMulai ?? '-'),
        //         'lokasi' => $jadwal->tempat->name ?? '-',
        //     ];
        // });

        return view('peserta.jadwal',
            [
                'user' => $user,
                'profile' => $peserta,
                'jadwal' => $jadwal,
            ]
        );
    }
}
