<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\Survey;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{
    public function index()
    {
        $surveyGroups = Kegiatan::query()
            ->select('kegiatans.*')
            ->join('tahun_kegiatans', 'tahun_kegiatans.id', '=', 'kegiatans.tahun_kegiatan_id')
            ->with([
                'tahunKegiatan:id,tahun',
                'surveys:id,kegiatan_id,type,pertanyaan,created_at',
            ])
            ->withCount('surveys')
            ->whereHas('surveys')
            ->orderByDesc('tahun_kegiatans.tahun')
            ->orderByDesc('kegiatans.created_at')
            ->paginate(9);

        return view('admin.survey', [
            'user' => Auth::user(),
            'surveyGroups' => $surveyGroups,
        ]);
    }

    public function create()
    {
        $kegiatanOptions = Kegiatan::with('tahunKegiatan:id,tahun,is_active')
            ->join('tahun_kegiatans', 'kegiatans.tahun_kegiatan_id', '=', 'tahun_kegiatans.id')
            ->orderByDesc('tahun_kegiatans.tahun')
            ->orderBy('kegiatans.nama_kegiatan')
            ->get(['kegiatans.id', 'kegiatans.nama_kegiatan', 'kegiatans.tahun_kegiatan_id']);

        return view('admin.survey.create', [
            'user' => Auth::user(),
            'kegiatanOptions' => $kegiatanOptions,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kegiatan_id' => ['required', 'exists:kegiatans,id'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.type' => ['required', 'in:choice,text'],
            'questions.*.pertanyaan' => ['required', 'string', 'max:1000'],
        ]);

        foreach ($validated['questions'] as $question) {
            Survey::create([
                'kegiatan_id' => $validated['kegiatan_id'],
                'type' => $question['type'],
                'pertanyaan' => $question['pertanyaan'],
            ]);
        }

        return redirect()->route('admin.survey.index')->with('status', count($validated['questions']) . ' survey baru berhasil ditambahkan.');
    }

    public function edit(Survey $survey)
    {
        $survey->load(['kegiatan.tahunKegiatan', 'kegiatan.surveys' => fn ($query) => $query->orderBy('created_at')]);

        $questions = collect($survey->kegiatan->surveys)
            ->map(fn (Survey $item) => [
                'id' => $item->id,
                'type' => $item->type,
                'pertanyaan' => $item->pertanyaan,
            ])
            ->toArray();

        if (empty($questions)) {
            $questions = [[
                'id' => null,
                'type' => Survey::TYPE_CHOICE,
                'pertanyaan' => '',
            ]];
        }

        return view('admin.survey.edit', [
            'user' => Auth::user(),
            'survey' => $survey,
            'questions' => old('questions', $questions),
        ]);
    }

    public function update(Request $request, Survey $survey): RedirectResponse
    {
        $validated = $request->validate([
            'kegiatan_id' => ['required', 'exists:kegiatans,id'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.id' => ['nullable', 'integer'],
            'questions.*.type' => ['required', 'in:choice,text'],
            'questions.*.pertanyaan' => ['required', 'string', 'max:1000'],
        ]);

        $kegiatan = $survey->kegiatan()->with('surveys')->firstOrFail();

        $existingSurveys = $kegiatan->surveys->keyBy('id');

        $submittedIds = collect($validated['questions'])
            ->pluck('id')
            ->filter()
            ->unique();

        $invalidIds = $submittedIds->diff($existingSurveys->keys());

        if ($invalidIds->isNotEmpty()) {
            return back()
                ->withErrors(['questions' => 'Pertanyaan tidak valid untuk kegiatan ini.'])
                ->withInput();
        }

        $handledSurveyIds = [];

        DB::transaction(function () use ($validated, $existingSurveys, &$handledSurveyIds) {
            foreach ($validated['questions'] as $question) {
                $questionId = $question['id'] ?? null;

                if ($questionId && $existingSurveys->has($questionId)) {
                    $existingSurveys[$questionId]->update([
                        'kegiatan_id' => $validated['kegiatan_id'],
                        'type' => $question['type'],
                        'pertanyaan' => $question['pertanyaan'],
                    ]);

                    $handledSurveyIds[] = $questionId;

                    continue;
                }

                $newSurvey = Survey::create([
                    'kegiatan_id' => $validated['kegiatan_id'],
                    'type' => $question['type'],
                    'pertanyaan' => $question['pertanyaan'],
                ]);

                $handledSurveyIds[] = $newSurvey->id;
            }
        });

        $handledSurveyIds = collect($handledSurveyIds);

        $surveyIdsToDelete = $existingSurveys->keys()->diff($handledSurveyIds);

        if ($surveyIdsToDelete->isNotEmpty()) {
            Survey::whereIn('id', $surveyIdsToDelete)->delete();
        }

        return redirect()->route('admin.survey.index')->with('status', 'Survey berhasil diperbarui.');
    }

    public function destroy(Survey $survey): RedirectResponse
    {
        $survey->delete();

        return redirect()
            ->route('admin.survey.index')
            ->with('status', 'Survey berhasil dihapus.');
    }

    public function answers(Survey $survey)
    {
        $jawabans = $survey->jawabans()->with('peserta')->get();

        return view('admin.survey.answers', [
            'user' => Auth::user(),
            'survey' => $survey,
            'jawabans' => $jawabans,
        ]);
    }

    public function close(Survey $survey): RedirectResponse
    {
        $survey->update(['is_active' => false]); // Asumsikan ada field is_active

        return redirect()->route('admin.survey.index')->with('status', 'Survey berhasil ditutup.');
    }
}
