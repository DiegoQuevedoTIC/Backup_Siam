<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleVencimientoDescuentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_vencimiento_descuento', function (Blueprint $table) {
            $table->id(); // Crea un campo 'id' auto-incremental
            $table->decimal('cliente', 14, 0); // Campo 'cliente' de tipo numeric(14,0)
            $table->decimal('con_descuento', 8, 0); // Campo 'con_descuento' de tipo numeric(8,0)
            $table->decimal('consecutivo', 5, 0); // Campo 'consecutivo' de tipo numeric(5,0)
            $table->decimal('nro_cuota', 5, 0); // Campo 'nro_cuota' de tipo numeric(5,0)
            $table->date('fecha_vencimiento')->nullable(); // Campo 'fecha_vencimiento' de tipo date
            $table->date('fecha_pago_total')->nullable(); // Campo 'fecha_pago_total' de tipo date
            $table->string('estado', 1)->nullable(); // Campo 'estado' de tipo character varying(1)
            $table->double('vlr_cuota')->default(0); // Campo 'vlr_cuota' de tipo double precision
            $table->double('abono_cuota')->default(0); // Campo 'abono_cuota' de tipo double precision
            $table->double('consecutivo_padre')->nullable(); // Campo 'consecutivo_padre' de tipo double precision
            $table->timestamps(); // Crea los campos 'created_at' y 'updated_at'

            // Definir la clave primaria
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_vencimiento_descuento');
    }
}
