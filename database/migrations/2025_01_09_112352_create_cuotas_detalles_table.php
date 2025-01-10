<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuotasDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuotas_detalles', function (Blueprint $table) {
            $table->id(); // Esto crea un BIGSERIAL PRIMARY KEY
            $table->string('tdocto', 3); // Tipo de documento
            $table->bigInteger('nro_docto'); // Número del documento
            $table->smallInteger('nro_cuota'); // Número de la cuota
            $table->smallInteger('consecutivo'); // Consecutivo del detalle
            $table->bigInteger('con_descuento'); // Concepto de descuento
            $table->string('estado', 1)->nullable(); // Estado del detalle
            $table->date('fecha_pago_total')->nullable(); // Fecha del pago total
            $table->decimal('vlr_detalle', 15, 2)->default(0); // Valor del detalle
            $table->decimal('vlr_abono_rec', 15, 2)->default(0); // Valor del abono recibido
            $table->decimal('vlr_abono_ncr', 15, 2)->default(0); // Valor del abono con nota de crédito
            $table->decimal('vlr_abono_dpa', 15, 2)->default(0); // Valor del abono de depósito
            $table->decimal('vlr_descuento', 15, 2)->default(0); // Valor del descuento
            $table->decimal('vlr_cuentas_orden', 15, 2)->default(0); // Valor en cuentas de orden
            $table->decimal('vlr_causado', 15, 2)->default(0); // Valor causado
            $table->decimal('vlr_causado_abonos', 15, 2)->default(0); // Valor causado por abonos
            $table->decimal('vlr_cuentas_orden_abonos', 15, 2)->default(0); // Valor en cuentas de orden por abonos
            $table->decimal('vlr_detalle_niif', 20, 2)->nullable(); // Valor del detalle NIIF
            $table->string('vendido', 2)->default('N'); // Estado de venta
            $table->timestamps(); // Crea created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cuotas_detalles');
    }
}
