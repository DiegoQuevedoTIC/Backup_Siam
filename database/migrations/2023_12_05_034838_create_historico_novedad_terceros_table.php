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
        Schema::create('historico_novedad_terceros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tercero_id')->constrained('terceros');
            $table->foreignId('novedad_id')->constrained('novedad_terceros');
            $table->date("fecha_novedad");
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historico_novedad_terceros');
    }
};
