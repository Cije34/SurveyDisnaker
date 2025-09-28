<?php

use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\TahunKegiatanController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\PenjabController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [PenjabController::class, 'index'])->name('dashboard');
    Route::post('/jadwal', [PenjabController::class, 'storeSchedule'])->name('jadwal.store');
    Route::get('/tahun-kegiatan', [TahunKegiatanController::class, 'index'])->name('tahun.index');
    Route::post('/tahun-kegiatan', [TahunKegiatanController::class, 'store'])->name('tahun.store');
    Route::delete('/tahun-kegiatan/{tahunKegiatan}', [TahunKegiatanController::class, 'destroy'])->name('tahun.destroy');
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan.index');
    Route::post('/kegiatan', [KegiatanController::class, 'store'])->name('kegiatan.store');
    Route::put('/kegiatan/{kegiatan}', [KegiatanController::class, 'update'])->name('kegiatan.update');
    Route::delete('/kegiatan/{kegiatan}', [KegiatanController::class, 'destroy'])->name('kegiatan.destroy');
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
