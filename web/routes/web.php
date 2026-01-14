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
    AuditoriaController,
};

// 🔐 Autenticación personalizada
Route::get('/', fn() => redirect()->route('login')); // Página raíz redirige al login
Route::get('/login', fn() => view('auth.login'))->name('login');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login')->with('status', 'Sesión cerrada correctamente');
})->name('logout');

require __DIR__ . '/auth.php'; // Rutas internas de autenticación

// 📊 Dashboard protegido
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// 🔎 Rutas públicas para búsqueda y validación AJAX
Route::get('/usuarios/buscar', [UsuarioController::class, 'buscar']);
Route::post('/usuarios/validar-nombre', [UsuarioController::class, 'validarNombre'])->name('usuarios.validarNombre');
Route::post('/usuarios/validar-email', [UsuarioController::class, 'validarEmail'])->name('usuarios.validarEmail');

Route::get('/recursos/buscar', [RecursoController::class, 'buscar']);
Route::post('/recursos/validar-disponibilidad', [RecursoController::class, 'verificarDisponibilidad']);
Route::post('/recursos/validar-nombre', [RecursoController::class, 'validarNombre'])->name('recursos.validarNombre');

Route::get('/prestamos/buscar', [PrestamoController::class, 'buscar']);

Route::get('/productos/buscar', [ProductoController::class, 'buscar']);
Route::post('/productos/validar-nombre', [ProductoController::class, 'validarNombre'])->name('productos.validarNombre');

// Solo admin puede buscar roles
Route::get('/roles/buscar', [RoleController::class, 'buscar'])
    ->middleware(['auth', 'verified', 'check.role:admin']);

// 🔐 Áreas protegidas por autenticación y verificación
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/home', 'home')->name('home');

    // 👤 Perfil del usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 🔄 Gestión principal de entidades
    // Usuarios: solo editar y eliminar (crear restringido a admin)
    Route::get('usuarios/create', [UsuarioController::class, 'create'])->name('usuarios.create')->middleware('check.role:admin');
    Route::post('usuarios', [UsuarioController::class, 'store'])->middleware('check.role:admin')->name('usuarios.store');
    Route::get('usuarios/{usuario}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit')->middleware('check.permission:usuarios.editar');
    Route::put('usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update')->middleware('check.permission:usuarios.editar');
    Route::delete('usuarios/{usuario}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy')->middleware('check.permission:usuarios.eliminar');
    Route::resource('usuarios', UsuarioController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);

    // Recursos: crear, editar y eliminar con permisos
    Route::get('recursos/create', [RecursoController::class, 'create'])->name('recursos.create')->middleware('check.permission:recursos.crear');
    Route::post('recursos', [RecursoController::class, 'store'])->middleware('check.permission:recursos.crear')->name('recursos.store');
    Route::get('recursos/{recurso}/edit', [RecursoController::class, 'edit'])->name('recursos.edit')->middleware('check.permission:recursos.editar');
    Route::put('recursos/{recurso}', [RecursoController::class, 'update'])->name('recursos.update')->middleware('check.permission:recursos.editar');
    Route::delete('recursos/{recurso}', [RecursoController::class, 'destroy'])->name('recursos.destroy')->middleware('check.permission:recursos.eliminar');
    Route::resource('recursos', RecursoController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);

    // Productos: crear, editar y eliminar con permisos
    Route::get('productos/create', [ProductoController::class, 'create'])->name('productos.create')->middleware('check.permission:productos.crear');
    Route::post('productos', [ProductoController::class, 'store'])->middleware('check.permission:productos.crear')->name('productos.store');
    Route::get('productos/{producto}/edit', [ProductoController::class, 'edit'])->name('productos.edit')->middleware('check.permission:productos.editar');
    Route::put('productos/{producto}', [ProductoController::class, 'update'])->name('productos.update')->middleware('check.permission:productos.editar');
    Route::delete('productos/{producto}', [ProductoController::class, 'destroy'])->name('productos.destroy')->middleware('check.permission:productos.eliminar');
    Route::resource('productos', ProductoController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);

    // Préstamos: crear, editar y eliminar con permisos
    Route::get('prestamos/create', [PrestamoController::class, 'create'])->name('prestamos.create')->middleware('check.permission:prestamos.crear');
    Route::post('prestamos', [PrestamoController::class, 'store'])->middleware('check.permission:prestamos.crear')->name('prestamos.store');
    Route::get('prestamos/{prestamo}/edit', [PrestamoController::class, 'edit'])->name('prestamos.edit')->middleware('check.permission:prestamos.editar');
    Route::put('prestamos/{prestamo}', [PrestamoController::class, 'update'])->name('prestamos.update')->middleware('check.permission:prestamos.editar');
    Route::delete('prestamos/{prestamo}', [PrestamoController::class, 'destroy'])->name('prestamos.destroy')->middleware('check.permission:prestamos.eliminar');
    Route::resource('prestamos', PrestamoController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);

    // Roles: solo accesibles por admin
    Route::resource('roles', RoleController::class)
        ->middleware('check.role:admin');

    // Auditoría (solo admin)
    Route::get('auditorias', [AuditoriaController::class, 'index'])->name('auditorias.index')->middleware('check.role:admin');
    Route::delete('auditorias/limpiar', [AuditoriaController::class, 'limpiar'])->name('auditorias.limpiar')->middleware('check.role:admin');

    // 📄 Reportes y descargas PDF
    Route::controller(ReporteController::class)->group(function () {
        Route::get('/reporte-productos', 'productosPDF');
        Route::get('/descargar-reporte-productos', 'descargarProductosPDF');

        Route::get('/reporte-usuarios', 'usuariosPDF');
        Route::get('/descargar-reporte-usuarios', 'descargarUsuariosPDF');

        Route::get('/reporte-prestamos', 'prestamosPDF');
        Route::get('/descargar-reporte-prestamos', 'descargarPrestamosPDF');

        Route::get('/reportes/recursos/pdf', 'recursosPDF')->name('reportes.recursos.pdf');
        Route::get('/reportes/recursos/descargar', 'descargarRecursosPDF')->name('reportes.recursos.descargar');
    });
});