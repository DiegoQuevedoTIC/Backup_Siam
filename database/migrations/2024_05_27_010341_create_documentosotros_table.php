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
        Schema::create('documentosotros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('documentoclase_id')->constrained('documentoclases');
            $table->foreignId('documentotipo_id')->constrained('documentotipos');
            $table->date('fecha_documento')->default(date('Y-m-d'));
            $table->string('ruta_imagen')->nullable();
            $table->string('descripcion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentosotros');
    }
};
