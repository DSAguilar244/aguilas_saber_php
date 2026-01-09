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
        Usuario::create([
            'nombre' => 'Admin',
            'apellido' => 'Sistema',
            'email' => 'admin@aguillassaber.local',
            'telefono' => '3001234567',
            'password' => Hash::make('password123'),
            'activo' => true,
        ]);

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
