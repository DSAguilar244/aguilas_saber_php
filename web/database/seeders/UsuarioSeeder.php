<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Usuario::firstOrCreate(
            ['email' => 'admin@aguilassaber.local'],
            [
                'nombre' => 'Admin',
                'apellido' => 'Sistema',
                'telefono' => '3001234567',
                'password' => Hash::make('password'),
                'activo' => true,
            ]
        );

        $test = Usuario::firstOrCreate(
            ['email' => 'test@aguilassaber.local'],
            [
                'nombre' => 'Test',
                'apellido' => 'Usuario',
                'telefono' => '3007654321',
                'password' => Hash::make('password'),
                'activo' => true,
            ]
        );

        $roleAdmin = Role::where('name', 'admin')->first();
        $roleUser  = Role::where('name', 'usuario')->first();

        if ($roleAdmin) {
            $admin->syncRoles([$roleAdmin->name]);
        }

        if ($roleUser) {
            $test->syncRoles([$roleUser->name]);
        }
    }
}
