<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('app:seed-admin', function () {
    DB::transaction(function () {
        $email = 'admin@aguillassaber.local';
        $admin = \App\Models\Usuario::where('email', $email)->first();
        if (! $admin) {
            $admin = \App\Models\Usuario::create([
                'nombre' => 'Admin',
                'apellido' => 'Sistema',
                'email' => $email,
                'telefono' => '3001234567',
                'password' => Hash::make('password123'),
                'activo' => true,
            ]);
        }

        $role = \App\Models\Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'web'],
            ['descripcion' => 'Administrador del sistema']
        );

        $admin->roles()->syncWithoutDetaching([$role->id]);
    });

    $this->info('Admin user seeded: admin@aguillassaber.local / password123');
})->purpose('Ensure admin user and role exist and are linked');
