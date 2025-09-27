<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Jawaban;
use App\Models\Kegiatan;
use App\Models\Penjab;
use App\Models\Survey;
use App\Models\Tempat;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PenjabController extends Controller
{
    public function index(Request $request): View
    {
        $kegiatanOptions = Kegiatan::orderBy('nama_kegiatan')->get(['id', 'nama_kegiatan']);
        $penjabOptions = Penjab::orderBy('name')->get(['id', 'name']);
        $tempatOptions = Tempat::orderBy('name')->get(['id', 'name']);

        $selectedKegiatanId = $request->query('kegiatan_id', $kegiatanOptions->first()->id ?? null);
        $selectedKegiatan = $selectedKegiatanId
            ? Kegiatan::with(['surveys'])->find($selectedKegiatanId)
            : null;

        $chartData = [
            'labels' => ['Sangat Baik', 'Baik', 'Cukup Baik', 'Buruk'],
            'values' => [0, 0, 0, 0],
            'total' => 0,
        ];

        if ($selectedKegiatan) {
            $choiceSurveyIds = $selectedKegiatan->surveys
                ->where('type', Survey::TYPE_CHOICE)
                ->pluck('id');

            if ($choiceSurveyIds->isNotEmpty()) {
                $rawCounts = Jawaban::select('jawaban', DB::raw('count(*) as total'))
                    ->whereIn('survey_id', $choiceSurveyIds)
                    ->groupBy('jawaban')
                    ->pluck('total', 'jawaban');

                $total = $rawCounts->sum();
                $chartData['total'] = $total;

                if ($total > 0) {
                    $mapping = collect(['Sangat Baik', 'Baik', 'Cukup Baik', 'Buruk']);

                    $values = $mapping->map(function ($label) use ($rawCounts, $total) {
                        $count = $rawCounts->filter(function ($value, $key) use ($label) {
                            return Str::lower($key) === Str::lower($label);
                        })->first() ?? 0;

                        return round(($count / $total) * 100, 2);
                    });

                    $chartData['values'] = $values->toArray();
                }
            }
        }

        $upcomingSchedules = Jadwal::with(['kegiatan', 'tempat'])
            ->when($selectedKegiatanId, fn ($query) => $query->where('kegiatan_id', $selectedKegiatanId))
            ->whereDate('tanggal_mulai', '>=', now()->startOfDay())
            ->orderBy('tanggal_mulai')
            ->take(5)
            ->get();

        return view('admin.dashboard', [
            'user' => Auth::user(),
            'kegiatanOptions' => $kegiatanOptions,
            'penjabOptions' => $penjabOptions,
            'tempatOptions' => $tempatOptions,
            'selectedKegiatanId' => $selectedKegiatanId,
            'selectedKegiatan' => $selectedKegiatan,
            'chartData' => $chartData,
            'upcomingSchedules' => $upcomingSchedules,
        ]);
    }

    public function storeSchedule(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kegiatan_id' => ['required', 'exists:kegiatans,id'],
            'penjab_id' => ['required', 'exists:penjabs,id'],
            'tempat_id' => ['required', 'exists:tempats,id'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'jam_mulai' => ['required'],
            'jam_selesai' => ['required'],
        ], [], [
            'kegiatan_id' => 'kegiatan',
            'penjab_id' => 'penanggung jawab',
            'tempat_id' => 'tempat',
        ]);

        Jadwal::create([
            'kegiatan_id' => $validated['kegiatan_id'],
            'penjab_id' => $validated['penjab_id'],
            'tempat_id' => $validated['tempat_id'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'jam_mulai' => $validated['jam_mulai'],
            'jam_selesai' => $validated['jam_selesai'],
            'status' => 0,
        ]);

        return back()->with('status', 'Jadwal baru berhasil ditambahkan.');
    }
}
