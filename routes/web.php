<?php

use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\MentorController as AdminMentorController;
use App\Http\Controllers\Admin\PenjabController as AdminPenjabController;
use App\Http\Controllers\Admin\PesertaController as AdminPesertaController;
use App\Http\Controllers\Admin\TahunKegiatanController;
use App\Http\Controllers\Admin\TempatController as AdminTempatController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\PenjabController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [PenjabController::class, 'index'])->name('dashboard');

    // Tahun Kegiatan
    Route::get('/tahun-kegiatan', [TahunKegiatanController::class, 'index'])->name('tahun.index');
    Route::post('/tahun-kegiatan', [TahunKegiatanController::class, 'store'])->name('tahun.store');
    Route::delete('/tahun-kegiatan/{tahunKegiatan}', [TahunKegiatanController::class, 'destroy'])->name('tahun.destroy');

    // Kegiatan
    Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan.index');
    Route::post('/kegiatan', [KegiatanController::class, 'store'])->name('kegiatan.store');
    Route::put('/kegiatan/{kegiatan}', [KegiatanController::class, 'update'])->name('kegiatan.update');
    Route::delete('/kegiatan/{kegiatan}', [KegiatanController::class, 'destroy'])->name('kegiatan.destroy');

    // Jadwal
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::post('/jadwal', [JadwalController::class, 'store'])->name('jadwal.store');
    Route::put('/jadwal/{jadwal}', [JadwalController::class, 'update'])->name('jadwal.update');
    Route::delete('/jadwal/{jadwal}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');

    // Peserta
    Route::get('/peserta', [AdminPesertaController::class, 'index'])->name('peserta.index');
    Route::post('/peserta', [AdminPesertaController::class, 'store'])->name('peserta.store');
    Route::put('/peserta/{peserta}', [AdminPesertaController::class, 'update'])->name('peserta.update');
    Route::delete('/peserta/{peserta}', [AdminPesertaController::class, 'destroy'])->name('peserta.destroy');
    Route::get('/peserta/template', [AdminPesertaController::class, 'downloadTemplate'])->name('peserta.template');
    Route::post('/peserta/import', [AdminPesertaController::class, 'import'])->name('peserta.import');

    // Mentor
    Route::get('/mentor', [AdminMentorController::class, 'index'])->name('mentor.index');
    Route::post('/mentor', [AdminMentorController::class, 'store'])->name('mentor.store');
    // Route::put('/mentor/{mentor}', [AdminMentorController::class, 'update'])->name('mentor.update');
    // Route::delete('/mentor/{mentor}', [AdminMentorController::class, 'destroy'])->name('mentor.destroy');

    // Penjab
    Route::get('/penjab', [AdminPenjabController::class, 'index'])->name('penjab.index');
    // Route::post('/penjab', [AdminPenjabController::class, 'store'])->name('penjab.store');
    // Route::put('/penjab/{penjab}', [AdminPenjabController::class, 'update'])->name('penjab.update');
    // Route::delete('/penjab/{penjab}', [AdminPenjabController::class, 'destroy'])->name('penjab.destroy');

    // Tempat
    Route::get('/tempat', [AdminTempatController::class, 'index'])->name('tempat.index');
    Route::post('/tempat', [AdminTempatController::class, 'store'])->name('tempat.store');
    // Route::put('/tempat/{tempat}', [AdminTempatController::class, 'update'])->name('tempat.update');
    // Route::delete('/tempat/{tempat}', [AdminTempatController::class, 'destroy'])->name('tempat.destroy');





    // User
});

Route::middleware(['auth', 'verified', 'role:peserta'])->prefix('peserta')->name('peserta.')->group(function () {
    Route::get('/dashboard', [PesertaController::class, 'index'])->name('dashboard');
    Route::get('/jadwal', [PesertaController::class, 'jadwal'])->name('jadwal');
    Route::get('/survey', [PesertaController::class, 'survey'])->name('survey');
    Route::get('/survey/{kegiatan}', [PesertaController::class, 'showSurvey'])->name('survey.show');
    Route::post('/survey/{kegiatan}', [PesertaController::class, 'submitSurvey'])->name('survey.submit');
});

Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    $user = auth()->user();

    if ($user?->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }

    if ($user?->hasRole('peserta')) {
        return redirect()->route('peserta.dashboard');
    }

    abort(403);
})->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
require __DIR__.'/auth.php';
