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
        Schema::create('tipo_documento_contables', function (Blueprint $table) {
            $table->id();
            $table->string('sigla',3)->unique();
            $table->string('tipo_documento');
            $table->string('clase_origen');
            $table->integer('numerador');
            $table->boolean('fecha_modificable')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_documento_contables');
    }
};
