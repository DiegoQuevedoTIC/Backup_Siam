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
        Schema::create('credito_lineas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('clasificacion_id');
            $table->unsignedBigInteger('tipo_garantia_id');
            $table->unsignedBigInteger('tipo_inversion_id');
            $table->unsignedInteger('moneda_id');
            $table->unsignedBigInteger('subcentro_id');
            $table->string('linea');
            $table->string('descripcion', 120);
            $table->string('periodo_pago');
            $table->float('interes_cte')->default(0);
            $table->float('interes_mora')->default(0);
            $table->string('tipo_cuota', 10);
            $table->string('tipo_tasa', 10);
            $table->unsignedBigInteger('nro_cuotas_max');
            $table->unsignedBigInteger('nro_cuotas_gracia')->default(0);
            $table->unsignedBigInteger('cant_gar_real');
            $table->unsignedBigInteger('cant_gar_pers');
            $table->float('monto_min')->nullable();
            $table->float('monto_max')->nullable();
            $table->string('abonos_extra', 3);
            $table->string('ciius');

            $table->foreign('clasificacion_id')->references('id')->on('clasificacion_creditos');
            $table->foreign('tipo_garantia_id')->references('id')->on('tipo_garantias');
            $table->foreign('tipo_inversion_id')->references('id')->on('tipo_inversiones');
            $table->foreign('moneda_id')->references('id')->on('monedas');
            $table->foreign('subcentro_id')->references('id')->on('subcentros');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credito_lineas');
    }
};
