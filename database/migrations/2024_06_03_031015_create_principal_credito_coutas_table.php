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
        Schema::create('principal_credito_coutas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('credito_solicitud_id');
            $table->string('periodo');
            $table->float('vlr_cuota')->default(0.00);
            $table->float('vlr_interes')->default(0.00);
            $table->float('amortizacion_capital')->default(0.00);
            $table->float('saldo')->default(0.00);

            $table->foreign('credito_solicitud_id')->references('id')->on('credito_solicitudes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('principal_credito_coutas');
    }
};
