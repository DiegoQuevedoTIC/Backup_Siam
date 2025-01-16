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
        Schema::create('garantias_cartera', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asociado_id')->notNull();
            $table->string('tipo_garantia_id', 1)->notNull();
            $table->bigInteger('numero_documento_garantia')->nullable();
            $table->bigInteger('tercero_garantia')->nullable();
            $table->string('estado', 1)->default('A')->notNull();
            $table->string('nro_escr_o_matri', 255)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->string('ciudad_registro', 255)->nullable();
            $table->double('valor_avaluo')->default(0)->notNull();
            $table->date('fecha_avaluo')->nullable();
            $table->boolean('bien_con_prenda')->default(false)->notNull();
            $table->boolean('bien_sin_prenda')->default(false)->notNull();
            $table->double('valor_avaluo_comercial')->default(0)->notNull();
            $table->string('observaciones', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('garantias');
    }
};
