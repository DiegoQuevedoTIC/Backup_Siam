<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToComprobanteLineas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('comprobante_lineas', function (Blueprint $table) {
            $table->integer('linea')->unsigned()->nullable()->default(10000); // Línea numérica hasta 10000
            $table->decimal('BASE_GRAVABLE', 15, 2)->nullable(); // Valor monetario
            $table->integer('CHEQUE')->unsigned()->nullable()->default(100000); // Cheque numérico hasta 100000
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('comprobante_lineas', function (Blueprint $table) {
            $table->dropColumn('linea');
            $table->dropColumn('BASE_GRAVABLE');
            $table->dropColumn('CHEQUE');
        });
    }
}
