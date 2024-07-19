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
        Schema::create('obligaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asociado_id');
            $table->string('concepto');
            $table->string('aportes');
            $table->float('valor_descuento')->default(0.00);
            $table->string('plazo')->nullable();
            $table->string('periodo_descuento')->nullable();
            $table->date('fecha_limite_pago')->nullable();
            $table->date('fecha_inicio_descuento')->nullable();
            $table->date('fecha_ultima_couta')->nullable();
            $table->integer('nro_cuota');
            $table->boolean('vigente')->default(false);
            $table->boolean('vencida')->default(false);
            $table->string('limite_cuotas')->nullable();

            $table->foreign('asociado_id')->references('id')->on('asociados');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obligaciones');
    }
};
