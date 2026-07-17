<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membresia_id')
                  ->constrained('membresias')
                  ->cascadeOnDelete();
            $table->foreignId('socia_id')
                  ->constrained('socias')
                  ->cascadeOnDelete();
            $table->foreignId('plan_version_id')
                  ->constrained('planes_versiones')
                  ->restrictOnDelete();
            $table->string('folio_pago', 50)->unique();
            $table->string('tipo_pago', 20);
            $table->unsignedSmallInteger('periodo_anio')->nullable();
            $table->unsignedTinyInteger('periodo_mes')->nullable();
            $table->date('fecha_programada')->nullable();
            $table->dateTime('fecha_pago');
            $table->char('moneda', 3)->default('MXN');
            $table->decimal('monto_lista', 10, 2);
            $table->decimal('monto_descuento', 10, 2)->default(0);
            $table->decimal('monto_recargo', 10, 2)->default(0);
            $table->decimal('monto_final', 10, 2);
            $table->string('metodo_pago', 30);
            $table->string('referencia_externa', 100)->nullable();
            $table->string('estatus', 20)->default('aplicado');
            $table->json('snapshot_json')->nullable();
            $table->decimal('comision_monto', 10, 2)->default(0);
            $table->date('comision_pagable_en')->nullable();
            $table->decimal('retencion_monto', 10, 2)->default(0);
            $table->boolean('retencion_aplica')->default(false);
            $table->date('retencion_liberable_en')->nullable();
            $table->foreignId('registrado_por')
                  ->constrained('users')
                  ->restrictOnDelete();
            $table->foreignId('anulado_por')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->dateTime('anulado_at')->nullable();
            $table->string('motivo_anulacion', 200)->nullable();
            $table->timestamps();

            // Índices
            $table->index(['membresia_id', 'fecha_pago']);
            $table->index(['socia_id', 'fecha_pago']);
            $table->index(['estatus', 'fecha_pago']);
            $table->index(['tipo_pago', 'periodo_anio', 'periodo_mes']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
