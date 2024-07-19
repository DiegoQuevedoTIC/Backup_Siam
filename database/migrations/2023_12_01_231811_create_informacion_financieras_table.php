<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('informacion_financieras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tercero_id')->constrained('terceros');
            $table->float('total_activos');
            $table->float('total_pasivos');
            $table->float('total_patrimonio');
            $table->float('salario');
            $table->float('honorarios');
            $table->float('otros_ingresos');
            $table->float('total_ingresos');
            $table->float('gastos_sostenimiento');
            $table->float('gastos_financieros');
            $table->float('creditos_hipotecarios');
            $table->float('otros_gastos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informacion_financieras');
    }
};
