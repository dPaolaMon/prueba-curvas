<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('socias', function (Blueprint $table) {
            $table->id();

            // Relación con users (login)
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Datos personales
            $table->string('nombre', 64);
            $table->string('apellidos', 128);
            $table->string('foto')->nullable();
            $table->date('fecha_nacimiento');
            $table->string('ocupacion', 32)->nullable();
            $table->string('estado_civil', 32)->nullable();
            $table->string('celular', 20);
            $table->string('email', 32)->unique();
            $table->string('direccion', 256)->nullable();
            $table->string('colonia', 32)->nullable();
            $table->string('codigo_postal', 10)->nullable();

            // Catálogos
            $table->foreignId('municipio_id')
                  ->constrained()
                  ->restrictOnDelete();
            $table->foreignId('estado_id')
                  ->constrained()
                  ->restrictOnDelete();
            
            $table->string('metodo_pago', 32);

            // Información adicional
            $table->text('contacto_emergencia')->nullable();
            $table->text('padecimiento_cronico')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('socias');
    }
};
