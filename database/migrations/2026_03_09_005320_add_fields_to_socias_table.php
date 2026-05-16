<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('socias', function (Blueprint $table) {
            $table->unsignedBigInteger('num_socia')->unique();
            $table->string('estatus', 32)->default('Activa');
            $table->date('fecha_alta')->default(DB::raw('CURRENT_DATE'));
            $table->date('fecha_baja')->nullable();
            $table->text('comentarios')->nullable();
            $table->text('factorx')->nullable();
        });

        $driver = DB::getDriverName();
        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::unprepared(<<<'SQL'
CREATE TRIGGER socias_num_socia_before_insert
BEFORE INSERT ON socias
FOR EACH ROW
BEGIN
    IF NEW.num_socia IS NULL OR NEW.num_socia = 0 THEN
        SET NEW.num_socia = (SELECT COALESCE(MAX(num_socia), 999) + 1 FROM socias);
    END IF;
END;
SQL);
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();
        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::unprepared('DROP TRIGGER IF EXISTS socias_num_socia_before_insert');
        }

        Schema::table('socias', function (Blueprint $table) {
            $table->dropColumn([
                'num_socia',
                'estatus',
                'fecha_alta',
                'fecha_baja',
                'comentarios',
                'factorx',
            ]);
        });
    }
};
