<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Usuario::where('email', 'admin@aguillassaber.local')->first();
        if (! $admin) {
            try {
                $admin = Usuario::create([
                    'nombre' => 'Admin',
                    'apellido' => 'Sistema',
                    'email' => 'admin@aguillassaber.local',
                    'telefono' => '3001234567',
                    'password' => Hash::make('password123'),
                    'activo' => true,
                ]);
            } catch (\Throwable $e) {
                $admin = Usuario::where('email', 'admin@aguillassaber.local')->first();
            }
        }

        // Asignar rol administrador si existe
        try {
            $roleAdmin = \App\Models\Role::where('name', 'admin')->first();
            if ($roleAdmin) {
                $admin->roles()->syncWithoutDetaching([$roleAdmin->id]);
            }
        } catch (\Throwable $e) {
            // Ignorar si la tabla de roles aún no existe en algunos entornos
        }

        $test = Usuario::where('email', 'test@aguillassaber.local')->first();
        if (! $test) {
            Usuario::create([
                'nombre' => 'Test',
                'apellido' => 'Usuario',
                'email' => 'test@aguillassaber.local',
                'telefono' => '3007654321',
                'password' => Hash::make('test123'),
                'activo' => true,
            ]);
        }
    }
}
