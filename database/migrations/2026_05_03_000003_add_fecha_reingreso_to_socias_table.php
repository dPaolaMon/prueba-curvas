<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('socias', function (Blueprint $table) {
            $table->date('fecha_reingreso')
                ->default(DB::raw('CURRENT_DATE'))
                ->after('fecha_alta');
        });

        DB::table('socias')->update([
            'fecha_reingreso' => DB::raw('fecha_alta'),
        ]);
    }

    public function down(): void
    {
        Schema::table('socias', function (Blueprint $table) {
            $table->dropColumn('fecha_reingreso');
        });
    }
};
