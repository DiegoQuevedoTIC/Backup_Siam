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
        Schema::create('novedad_terceros', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 3); 
            $table->string('nombre');
            $table->string('descripcion'); 
            $table->boolean('cambiaestado')->default(false);
            $table->foreignId('estado_cliente_id')->constrained('estado_clientes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('novedad_terceros');
    }
};
