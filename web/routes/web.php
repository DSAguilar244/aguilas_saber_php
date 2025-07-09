<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReporteController;

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


Route::get('/reporte-productos', [ReporteController::class, 'productosPDF']);
Route::get('/descargar-reporte-productos', [ReporteController::class, 'descargarProductosPDF']);

Route::get('/reporte-usuarios', [ReporteController::class, 'usuariosPDF']);
Route::get('/descargar-reporte-usuarios', [ReporteController::class, 'descargarUsuariosPDF']);

Route::get('/reporte-prestamos', [ReporteController::class, 'prestamosPDF']);
Route::get('/descargar-reporte-prestamos', [ReporteController::class, 'descargarPrestamosPDF']);


require __DIR__.'/auth.php';
