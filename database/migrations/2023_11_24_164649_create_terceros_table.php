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
        Schema::create('terceros', function (Blueprint $table) {
            $table->id();
            $table->string('tercero_id', 16)->unique();
            $table->string('digito_verificacion', 1)->nullable();
            $table->foreignId('tipo_identificacion_id')->constrained('tipo_identificacions');
            $table->string('nombres');
            $table->string('primer_apellido');
            $table->string('segundo_apellido')->nullable();
            $table->string('direccion');
            $table->string('telefono',10);
            $table->string('celular',12);
            $table->string('email');
            $table->string('tipo_tercero');
            $table->foreignId('pais_id')->constrained('pais');
            $table->foreignId('ciudad_id')->constrained('ciudads');
            $table->foreignId('barrio_id')->constrained('barrios');
            $table->foreignId('tipo_contribuyente_id')->constrained('tipo_contribuyentes');
            $table->text('observaciones')->nullable();
            $table->string('autorizacion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terceros');
    }
};
