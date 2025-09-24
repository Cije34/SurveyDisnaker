<?php

use App\Http\Controllers\PenjabController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});

Route::middleware(['auth', 'role:peserta'])->prefix('peserta')->name('peserta.')->group(function () {
    Route::get('/dashboard', function () {
        return view('   .dashboard');
    })->name('dashboard');
});

<<<<<<< HEAD
Route::middleware(['auth','is.peserta','verified'])->group(function () {
    Route::get('/dashboard', [PesertaController::class,'index'])->name('dashboard');
});
=======
Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user && $user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }

    if ($user && $user->hasRole('peserta')) {
        return redirect()->route('peserta.dashboard');
    }

    abort(403);
})->middleware(['auth', 'verified'])->name('dashboard');
>>>>>>> 843bcfc (install spatie)

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
