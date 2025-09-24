<?php

use App\Http\Controllers\PenjabController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/','/login');

Route::get('/dashboard', function () {
    return view('peserta.dashboard');
})->middleware(['auth', 'verified','is.peserta'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'is.admin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {
        Route::get('/dashboard', [PenjabController::class, 'index'])->name('dashboard');
    });
require __DIR__.'/auth.php';
