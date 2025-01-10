<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuotasEncabezadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuotas_encabezados', function (Blueprint $table) {
            $table->id(); // Esto crea un BIGSERIAL PRIMARY KEY
            $table->string('tdocto', 3); // Tipo de documento
            $table->bigInteger('nro_docto'); // Número del documento
            $table->smallInteger('nro_cuota'); // Número de la cuota
            $table->smallInteger('consecutivo'); // Consecutivo del registro
            $table->string('estado', 1)->nullable(); // Estado de la cuota
            $table->string('iden_cuota', 1)->nullable(); // Identificador único de la cuota
            $table->decimal('interes_cte', 12, 4)->nullable(); // Interés corriente
            $table->decimal('interes_mora', 12, 4)->nullable(); // Interés de mora
            $table->date('fecha_vencimiento')->nullable(); // Fecha de vencimiento de la cuota
            $table->date('fecha_pago_total')->nullable(); // Fecha de pago total
            $table->smallInteger('dias_mora')->nullable(); // Días en mora
            $table->decimal('vlr_cuota', 15, 2)->default(0); // Valor de la cuota
            $table->decimal('saldo_capital', 15, 2)->default(0); // Saldo de capital
            $table->decimal('vlr_abono_rec', 15, 2)->default(0); // Valor del abono recibido
            $table->decimal('vlr_abono_ncr', 15, 2)->default(0); // Valor del abono con nota de crédito
            $table->decimal('vlr_abono_dpa', 15, 2)->default(0); // Valor del abono de depósito
            $table->decimal('vlr_descuento', 15, 2)->default(0); // Valor del descuento
            $table->string('forma_descuento', 1)->nullable(); // Forma del descuento
            $table->decimal('vlr_cuentas_orden', 15, 2)->default(0); // Valor en cuentas de orden
            $table->decimal('vlr_causado', 15, 2)->default(0); // Valor causado
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
        Schema::dropIfExists('cuotas_encabezados');
    }
}
