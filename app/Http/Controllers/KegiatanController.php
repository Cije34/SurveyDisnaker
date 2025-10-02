<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\TahunKegiatan;
use Carbon\CarbonInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class KegiatanController extends Controller
{
    public function index(Request $request): View
    {
        $paginatedKegiatan = Kegiatan::with('tahunKegiatan')
            ->join('tahun_kegiatans', 'kegiatans.tahun_kegiatan_id', '=', 'tahun_kegiatans.id')
            ->orderByDesc('tahun_kegiatans.tahun')
            ->orderByDesc('kegiatans.created_at')
            ->select('kegiatans.*')
            ->paginate(5);

        $kegiatanCollection = $paginatedKegiatan->getCollection();

        $years = $kegiatanCollection
            ->map(function (Kegiatan $kegiatan) {
                return $kegiatan->tahunKegiatan;
            })
            ->filter()
            ->unique('id')
            ->values()
            ->map(function (TahunKegiatan $year) use ($kegiatanCollection) {
                $year->setRelation(
                    'kegiatan',
                    $kegiatanCollection
                        ->where('tahun_kegiatan_id', $year->id)
                        ->values()
                );

                return $year;
            });

        $unassignedKegiatan = $kegiatanCollection->whereNull('tahun_kegiatan_id');

        if ($unassignedKegiatan->isNotEmpty()) {
            $years->push((object) [
                'id' => null,
                'tahun' => 'Tanpa Tahun',
                'is_active' => false,
                'kegiatan' => $unassignedKegiatan->values(),
            ]);
        }

        $years = $years->sortByDesc(function ($year) {
            $kegiatan = collect($year->kegiatan ?? []);
            $latest = $kegiatan->max('created_at');

            if ($latest instanceof CarbonInterface) {
                return $latest->getTimestamp();
            }

            if (is_string($latest)) {
                $timestamp = strtotime($latest);

                return $timestamp ?: PHP_INT_MIN;
            }

            if (is_numeric($latest)) {
                return (int) $latest;
            }

            return PHP_INT_MIN;
        })->values();

        $yearOptions = TahunKegiatan::where('is_active', true)->orderByDesc('tahun')->get(['id', 'tahun', 'is_active']);

        return view('admin.kegiatan', [
            'user' => Auth::user(),
            'years' => $years,
            'yearOptions' => $yearOptions,
            'kegiatanPaginator' => $paginatedKegiatan,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = Validator::make($request->all(), [
            'nama_kegiatan' => ['required', 'string', 'max:255'],
            'tahun_kegiatan_id' => ['required', 'exists:tahun_kegiatans,id'],
        ], [], [
            'nama_kegiatan' => 'nama kegiatan',
            'tahun_kegiatan_id' => 'tahun kegiatan',
        ])->validateWithBag('createKegiatan');

        Kegiatan::create($validated);

        return back()->with('status', 'Kegiatan baru berhasil ditambahkan.');
    }

    public function update(Request $request, Kegiatan $kegiatan): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'nama_kegiatan' => ['required', 'string', 'max:255'],
            'tahun_kegiatan_id' => ['required', 'exists:tahun_kegiatans,id'],
            'kegiatan_id' => ['nullable', 'integer'],
        ], [], [
            'nama_kegiatan' => 'nama kegiatan',
            'tahun_kegiatan_id' => 'tahun kegiatan',
        ]);

        $validator->validateWithBag('updateKegiatan');

        $kegiatan->update($validator->safe()->only(['nama_kegiatan', 'tahun_kegiatan_id']));

        return back()->with('status', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan): RedirectResponse
    {
        $kegiatan->delete();

        return back()->with('status', 'Kegiatan berhasil dihapus.');
    }
}
