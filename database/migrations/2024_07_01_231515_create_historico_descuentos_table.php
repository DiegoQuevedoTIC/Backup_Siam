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
        Schema::create('historico_descuentos', function (Blueprint $table) {
            $table->id();
            $table->string('cliente');
            $table->boolean('con_descuento');
            $table->integer('linea')->nullable();
            $table->boolean('con_servicio');
            $table->date('fecha')->nullable();
            $table->string('hora')->nullable();
            $table->string('grupo_docto')->nullable();
            $table->string('compania_docto')->nullable();
            $table->string('agencia_docto')->nullable();
            $table->string('tdocto')->nullable();
            $table->string('nro_docto')->nullable();
            $table->float('vlr_debito')->default(0.00);
            $table->float('vlr_credito')->default(0.00);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historico_descuentos');
    }
};
