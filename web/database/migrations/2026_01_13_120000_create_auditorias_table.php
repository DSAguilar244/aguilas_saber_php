<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auditorias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->string('usuario_email')->nullable();
            $table->string('usuario_nombre')->nullable();
            $table->string('entidad');
            $table->string('accion');
            $table->unsignedBigInteger('registro_id')->nullable();
            $table->text('detalles')->nullable();
            $table->timestamps();

            $table->index(['entidad', 'accion']);
            $table->index('usuario_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auditorias');
    }
};
