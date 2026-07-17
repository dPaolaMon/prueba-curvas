<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membresias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('socia_id')
                  ->constrained('socias')
                  ->cascadeOnDelete();
            $table->foreignId('plan_version_id')
                  ->constrained('planes_versiones')
                  ->restrictOnDelete();
            $table->date('fecha_inicio');
            $table->date('fecha_fin_programada');
            $table->date('fecha_cancelacion')->nullable();
            $table->date('fecha_renovacion')->nullable();
            $table->string('estatus', 32)->default('activa');
            $table->string('metodo_pago', 32);
            $table->unsignedTinyInteger('dia_cobro')->nullable();
            $table->string('ciclo_facturacion', 20)->default('mensual');
            $table->unsignedTinyInteger('periodo_gracia_dias')->default(0);
            $table->string('motivo_baja', 120)->nullable();
            $table->text('observaciones')->nullable();
            $table->foreignId('vendedor_user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamps();

            // Índices
            $table->index('socia_id');
            $table->index('plan_version_id');
            $table->index(['fecha_inicio', 'fecha_fin_programada']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membresias');
    }
};
