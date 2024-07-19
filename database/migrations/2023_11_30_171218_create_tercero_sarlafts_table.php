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
        Schema::create('tercero_sarlafts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tercero_id')->constrained('terceros');
            $table->string('reconocimiento_publico',2)->default('0');
            $table->string('descripcion_reconocimiento')->nullable();
            $table->string('ejerce_cargos_publicos',2)->default('0');
            $table->string('descripcion_cargo_publico')->nullable();
            $table->string('familiar_peps',2)->default('0');
            $table->string('nombre_peps')->nullable();
            $table->integer('peps_id')->nullable();
            $table->foreignId('parentesco_id')->constrained('parentescos')->nullable();
            $table->string('socio_peps',2)->default('0');
            $table->string('declara_renta',2)->default('0');
            $table->string('operacion_moneda_extranjera',2)->default('0');
            $table->string('producto_moneda_extranjera')->nullable();
            $table->foreignId('moneda_id')->constrained('monedas')->nullable();
            $table->string('tipo_producto_moneda_extranjera')->nullable();
            $table->float('monto_inicial',16,2)->nullable();
            $table->float('monto_final', 16,2)->nullable();
            $table->foreignId('pais_id')->constrained('pais')->nullable();
            $table->text('origen_fondos')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tercero_sarlafts');
    }
};
