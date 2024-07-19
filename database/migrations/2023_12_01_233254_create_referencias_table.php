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
        Schema::create('referencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tercero_id')->constrained('terceros');
            $table->string('tipo_referencia');
            $table->string('nombre_referencia');
            $table->foreignId('parentesco_id')->constrained('parentescos');
            $table->string('direccion_referencia');
            $table->string('telefono_referencia',10);
            $table->string('mail_referencia');
            $table->string('direccion_oficina_referencia');
            $table->string('telefono_oficina_referencia',10);
            $table->string('mail_oficina_referencia');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referencias');
    }
};
