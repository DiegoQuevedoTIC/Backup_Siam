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
        Schema::create('cuotas_descuentos', function (Blueprint $table) {
            $table->id();
            $table->string('cliente');
            $table->boolean('con_descuento')->default(false);
            $table->string('consecutivo')->nullable();
            $table->string('nro_cuota')->nullable();
            $table->string('fecha_vencimiento')->nullable();
            $table->string('fecha_pago_total')->nullable();
            $table->string('estado')->nullable();
            $table->string('vlr_cuota')->nullable();
            $table->string('abono_cuota')->nullable();
            $table->string('vlr_interes')->nullable();
            $table->string('abono_interes')->nullable();
            $table->string('vlr_mora')->nullable();
            $table->string('abono_mora')->nullable();
            $table->string('congelada')->nullable();
            $table->string('consecutivo_padre')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuotas_descuentos');
    }
};
