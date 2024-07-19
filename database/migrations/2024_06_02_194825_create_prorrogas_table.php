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
        Schema::create('prorrogas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asociado_id');
            $table->string('plazo_inversion')->nullable();
            $table->float('valor_inicial_cdat')->default(0.00);
            $table->float('valor_prorroga')->default(0.00);
            $table->string('tasa_interes_remuneracion')->nullable();
            $table->string('porcentaje_retencion')->nullable();

            $table->foreign('asociado_id')->references('id')->on('asociados');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prorrogas');
    }
};
