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
            $table->unsignedBigInteger('linea');
            $table->string('descripcion', 120);
            $table->string('clasificacion', 3);
            $table->unsignedBigInteger('tipo_garantia');
            $table->unsignedBigInteger('tipo_inversion');
            $table->unsignedInteger('moneda')->nullable();
            $table->unsignedInteger('periodo_pago')->nullable();
            $table->float('interes_cte')->default(0);
            $table->float('interes_mora')->default(0);
            $table->string('tipo_cuota', 1);
            $table->string('tipo_tasa', 1);
            $table->unsignedBigInteger('nro_cuotas_max');
            $table->unsignedBigInteger('nro_cuotas_gracia')->default(0);
            $table->unsignedBigInteger('cant_gar_real');
            $table->unsignedBigInteger('cant_gar_pers');
            $table->float('monto_min')->nullable();
            $table->float('monto_max')->nullable();
            $table->string('abonos_extra', 1);
            $table->unsignedBigInteger('ciius');
            $table->string('subcentro', 16);
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
