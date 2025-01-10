<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarteraComposicionConceptosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cartera_composicion_conceptos', function (Blueprint $table) {
            $table->id(); // Esto crea un BIGSERIAL PRIMARY KEY
            $table->string('tipo_documento', 3); // Tipo de documento
            $table->bigInteger('numero_documento'); // Número del documento
            $table->bigInteger('concepto_descuento'); // Concepto de descuento
            $table->smallInteger('prioridad')->nullable(); // Prioridad del concepto
            $table->string('valor', 1)->nullable(); // Indicador de valor
            $table->decimal('valor_con_descuento', 15, 2)->default(0); // Valor con descuento aplicado
            $table->decimal('porcentaje_descuento', 7, 4)->default(0); // Porcentaje aplicado al descuento
            $table->decimal('valor_a_dividir', 15, 2)->default(0); // Valor que debe dividirse
            $table->string('comodin', 1)->default('N'); // Indicador de comodín
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
        Schema::dropIfExists('cartera_composicion_conceptos');
    }
}
