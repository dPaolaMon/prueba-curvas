<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 120);
            $table->text('descripcion')->nullable();
            $table->string('estatus', 32)->default('activo');
            $table->foreignId('creado_por')
                  ->constrained('users')
                  ->restrictOnDelete();
            $table->foreignId('actualizado_por')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamps();

            // Índices
            $table->index('estatus');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planes');
    }
};
