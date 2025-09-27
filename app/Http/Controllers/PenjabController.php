<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Jawaban;
use App\Models\Kegiatan;
use App\Models\Survey;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PenjabController extends Controller
{
    public function index(Request $request): View
    {
        $kegiatanOptions = Kegiatan::orderBy('nama_kegiatan')->get(['id', 'nama_kegiatan']);

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
            'selectedKegiatanId' => $selectedKegiatanId,
            'selectedKegiatan' => $selectedKegiatan,
            'chartData' => $chartData,
            'upcomingSchedules' => $upcomingSchedules,
        ]);
    }
}
