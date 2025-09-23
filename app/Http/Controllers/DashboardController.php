<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = ['name' => 'Thomas', 'email' => 'thomas@gmail.com'];

        $schedule = [
            ['kegiatan' => 'Web Development',   'tanggal' => '07-06-2025', 'waktu' => '08:00 - 11:00', 'lokasi' => 'Gedung Serba guna'],
            ['kegiatan' => 'Mahir HTML',        'tanggal' => '06-06-2025', 'waktu' => '09:00 - 12:00', 'lokasi' => 'Gedung Serba guna'],
            ['kegiatan' => 'Digital marketing', 'tanggal' => '05-06-2025', 'waktu' => '10:00 - 13:00', 'lokasi' => 'Gedung Serba guna'],
            ['kegiatan' => 'Sistem Operasi',    'tanggal' => '04-06-2025', 'waktu' => '08:00 - 11:00', 'lokasi' => 'Gedung Serba guna'],
        ];

        return view('dashboard', compact('user', 'schedule'));
    }

    public function jadwal()
    {
        $schedule = [
            ['kegiatan' => 'Web Development',   'tanggal' => '07-06-2025', 'waktu' => '08:00 - 11:00', 'lokasi' => 'Gedung Serba guna'],
            ['kegiatan' => 'Mahir HTML',        'tanggal' => '06-06-2025', 'waktu' => '09:00 - 12:00', 'lokasi' => 'Gedung Serba guna'],
            ['kegiatan' => 'Digital marketing', 'tanggal' => '05-06-2025', 'waktu' => '10:00 - 13:00', 'lokasi' => 'Gedung Serba guna'],
            ['kegiatan' => 'Sistem Operasi',    'tanggal' => '04-06-2025', 'waktu' => '08:00 - 11:00', 'lokasi' => 'Gedung Serba guna'],
            ['kegiatan' => 'Sistem Operasi',    'tanggal' => '04-06-2025', 'waktu' => '08:00 - 11:00', 'lokasi' => 'Gedung Serba guna'],
        ];
        $highlightIndex = 1;
        return view('jadwal', compact('schedule', 'highlightIndex'));
    }

    public function survey()
    {
        $available = []; // empty state per screenshot
        return view('survey', compact('available'));
    }
}
