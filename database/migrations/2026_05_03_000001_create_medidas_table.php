<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medidas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('socia_id')
                ->constrained('socias')
                ->cascadeOnDelete();

            $table->float('busto');
            $table->float('cintura');
            $table->float('abdomen');
            $table->float('caderas');
            $table->float('muslo');
            $table->float('brazo');
            $table->float('peso');
            $table->float('altura');
            $table->float('porcentaje_grasa');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medidas');
    }
};
