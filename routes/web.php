<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

use App\Models\Kantor;
use App\Models\Periode;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    $kantors = Kantor::all();
    $periodes = Periode::all();
    return view('auth.login', compact('kantors', 'periodes'));
})->name('login');

Route::post('/login', [AuthController::class, 'authenticate']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('kantor', \App\Http\Controllers\KantorController::class);
    Route::resource('periode', \App\Http\Controllers\PeriodeController::class);

    Route::get('/master', [\App\Http\Controllers\MasterController::class, 'index'])->name('master.index');
    Route::put('/master', [\App\Http\Controllers\MasterController::class, 'update'])->name('master.update');

    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
