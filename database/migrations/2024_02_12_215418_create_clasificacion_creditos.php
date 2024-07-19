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
        Schema::create('clasificacion_creditos', function (Blueprint $table) {
            $table->id();
            $table->string('clasificacion');
            $table->string('descripcion');
            $table->integer('nro_salarios_min')->nullable();
            $table->integer('nro_salarios_max')->nullable();
            $table->string('puc_causa_cxc')->nullable();
            $table->string('puc_causa_ingresos')->nullable();
            $table->string('puc_causa_gastos')->nullable();
            $table->string('puc_causa_ctas_orden')->nullable();
            $table->float('porc_causacion')->default(0)->nullable();
            $table->string('puc_aprobacion')->nullable();
            $table->string('puc_contra_partida')->nullable();
            $table->string('puc_provision')->nullable();
            $table->string('puc_prov_int')->nullable();
            $table->string('puc_prov_int_rev')->nullable();
            $table->string('puc_prov_rev')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clasificacion_creditos');
    }
};
