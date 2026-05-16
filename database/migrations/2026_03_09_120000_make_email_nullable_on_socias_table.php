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
            $table->dropUnique('socias_email_unique');
            $table->string('email', 32)->nullable()->change();
            $table->unique('email');
        });
    }

    public function down(): void
    {
        DB::table('socias')
            ->whereNull('email')
            ->update([
                'email' => DB::raw("CONCAT('socia_', num_socia, '@rollback.local')"),
            ]);

        Schema::table('socias', function (Blueprint $table) {
            $table->dropUnique('socias_email_unique');
            $table->string('email', 32)->nullable(false)->change();
            $table->unique('email');
        });
    }
};
