<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Kegiatan;
use App\Models\Mentor;
use App\Models\Penjab;
use App\Models\Tempat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class JadwalController extends Controller
{
    public function index(): View
    {
        $schedules = Jadwal::with(['kegiatan.tahunKegiatan', 'tempat:id,name', 'mentors:id,name', 'penjabs:id,name', 'pesertas:id,name'])
            ->select('jadwals.*')
            ->join('kegiatans', 'jadwals.kegiatan_id', '=', 'kegiatans.id')
            ->join('tahun_kegiatans', 'kegiatans.tahun_kegiatan_id', '=', 'tahun_kegiatans.id')
            ->orderByDesc('tahun_kegiatans.tahun')
            ->orderByDesc('jadwals.tanggal_mulai')
            ->paginate(5);

        $kegiatanOptions = Kegiatan::orderBy('nama_kegiatan')->get(['id', 'nama_kegiatan']);
        $penjabOptions = Penjab::orderBy('name')->get(['id', 'name']);
        $tempatOptions = Tempat::orderBy('name')->get(['id', 'name']);
        $mentorOptions = Mentor::orderBy('name')->get(['id', 'name']);
        // dd($schedules);

        return view('admin.jadwal', [
            'user' => Auth::user(),
            'schedules' => $schedules,
            'kegiatanOptions' => $kegiatanOptions,
            'penjabOptions' => $penjabOptions,
            'tempatOptions' => $tempatOptions,
            'mentorOptions' => $mentorOptions,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kegiatan_id' => ['required', 'exists:kegiatans,id'],
            'penjab_ids' => ['required', 'array', 'min:1'],
            'penjab_ids.*' => ['integer', 'exists:penjabs,id'],
            'tempat_id' => ['required', 'exists:tempats,id'],
            'mentor_ids' => ['required', 'array', 'min:1'],
            'mentor_ids.*' => ['integer', 'exists:mentors,id'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'jam_mulai' => ['nullable', 'date_format:H:i'],
            'jam_selesai' => ['nullable', 'date_format:H:i'],
        ]);
        // dd($validated);
        $jadwalData = collect($validated)->except(['mentor_ids', 'penjab_ids'])->all();
        $jadwal = Jadwal::create($jadwalData);
        $jadwal->mentors()->attach($validated['mentor_ids']);
        $jadwal->penjabs()->attach($validated['penjab_ids']);

        return redirect()->route('admin.jadwal.index')->with('status', 'Jadwal baru berhasil ditambahkan.');
    }

    public function update(Request $request, Jadwal $jadwal): RedirectResponse
    {
        $validated = $request->validate([
            'kegiatan_id' => ['required', 'exists:kegiatans,id'],
            'penjab_ids' => ['required', 'array', 'min:1'],
            'penjab_ids.*' => ['integer', 'exists:penjabs,id'],
            'tempat_id' => ['required', 'exists:tempats,id'],
            'mentor_ids' => ['required', 'array', 'min:1'],
            'mentor_ids.*' => ['integer', 'exists:mentors,id'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'jam_mulai' => ['nullable', 'date_format:H:i'],
            'jam_selesai' => ['nullable', 'date_format:H:i'],
        ]);

        $jadwalData = collect($validated)->except(['mentor_ids', 'penjab_ids'])->all();
        $jadwal->update($jadwalData);
        $jadwal->mentors()->sync($validated['mentor_ids']);
        $jadwal->penjabs()->sync($validated['penjab_ids']);

        return redirect()->route('admin.jadwal.index')->with('status', 'Jadwal berhasil diperbarui.');
    }

     public function show(Jadwal $jadwal): View
     {
         $jadwal->load(['kegiatan.tahunKegiatan', 'tempat:id,name', 'mentors:id,name', 'penjabs:id,name']);

         return view('admin.jadwal-detail', [
             'jadwal' => $jadwal,
         ]);
     }

     public function destroy(Jadwal $jadwal): RedirectResponse
     {
         $jadwal->delete();

         return redirect()->route('admin.jadwal.index')->with('status', 'Jadwal berhasil dihapus.');
     }
 }
