<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Peserta;
use App\Models\Survey;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PesertaController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $peserta = $user?->peserta;

        if (! $peserta) {
            abort(403, 'Data peserta tidak ditemukan.');
        }

        $jadwal = $this->formatJadwals(
            $peserta->jadwals()
                ->with(['kegiatan', 'tempat'])
                ->orderByDesc('tanggal_mulai')
                ->take(4)
                ->get()
        );

        $surveys = $this->surveyCardsFor($peserta)
            ->take(4)
            ->values();

        return view('peserta.dashboard', [
            'user' => $user,
            'profile' => $peserta,
            'jadwal' => $jadwal,
            'surveys' => $surveys,
        ]);
    }

    public function jadwal(): View
    {
        $user = Auth::user();
        $peserta = $user?->peserta;

        if (! $peserta) {
            abort(403, 'peserta tidak di temukan');
        }

        $jadwal = $this->formatJadwals(
            $peserta->jadwals()
                ->with(['kegiatan', 'tempat'])
                ->orderByDesc('tanggal_mulai')
                ->get()
        );

        return view('peserta.jadwal', [
            'user' => $user,
            'profile' => $peserta,
            'jadwal' => $jadwal,
        ]);
    }

    public function survey(): View
    {
        $user = Auth::user();
        $peserta = $user?->peserta;

        if (! $peserta) {
            abort(403, 'Data peserta tidak ditemukan.');
        }

        $surveys = $this->surveyCardsFor($peserta);

        return view('peserta.survey', [
            'user' => $user,
            'profile' => $peserta,
            'surveys' => $surveys,
        ]);
    }

    /**
     * Format jadwal collection into simple array for views.
     */
    protected function formatJadwals(Collection $jadwals): Collection
    {
        return $jadwals->map(function (Jadwal $jadwal) {
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
    }

    /**
     * Build survey cards for a peserta.
     */
    protected function surveyCardsFor(Peserta $peserta): Collection
    {
        $jadwals = $peserta->jadwals()
            ->with(['mentors', 'kegiatan'])
            ->get();

        $kegiatanIds = $jadwals->pluck('kegiatan_id')->filter()->unique();

        if ($kegiatanIds->isEmpty()) {
            return collect();
        }

        $jadwalsByKegiatan = $jadwals->groupBy('kegiatan_id');

        return Survey::query()
            ->with('kegiatan')
            ->whereIn('kegiatan_id', $kegiatanIds)
            ->withExists([
                'jawabans as has_answer' => fn ($query) => $query->where('peserta_id', $peserta->id),
            ])
            ->get()
            ->map(function (Survey $survey) use ($jadwalsByKegiatan) {
                $relatedJadwal = $jadwalsByKegiatan->get($survey->kegiatan_id)?->sortByDesc('tanggal_selesai')->first();
                $mentors = $relatedJadwal?->mentors->pluck('name')->implode(', ');

                return [
                    'id' => $survey->id,
                    'title' => Str::upper($survey->kegiatan->nama_kegiatan ?? 'Survey'),
                    'description' => Str::limit($survey->pertanyaan, 120),
                    'deadline' => $relatedJadwal?->tanggal_selesai?->format('d-m-Y'),
                    'mentor' => $mentors ?: '-',
                    'status' => $survey->has_answer ? 'completed' : 'pending',
                    'action' => '#',
                ];
            })->values();
    }
}
