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
        Schema::create('tipo_contribuyentes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); 
            $table->text('descripcion')->nullable(); // Descripción opcional del tipo de contribuyente
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_contribuyentes');
    }
};
