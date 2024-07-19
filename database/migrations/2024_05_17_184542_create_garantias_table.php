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
        Schema::create('garantias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asociado_id');
            $table->unsignedBigInteger('tipo_garantia_id');

            $table->string('altura_mora')->nullable();
            $table->string('nro_escr_o_matri');
            $table->string('direccion');
            $table->string('ciudad_registro');
            $table->float('valor_avaluo')->default(0.00);
            $table->date('fecha_avaluo')->nullable();
            $table->boolean('bien_con_prenda')->default(false);
            $table->boolean('bien_sin_prenda')->default(false);
            $table->float('valor_avaluo_comercial')->default(0.00);
            $table->string('observaciones')->nullable();
            $table->float('saldo_capital')->default(0.00);
            $table->float('valor_a_pagar')->default(0.00);

            $table->foreign('asociado_id')->references('id')->on('asociados');
            $table->foreign('tipo_garantia_id')->references('id')->on('tipo_garantias');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('garantias');
    }
};
