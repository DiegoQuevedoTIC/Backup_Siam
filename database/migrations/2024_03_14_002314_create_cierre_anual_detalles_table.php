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
        Schema::create('cierre_anual_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pucs_id');
            $table->foreign('pucs_id')->on('pucs')->references('id');
            $table->unsignedBigInteger('cierre_anual_id');
            $table->foreign('cierre_anual_id')->on('cierre_anuales')->references('id');
            $table->decimal('saldo_anterior', 12, 2)->default(0.00)->nullable();
            $table->decimal('saldo_actual', 12, 2)->default(0.00)->nullable();
            $table->decimal('debito', 12, 2)->nullable()->default(0.00);
            $table->decimal('credito', 12, 2)->nullable()->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cierre_anual_detalles');
    }
};
