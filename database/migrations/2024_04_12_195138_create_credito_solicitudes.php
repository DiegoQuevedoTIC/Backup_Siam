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
        Schema::create('credito_solicitudes', function (Blueprint $table) {
            $table->id();
            $table->integer('solicitud');
            $table->integer('linea');
            $table->unsignedBigInteger('asociado_id');
            $table->string('asociado')->nullable();
            $table->string('estado')->nullable();
            $table->date('fecha_solicitud')->nullable();
            $table->float('vlr_solicitud')->default(0)->nullable();
            $table->integer('periodo_pago')->nullable();
            $table->integer('moneda')->nullable();
            $table->string('destino_prestamo')->nullable();
            $table->integer('motivo_rechazo')->nullable();
            $table->integer('nro_cuotas_max')->nullable();
            $table->float('vlr_aprobado')->default(0)->nullable();
            $table->float('vlr_planes')->default(0)->nullable();
            $table->float('vlr_girado')->default(0)->nullable();
            $table->date('fecha_novedad')->nullable();
            $table->string('tipo_desembolso')->nullable();
            $table->string('int_cte_mlv')->nullable();
            $table->unsignedBigInteger('tasa_id');
            $table->string('int_mora_mlv')->nullable();
            $table->float('interes_mora')->default(0)->nullable();
            $table->string('tipo_cuota')->nullable();
            $table->float('tasa_incremento')->nullable();
            $table->integer('nro_cuotas_gracia')->default(0);
            $table->string('abonos_extra')->nullable();
            $table->integer('analista')->nullable();
            $table->string('ente_aprobador')->nullable();
            $table->integer('nro_acta_aprob')->nullable();
            $table->string('observaciones')->nullable();
            $table->string('usuario_crea')->nullable();
            $table->string('reestructuracion')->nullable();
            $table->integer('empresa')->default(0)->nullable();
            $table->integer('tercero_asesor')->nullable();
            $table->string('departamento_solicitud')->nullable();
            $table->integer('estado_novedad')->nullable();
            $table->integer('ultima_novedad')->nullable();
            $table->string('compra_cartera')->default('N')->nullable();
            $table->string('estado_id_regreso')->default('N')->nullable();
            $table->string('departamento_anterior')->nullable();
            $table->integer('estado_novedad_anterior')->nullable();
            $table->integer('novedad_anterior')->nullable();
            $table->date('fecha_ultima_novedad')->nullable();
            $table->string('lista_desembolso')->default('S');
            $table->longText('observacion_novedad')->nullable();
            $table->string('usuario_modifica')->nullable();
            $table->integer('capacidad_endeudamiento')->nullable();
            $table->float('vlr_cuota')->nullable();
            $table->integer('ciius')->default(0)->nullable();
            $table->date('fecha_primer_vto')->nullable();


            $table->foreign('asociado_id')->references('id')->on('asociados');
            $table->foreign('tasa_id')->references('id')->on('tasas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credito_solicitudes');
    }
};
