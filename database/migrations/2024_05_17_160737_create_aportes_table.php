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
        Schema::create('aportes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asociado_id');
            $table->string('concepto');
            $table->float('movimientos_debito')->default(0.00);
            $table->float('movimientos_credito')->default(0.00);
            $table->float('saldo')->default(0.00);

            $table->foreign('asociado_id')->references('id')->on('asociados');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aportes');
    }
};
