<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Kegiatan;
use App\Models\Peserta;
use App\Models\Survey;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    public function showSurvey(Kegiatan $kegiatan): View
    {
        $user = Auth::user();
        $peserta = $user?->peserta;

        if (! $peserta) {
            abort(403, 'Data peserta tidak ditemukan.');
        }

        $this->authorizeKegiatanSurvey($peserta, $kegiatan);

        $this->abortIfSurveyClosed($kegiatan);

        $kegiatan->load(['surveys' => function ($query) use ($peserta) {
            $query->with(['jawabans' => fn ($q) => $q->where('peserta_id', $peserta->id)]);
        }]);

        $kegiatan->setRelation('surveys', $kegiatan->surveys->sortBy('id')->values());

        return view('peserta.survey-form', [
            'user' => $user,
            'profile' => $peserta,
            'kegiatan' => $kegiatan,
        ]);
    }

    public function submitSurvey(Request $request, Kegiatan $kegiatan): RedirectResponse
    {
        $user = Auth::user();
        $peserta = $user?->peserta;

        if (! $peserta) {
            abort(403, 'Data peserta tidak ditemukan.');
        }

        $this->authorizeKegiatanSurvey($peserta, $kegiatan);
        $this->abortIfSurveyClosed($kegiatan);

        $kegiatan->load('surveys');
        $kegiatan->setRelation('surveys', $kegiatan->surveys->sortBy('id')->values());

        if ($kegiatan->surveys->isEmpty()) {
            return redirect()->route('peserta.survey')->with('status', 'Belum ada pertanyaan untuk kegiatan ini.');
        }

        $rules = [];

        $rules['answers'] = ['required', 'array'];

        foreach ($kegiatan->surveys as $survey) {
            $field = 'answers.'.$survey->id;

            if ($survey->type === Survey::TYPE_CHOICE) {
                $rules[$field] = ['required', 'in:Sangat Baik,Baik,Cukup Baik,Buruk'];
            } else {
                $rules[$field] = ['required', 'string', 'max:5000'];
            }
        }

        $validated = $request->validate($rules);

        foreach ($kegiatan->surveys as $survey) {
            $survey->jawabans()->updateOrCreate(
                ['peserta_id' => $peserta->id],
                [
                    'jawaban' => $validated['answers'][$survey->id],
                    'tipe' => $survey->type,
                ]
            );
        }

        return redirect()
            ->route('peserta.survey')
            ->with('status', 'Terima kasih, jawaban Anda telah disimpan.');
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
            ->with(['mentors', 'kegiatan.surveys' => fn ($query) => $query->with(['jawabans' => fn ($q) => $q->where('peserta_id', $peserta->id)])])
            ->get();

        return $jadwals
            ->groupBy('kegiatan_id')
            ->filter(fn ($group, $key) => $key !== null && optional($group->first())->kegiatan?->surveys->isNotEmpty())
            ->map(function ($group) use ($peserta) {
                $jadwal = $group->sortByDesc('tanggal_selesai')->first();
                $kegiatan = $jadwal->kegiatan;
                $surveys = $kegiatan->surveys;

                $completed = $surveys->every(fn (Survey $survey) => $survey->jawabans->isNotEmpty());
                $isClosed = $surveys->isNotEmpty() && $surveys->every(fn (Survey $survey) => $survey->is_active === false);

                return [
                    'id' => $kegiatan->id,
                    'title' => Str::upper($kegiatan->nama_kegiatan ?? 'Survey'),
                    'description' => Str::limit($surveys->first()?->pertanyaan ?? 'Survey kegiatan', 120),
                    'deadline' => $jadwal->tanggal_selesai?->format('d-m-Y'),
                    'mentor' => $group->flatMap(function ($item) {
                        return $item->mentors ?? collect();
                    })->pluck('name')->unique()->implode(', ') ?: '-',
                    'status' => $isClosed ? 'closed' : ($completed ? 'completed' : 'pending'),
                    'action' => $isClosed ? null : route('peserta.survey.show', $kegiatan->id),
                ];
            })->values();
    }

    protected function authorizeKegiatanSurvey(Peserta $peserta, Kegiatan $kegiatan): void
    {
        $eligible = $peserta->jadwals()->where('kegiatan_id', $kegiatan->id)->exists();

        abort_unless($eligible, 403, 'Survey tidak tersedia untuk peserta ini.');
    }

    protected function abortIfSurveyClosed(Kegiatan $kegiatan): void
    {
        $kegiatan->loadMissing('surveys');

        $isClosed = $kegiatan->surveys->isNotEmpty() && $kegiatan->surveys->every(fn (Survey $survey) => $survey->is_active === false);

        abort_if($isClosed, 403, 'Survey sudah ditutup.');
    }
}
