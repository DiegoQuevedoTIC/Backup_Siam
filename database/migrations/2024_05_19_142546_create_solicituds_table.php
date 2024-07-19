<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('solicituds', function (Blueprint $table) {
            $table->id();
            $table->integer('solicitud');
            $table->integer('linea');
            $table->unsignedBigInteger('asociado_id');
            $table->string('asociado')->nullable();
            $table->string('estado')->nullable();
            $table->date('fecha_solicitud')->nullable();
            $table->float('vlr_solicitud')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicituds');
    }
};
