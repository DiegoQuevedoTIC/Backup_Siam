<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentosCancelacionesDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_cancelaciones_detalles', function (Blueprint $table) {
            $table->id(); // Esto crea un BIGSERIAL PRIMARY KEY
            $table->string('tipo_documento', 3); // Tipo de documento
            $table->bigInteger('numero_documento'); // Número del documento
            $table->smallInteger('consecutivo'); // Consecutivo del registro
            $table->bigInteger('cliente_id')->nullable(); // Identificador del cliente
            $table->string('tipo_pago', 3)->nullable(); // Tipo de pago
            $table->string('tipo_documento_dvt', 3)->nullable(); // Tipo de documento DVT
            $table->bigInteger('numero_documento_dvt')->nullable(); // Número del documento DVT
            $table->smallInteger('numero_cuota_dvt')->nullable(); // Número de la cuota DVT
            $table->smallInteger('consecutivo_dvt')->nullable(); // Consecutivo del detalle DVT
            $table->bigInteger('concepto_descuento_dvt')->nullable(); // Concepto de descuento DVT
            $table->string('tipo_documento_dre', 3)->nullable(); // Tipo de documento DRE
            $table->bigInteger('numero_documento_dre')->nullable(); // Número del documento DRE
            $table->bigInteger('concepto_descuento_vde')->nullable(); // Concepto de descuento VDE
            $table->bigInteger('consecutivo_vde')->nullable(); // Consecutivo VDE
            $table->smallInteger('numero_cuota_vde')->nullable(); // Número de la cuota VDE
            $table->bigInteger('concepto_descuento_lcd')->nullable(); // Concepto de descuento LCD
            $table->decimal('valor_pago', 15, 2)->default(0); // Valor pagado
            $table->decimal('valor_descuento', 15, 2)->default(0); // Valor del descuento
            $table->bigInteger('servicio_concepto_lcd')->nullable(); // Servicio asociado al concepto LCD
            $table->string('tipo_recalculo', 3)->nullable(); // Tipo de recálculo
            $table->decimal('valor_causado', 15, 2)->default(0); // Valor causado
            $table->decimal('valor_cuenta_orden', 15, 2)->default(0); // Valor en cuenta de orden
            $table->string('numero_credito', 16)->nullable(); // Número del crédito
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
        Schema::dropIfExists('documentos_cancelaciones_detalles');
    }
}
