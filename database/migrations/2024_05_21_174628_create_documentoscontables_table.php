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
        Schema::create('documentoscontables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('documentoclase_id');
            $table->unsignedBigInteger('documentotipo_id');
            $table->string('llave_de_consulta_id');
            $table->string('ruta_imagen')->nullable();
            $table->string('ruta_imagen_1')->nullable();
            $table->foreign('documentoclase_id')->references('id')->on('documentoclases');
            $table->foreign('documentotipo_id')->references('id')->on('documentotipos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentoscontables');
    }
};
