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
        Schema::create('certificado_depositos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asociado_id');
            $table->string('tasa')->nullable();
            $table->string('plazo_inversion')->nullable();
            $table->float('valor_inicial_cdat')->default(0.00);
            $table->float('valor_proyectado')->default(0.00);
            $table->string('tasa_interes_remuneracion')->nullable();
            $table->string('porcentaje_retencion')->nullable();
            $table->string('nro_prorroga')->nullable();
            $table->string('codigo_asesor')->nullable();
            $table->string('nombre_asesor')->nullable();
            $table->string('observaciones')->nullable();
            $table->float('valor_apertura')->default(0.00);
            $table->date('fecha_apertura')->nullable();
            $table->float('valor_a_pagar')->default(0.00);
            $table->date('fecha_cancelacion')->nullable();

            $table->foreign('asociado_id')->references('id')->on('asociados');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificado_depositos');
    }
};
