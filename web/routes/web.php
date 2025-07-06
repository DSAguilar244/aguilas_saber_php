<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/home', function () {
    return view('home');
})->middleware(['auth'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::resource('usuarios', \App\Http\Controllers\UsuarioController::class);
    Route::resource('recursos', \App\Http\Controllers\RecursoController::class);
    Route::resource('prestamos', \App\Http\Controllers\PrestamoController::class);
    Route::resource('productos', \App\Http\Controllers\ProductoController::class);
    Route::resource('roles', \App\Http\Controllers\RoleController::class);
});

require __DIR__.'/auth.php';
