<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConceptoDescuentosTable extends Migration
{
    /**
     * Ejecutar la migración.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('concepto_descuentos', function (Blueprint $table) {
            $table->bigIncrements('id'); // Clave primaria auto-incremental
            $table->bigInteger('codigo_descuento'); // Código único del descuento
            $table->string('descripcion', 60)->notNullable(); // Descripción del descuento
            $table->char('reservado', 1)->default('N'); // Indicador reservado (S/N)
            $table->string('cuenta_contable', 14)->nullable(); // Código PUC para contabilidad
            $table->char('genera_interes_x_pagar', 1)->default('N'); // Indicador de generación de interés por pagar (S/N)
            $table->string('cuenta_interes', 14)->nullable(); // Código PUC para intereses
            $table->decimal('porcentaje_interes', 7, 4)->default(0); // Porcentaje de interés
            $table->string('cuenta_rete_fuente', 14)->nullable(); // Código PUC para retención en la fuente
            $table->decimal('porcentaje_rete_fuente', 7, 4)->default(0); // Porcentaje de retención en la fuente
            $table->decimal('base_rete_fuente', 15, 2)->default(0); // Base para calcular retención en la fuente
            $table->char('identificador_concepto', 2)->nullable(); // Identificador del concepto
            $table->char('revalorizacion', 1)->nullable(); // Indicador de revalorización (S/N)
            $table->char('distribuye', 1)->nullable(); // Indicador de distribución (S/N)
            $table->char('genera_extracto', 1)->nullable(); // Indicador de generación de extracto (S/N)
            $table->char('genera_cruce', 1)->default('N'); // Indicador de generación de cruces (S/N)
            $table->char('obliga_retiro_total', 1)->default('N'); // Indicador si obliga a retiro total (S/N)
            $table->decimal('porcentaje_interes_ef', 7, 4)->default(0); // Porcentaje de interés efectivo
            $table->timestamps(); // Fecha de creación y última actualización
        });
    }

    /**
     * Revertir la migración.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('concepto_descuentos');
    }
}
