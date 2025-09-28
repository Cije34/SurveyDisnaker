<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class JadwalController extends Controller
{
    public function index(): View
    {
        $schedules = Jadwal::with(['kegiatan:id,nama_kegiatan', 'tempat:id,name', 'mentors:id,name'])
            ->orderByDesc('tanggal_mulai')
            ->paginate(5);
        // dd($schedules);

        return view('admin.jadwal', [
            'user' => Auth::user(),
            'schedules' => $schedules,
        ]);

        // Debug: Cek data mentors
        // $jadwal = Jadwal::first();
        // if ($jadwal) {
        //     echo "Jadwal ID: " . $jadwal->id . "<br>";
        //     echo "Mentors count: " . $jadwal->mentors()->count() . "<br>";
        //     echo "Mentors data: " . $jadwal->mentors()->get()->toJson() . "<br>";

        //     // Cek data di table pivot
        //     $pivotData = \DB::table('jadwal_mentor')->where('jadwal_id', $jadwal->id)->get();
        //     echo "Pivot data: " . $pivotData->toJson() . "<br>";

        //     // Cek apakah ada data mentors
        //     $mentors = \DB::table('mentors')->get();
        //     echo "All mentors: " . $mentors->toJson() . "<br>";
        // }
        // die();
    }
}
