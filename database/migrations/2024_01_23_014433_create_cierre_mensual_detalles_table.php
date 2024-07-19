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
        Schema::create('cierre_mensual_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cierre_mensual_id');
            $table->foreign('cierre_mensual_id')->on('cierre_mensuales')->references('id');
            $table->unsignedBigInteger('puc_id');
            $table->foreign('puc_id')->on('pucs')->references('id');
            $table->decimal('saldo_anterior', 12, 2)->default(0.00)->nullable();
            $table->decimal('saldo_actual', 12, 2)->default(0.00)->nullable();
            $table->decimal('debito', 12, 2)->default(0.00)->nullable();
            $table->decimal('credito', 12, 2)->default(0.00)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cierre_mensual_detalles');
    }
};
