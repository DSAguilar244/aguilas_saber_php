<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permisos = [
            'usuarios.crear',
            'usuarios.editar',
            'usuarios.eliminar',

            'productos.crear',
            'productos.editar',
            'productos.eliminar',

            'recursos.crear',
            'recursos.editar',
            'recursos.eliminar',

            'prestamos.crear',
            'prestamos.editar',
            'prestamos.eliminar',
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso, 'guard_name' => 'web']);
        }

        // Asignar todos los permisos al rol admin si existe
        $admin = Role::where('name', 'admin')->first();
        if ($admin) {
            $admin->syncPermissions(Permission::all());
        }
    }
}
