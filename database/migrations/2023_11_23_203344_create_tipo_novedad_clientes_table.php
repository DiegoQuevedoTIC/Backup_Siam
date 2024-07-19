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
        Schema::create('tipo_novedad_clientes', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 3) ->unique(); 
            $table->string('nombre');
            $table->text('descripcion');
            $table->string('cambiaestado');
            $table->string('nuevoestado', 3) ; 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_novedad_clientes');
    }
};
