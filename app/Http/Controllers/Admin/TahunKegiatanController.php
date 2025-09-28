<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunKegiatan;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TahunKegiatanController extends Controller
{
    public function index(Request $request): View
    {
        $years = TahunKegiatan::orderByDesc('tahun')->get();

        return view('admin.tahun-kegiatan', [
            'user' => Auth::user(),
            'years' => $years,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tahun' => ['required', 'digits:4', 'integer', 'unique:tahun_kegiatans,tahun'],
        ], [], [
            'tahun' => 'tahun kegiatan',
        ]);

        TahunKegiatan::query()->update(['is_active' => false]);

        TahunKegiatan::create([
            'tahun' => $validated['tahun'],
            'is_active' => true,
        ]);

        return back()->with('status', 'Tahun kegiatan baru berhasil ditambahkan.');
    }

    public function destroy(TahunKegiatan $tahunKegiatan): RedirectResponse
    {
        $wasActive = $tahunKegiatan->is_active;
        $tahunKegiatan->delete();

        if ($wasActive) {
            $latest = TahunKegiatan::orderByDesc('tahun')->first();
            if ($latest) {
                $latest->update(['is_active' => true]);
            }
        }

        return back()->with('status', 'Tahun kegiatan berhasil dihapus.');
    }
}
