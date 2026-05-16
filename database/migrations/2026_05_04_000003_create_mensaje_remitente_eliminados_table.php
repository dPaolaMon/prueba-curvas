<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Registra cuándo el remitente elimina un mensaje de su bandeja de enviados,
        // sin afectar a los destinatarios.
        Schema::create('mensaje_remitente_eliminados', function (Blueprint $table) {
            $table->id();

            $table->foreignId('mensaje_id')
                  ->constrained('mensajes')
                  ->cascadeOnDelete();

            $table->timestamp('eliminado_en')->useCurrent();

            $table->unique('mensaje_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mensaje_remitente_eliminados');
    }
};
