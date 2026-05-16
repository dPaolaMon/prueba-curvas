<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mensaje_destinatarios', function (Blueprint $table) {
            $table->id();

            $table->foreignId('mensaje_id')
                  ->constrained('mensajes')
                  ->cascadeOnDelete();

            $table->foreignId('destinatario_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // NULL = no leído; timestamp = momento en que se leyó
            $table->timestamp('leido_en')->nullable();

            // NULL = visible; timestamp = el destinatario lo eliminó de su bandeja
            $table->timestamp('eliminado_en')->nullable();

            $table->timestamps();

            // Un destinatario no puede aparecer dos veces en el mismo mensaje
            $table->unique(['mensaje_id', 'destinatario_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mensaje_destinatarios');
    }
};
