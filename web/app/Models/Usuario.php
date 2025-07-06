<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'telefono',
        'password',
        'activo',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_usuario', 'usuario_id', 'role_id');
    }
}
