<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planes_versiones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')
                  ->constrained('planes')
                  ->cascadeOnDelete();
            $table->string('nombre_comercial', 150);
            $table->date('vigencia_desde');
            $table->date('vigencia_hasta')->nullable();
            $table->decimal('precio_inscripcion', 10, 2)->default(0);
            $table->decimal('precio_mensualidad', 10, 2);
            $table->decimal('precio_mensualidad_recurrente', 10, 2)->nullable();
            $table->decimal('precio_pago_unico', 10, 2)->nullable();
            $table->unsignedTinyInteger('meses_duracion');
            $table->unsignedTinyInteger('meses_cobrables');
            $table->unsignedTinyInteger('meses_gratis')->default(0);
            $table->decimal('comision_monto', 10, 2)->default(0);
            $table->decimal('retencion_monto', 10, 2)->default(0);
            $table->unsignedTinyInteger('retencion_mes_numero')->nullable();
            $table->text('notas')->nullable();
            $table->foreignId('creado_por')
                  ->constrained('users')
                  ->restrictOnDelete();
            $table->foreignId('actualizado_por')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->string('estado_publicacion', 32)->default('borrador');
            $table->timestamps();

            // Índices
            $table->index(['plan_id', 'vigencia_desde', 'vigencia_hasta']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planes_versiones');
    }
};
