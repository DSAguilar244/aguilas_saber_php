<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'usuario_email',
        'usuario_nombre',
        'entidad',
        'accion',
        'registro_id',
        'detalles',
    ];
}
