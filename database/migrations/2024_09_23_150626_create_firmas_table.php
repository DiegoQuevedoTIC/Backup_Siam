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
        Schema::create('firmas', function (Blueprint $table) {
            $table->id();
            $table->string('representante_legal');
            $table->string('revisor_fiscal');
            $table->string('contador');
            $table->string('ci_representante_legal');
            $table->string('matricula_representante_legal');
            $table->string('ci_revisor_fiscal');
            $table->string('matricula_revisor_fiscal');
            $table->string('ci_contador');
            $table->string('matricula_contador');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('firmas');
    }
};
