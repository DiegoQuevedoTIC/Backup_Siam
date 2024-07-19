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
        Schema::create('cobranzas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asociado_id');
            $table->date('fecha_gestion');
            $table->string('nro_producto');
            $table->string('tipo_gestion');
            $table->string('detalles_gestion');
            $table->string('resultado');
            $table->string('usuario_gestion');

            $table->foreign('asociado_id')->references('id')->on('asociados');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cobranzas');
    }
};
