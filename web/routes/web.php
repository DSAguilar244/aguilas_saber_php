<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\{
    UsuarioController,
    RecursoController,
    PrestamoController,
    ProductoController,
    RoleController,
    ReporteController,
    ProfileController,
    DashboardController,
};

Route::get('/recursos/buscar', [RecursoController::class, 'buscar']);
Route::post('/usuarios/validar-nombre', [UsuarioController::class, 'validarNombre'])->name('usuarios.validarNombre');
Route::post('/usuarios/validar-email', [UsuarioController::class, 'validarEmail'])->name('usuarios.validarEmail');
Route::get('/prestamos/buscar', [PrestamoController::class, 'buscar']);
Route::get('/usuarios/buscar', [UsuarioController::class, 'buscar']);
Route::post('/recursos/validar-disponibilidad', [RecursoController::class, 'verificarDisponibilidad']);
Route::get('/productos/buscar', [ProductoController::class, 'buscar']);
Route::get('/roles/buscar', [RoleController::class, 'buscar']);

// Página raíz: redirige al login personalizado
Route::get('/', fn() => redirect()->route('login'));

// Login personalizado
Route::get('/login', fn() => view('auth.login'))->name('login');

// Logout redirigiendo al login
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login')->with('status', 'Sesión cerrada correctamente');
})->name('logout');

// 📊 Dashboard con datos reales desde el controlador
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

// Vistas protegidas y gestiones del sistema
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/home', 'home')->name('home');


    Route::post('/recursos/validar-nombre', [RecursoController::class, 'validarNombre'])
        ->name('recursos.validarNombre');

    Route::post('/productos/validar-nombre', [ProductoController::class, 'validarNombre'])
        ->name('productos.validarNombre');


    // Perfil del usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CRUD para entidades
    Route::resources([
        'usuarios'  => UsuarioController::class,
        'recursos'  => RecursoController::class,
        'prestamos' => PrestamoController::class,
        'productos' => ProductoController::class,
        'roles'     => RoleController::class,
    ]);

    // 📄 Reportes PDF y descargas
    Route::controller(ReporteController::class)->group(function () {
        Route::get('/reporte-productos', 'productosPDF');
        Route::get('/reporte-usuarios', 'usuariosPDF');
        Route::get('/reporte-prestamos', 'prestamosPDF');
        Route::get('/descargar-reporte-productos', 'descargarProductosPDF');
        Route::get('/descargar-reporte-usuarios', 'descargarUsuariosPDF');
        Route::get('/descargar-reporte-prestamos', 'descargarPrestamosPDF');
        Route::get('/reportes/recursos/pdf', [ReporteController::class, 'recursosPDF'])->name('reportes.recursos.pdf');
        Route::get('/reportes/recursos/descargar', [ReporteController::class, 'descargarRecursosPDF'])->name('reportes.recursos.descargar');
    });
});

// Rutas de autenticación integradas
require __DIR__ . '/auth.php';
