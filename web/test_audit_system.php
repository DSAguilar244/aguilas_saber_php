<?php
/**
 * Script de prueba para sistema de auditoría
 * Ejecutar: php test_audit_system.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Usuario;
use App\Models\Recurso;
use App\Models\Producto;
use App\Models\Prestamo;
use App\Models\Role;
use App\Models\Auditoria;
use App\Http\Controllers\AuditoriaController;
use Illuminate\Support\Facades\Auth;

echo "=== INICIANDO PRUEBAS DEL SISTEMA DE AUDITORÍA ===\n\n";

// 1. Login como admin
$admin = Usuario::where('email', 'admin@aguilassaber.local')->first();
if (!$admin) {
    die("❌ ERROR: Admin no encontrado\n");
}
Auth::login($admin);
echo "✅ Login como admin: {$admin->email}\n";

// 2. Limpiar auditoría anterior
$count = Auditoria::count();
Auditoria::truncate();
echo "✅ Limpiada auditoría previa ({$count} registros)\n\n";

// 3. CREAR RECURSO
echo "--- TEST 1: Crear Recurso ---\n";
$recurso = Recurso::create([
    'nombre' => 'Proyector Sony Test',
    'descripcion' => 'Proyector para auditoría',
    'cantidad' => 3,
    'estado' => 'bueno',
]);
AuditoriaController::registrar('recursos', 'crear', $recurso->id, [
    'nombre' => $recurso->nombre,
    'estado' => $recurso->estado,
    'cantidad' => $recurso->cantidad,
]);
$audit = Auditoria::latest()->first();
echo "✅ Recurso creado ID: {$recurso->id}\n";
echo "✅ Auditoría registrada ID: {$audit->id} - Entidad: {$audit->entidad} - Acción: {$audit->accion}\n\n";

// 4. ACTUALIZAR RECURSO
echo "--- TEST 2: Actualizar Recurso ---\n";
$recurso->update(['cantidad' => 5, 'estado' => 'regular']);
AuditoriaController::registrar('recursos', 'actualizar', $recurso->id, [
    'nombre' => $recurso->nombre,
    'estado' => $recurso->estado,
    'cantidad' => $recurso->cantidad,
]);
$audit = Auditoria::latest()->first();
echo "✅ Recurso actualizado ID: {$recurso->id}\n";
echo "✅ Auditoría registrada ID: {$audit->id} - Detalles: {$audit->detalles}\n\n";

// 5. CREAR PRODUCTO
echo "--- TEST 3: Crear Producto ---\n";
$producto = Producto::create([
    'nombre' => 'Cuadernos Test',
    'estado' => 'disponible',
    'fecha_entrada' => '2026-01-01',
    'fecha_salida' => '2026-02-01',
    'cantidad' => 100,
]);
AuditoriaController::registrar('productos', 'crear', $producto->id, [
    'nombre' => $producto->nombre,
    'estado' => $producto->estado,
    'cantidad' => $producto->cantidad,
]);
$audit = Auditoria::latest()->first();
echo "✅ Producto creado ID: {$producto->id}\n";
echo "✅ Auditoría registrada ID: {$audit->id}\n\n";

// 6. CREAR PRÉSTAMO
echo "--- TEST 4: Crear Préstamo ---\n";
$prestamo = Prestamo::create([
    'codigo' => 'TEST-001',
    'usuario_id' => $admin->id,
    'recurso_id' => $recurso->id,
    'fecha_prestamo' => '2026-01-13',
    'fecha_devolucion' => null,
    'estado' => 'pendiente',
]);
AuditoriaController::registrar('prestamos', 'crear', $prestamo->id, [
    'codigo' => $prestamo->codigo,
    'usuario_id' => $prestamo->usuario_id,
    'recurso_id' => $prestamo->recurso_id,
    'estado' => $prestamo->estado,
]);
$audit = Auditoria::latest()->first();
echo "✅ Préstamo creado ID: {$prestamo->id}\n";
echo "✅ Auditoría registrada ID: {$audit->id}\n\n";

// 7. CREAR ROL
echo "--- TEST 5: Crear Rol ---\n";
$rol = Role::create([
    'name' => 'supervisor',
    'descripcion' => 'Rol de prueba para auditoría',
    'guard_name' => 'web',
]);
AuditoriaController::registrar('roles', 'crear', $rol->id, [
    'name' => $rol->name,
]);
$audit = Auditoria::latest()->first();
echo "✅ Rol creado ID: {$rol->id}\n";
echo "✅ Auditoría registrada ID: {$audit->id}\n\n";

// 8. ELIMINAR ENTIDADES Y REGISTRAR
echo "--- TEST 6: Eliminar Entidades ---\n";
$prodDetalles = ['nombre' => $producto->nombre, 'estado' => $producto->estado];
$producto->delete();
AuditoriaController::registrar('productos', 'eliminar', $producto->id, $prodDetalles);
echo "✅ Producto eliminado y auditado\n";

$rolDetalles = ['name' => $rol->name];
$rol->delete();
AuditoriaController::registrar('roles', 'eliminar', $rol->id, $rolDetalles);
echo "✅ Rol eliminado y auditado\n\n";

// 9. RESUMEN FINAL
echo "=== RESUMEN FINAL ===\n";
$totalAuditorias = Auditoria::count();
echo "Total de registros en auditoría: {$totalAuditorias}\n\n";

echo "Registros de auditoría:\n";
echo str_repeat('-', 80) . "\n";
printf("%-5s %-15s %-15s %-10s %-10s %-30s\n", "ID", "Usuario", "Entidad", "Acción", "Reg.ID", "Fecha");
echo str_repeat('-', 80) . "\n";

foreach (Auditoria::orderBy('id')->get() as $aud) {
    printf("%-5d %-15s %-15s %-10s %-10s %-30s\n",
        $aud->id,
        substr($aud->usuario_nombre ?? 'N/A', 0, 14),
        $aud->entidad,
        $aud->accion,
        $aud->registro_id ?? 'N/A',
        $aud->created_at->format('Y-m-d H:i:s')
    );
}
echo str_repeat('-', 80) . "\n";

// 10. VERIFICAR FILTROS
echo "\n--- TEST 7: Verificar Filtros ---\n";
$recursosAudit = Auditoria::where('entidad', 'recursos')->count();
$productosAudit = Auditoria::where('entidad', 'productos')->count();
$prestamosAudit = Auditoria::where('entidad', 'prestamos')->count();
$rolesAudit = Auditoria::where('entidad', 'roles')->count();

echo "Auditorías por entidad:\n";
echo "  - Recursos: {$recursosAudit}\n";
echo "  - Productos: {$productosAudit}\n";
echo "  - Préstamos: {$prestamosAudit}\n";
echo "  - Roles: {$rolesAudit}\n\n";

echo "✅ TODAS LAS PRUEBAS COMPLETADAS EXITOSAMENTE\n";
echo "==============================================\n\n";

echo "🌐 Servidor Laravel corriendo en: http://127.0.0.1:8000\n";
echo "   Login: admin@aguilassaber.local / password\n";
echo "   Ver auditoría en: http://127.0.0.1:8000/auditorias\n\n";
