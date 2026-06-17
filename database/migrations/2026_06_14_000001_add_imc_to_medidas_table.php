<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medidas', function (Blueprint $table) {
            $table->float('imc')->after('altura');
        });
    }

    public function down(): void
    {
        Schema::table('medidas', function (Blueprint $table) {
            $table->dropColumn('imc');
        });
    }
};