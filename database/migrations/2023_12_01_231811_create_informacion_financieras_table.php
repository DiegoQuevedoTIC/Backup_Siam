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
            $table->float('total_activos')->nullable();
            $table->float('total_pasivos')->nullable();
            $table->float('total_patrimonio')->nullable();
            $table->float('salario')->nullable();
            $table->float('honorarios')->nullable();
            $table->float('otros_ingresos')->nullable();
            $table->float('total_ingresos')->nullable();
            $table->float('gastos_sostenimiento')->nullable();
            $table->float('gastos_financieros')->nullable();
            $table->float('creditos_hipotecarios')->nullable();
            $table->float('otros_gastos')->nullable();
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
