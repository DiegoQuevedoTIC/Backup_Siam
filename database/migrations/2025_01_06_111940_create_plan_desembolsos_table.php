<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('plan_desembolsos', function (Blueprint $table) {
            $table->id(); // Clave primaria auto-incremental (columna 'id')
            $table->bigInteger('solicitud_id')->unsigned(); // Identificador único de la solicitud
            $table->smallInteger('plan_numero'); // Número del plan de desembolso
            $table->date('fecha_plan')->nullable(); // Fecha del plan
            $table->date('fecha_inicio')->nullable(); // Fecha de inicio del plan
            $table->decimal('valor_plan', 15, 2)->default(0); // Valor asociado al plan
            $table->string('modo_desembolso', 1)->nullable(); // Modo de desembolso
            $table->string('tipo_documento_enc', 3)->nullable(); // Tipo de documento del encabezado
            $table->bigInteger('nro_documento_vto_enc')->nullable(); // Número del documento de vencimiento
            $table->string('tipo_documento_can', 3)->nullable(); // Tipo de documento asociado a la cancelación
            $table->bigInteger('nro_documento_can')->nullable(); // Número del documento de cancelación
            $table->timestamps(); // Agrega columnas 'created_at' y 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_desembolsos');
    }
};
