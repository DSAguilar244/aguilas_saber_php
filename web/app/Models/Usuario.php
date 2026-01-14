<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Usuario extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'telefono',
        'password',
        'activo',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Hide sensitive fields
     */
    protected $hidden = [
        'password',
    ];

    /**
     * JWTSubject required methods
     */

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * Here we optionally include the user's roles (as strings) in the token claims.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        // Ensure roles are returned as an array of strings (adapt to your Role attributes)
        $roles = $this->roles->map(function ($r) {
            return $r->nombre ?? $r->name ?? $r->id;
        })->toArray();

        return [
            'roles' => $roles,
        ];
    }

    /**
     * If you are using Spatie's HasRoles trait you can remove this method,
     * because the trait already defines a roles relationship.
     * If you prefer a custom pivot name (role_usuario), keep it as below.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_usuario', 'usuario_id', 'role_id');
    }
}