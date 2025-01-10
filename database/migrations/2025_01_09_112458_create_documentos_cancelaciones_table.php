<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentosCancelacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_cancelaciones', function (Blueprint $table) {
            $table->id(); // Esto crea un BIGSERIAL PRIMARY KEY
            $table->string('tdocto', 3); // Tipo de documento
            $table->bigInteger('id_proveedor')->nullable(); // Identificador del proveedor
            $table->date('fecha_docto')->nullable(); // Fecha del documento
            $table->date('fecha_pago_total')->nullable(); // Fecha del pago total
            $table->bigInteger('cliente')->nullable(); // Cliente asociado
            $table->string('contabilizado', 1)->nullable(); // Indicador de contabilización
            $table->bigInteger('con_nota_credito')->nullable(); // Concepto de nota crédito
            $table->smallInteger('moneda')->nullable(); // Moneda utilizada
            $table->decimal('vlr_pago_efectivo', 15, 2)->default(0); // Valor pagado en efectivo
            $table->decimal('vlr_pago_cheque', 15, 2)->default(0); // Valor pagado con cheque
            $table->decimal('vlr_descuento', 15, 2)->default(0); // Valor de descuento
            $table->string('usuario_crea', 12); // Usuario creador del registro
            $table->decimal('vlr_pago_otros', 15, 2)->default(0); // Otros valores de pago
            $table->string('observaciones', 250)->nullable(); // Observaciones adicionales
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
        Schema::dropIfExists('documentos_cancelaciones');
    }
}
