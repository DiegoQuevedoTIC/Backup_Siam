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
        Schema::create('asociados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tercero_id')->constrained('terceros');
            $table->string("tipo_vinculo_id", 3, $unsigned = false);
            $table->foreignId('pagaduria_id')->constrained('pagadurias');
            $table->string("codigo_interno_pag", 50)->nullable();
            $table->foreignId('estado_cliente_id')->constrained('estado_clientes');
            $table->foreignId('banco_id')->constrained('bancos');
            $table->string("cuenta_cliente", 50)->nullable();
            $table->string("observaciones_cliente", 100);
            $table->foreignId('ciudad_nacimiento_id')->constrained('ciudads');
            $table->date("fecha_nacimiento");
            $table->foreignId('tipo_residencia_id')->constrained('tipo_residencias');
            $table->string("tiempo_residencia", 50);
            $table->foreignId('estado_civil_id')->constrained('estado_civils');
            $table->string("conyugue", 100);
            $table->foreignId('parentesco_id')->constrained('parentescos');
            $table->string("direccion_conyugue", 100);
            $table->string("telefono_conyugue", 50);
            $table->string("madre_cabeza", 2);
            $table->integer("no_hijos", $unsigned = false);
            $table->integer("no_personas_cargo", $unsigned = false);
            $table->foreignId('nivel_escolar_id')->constrained('nivel_escolars');
            $table->string("ultimo_grado", 100);
            $table->foreignId('profesion_id')->constrained('profesions');
            $table->foreignId('actividad_economica_id')->constrained('actividad_economicas');
            $table->string('genero_id');
            $table->string("empresa", 100);
            $table->string("telefono_empresa", 50);
            $table->date("fecha_ingreso_laboral");
            $table->string("direccion_empresa", 100);
            $table->foreignId('tipo_contrato_id')->constrained('tipo_contratos');
            $table->date("fecha_asociacion");
            $table->boolean('habil')->default(true);
            $table->foreignId('calificacion_cliente_id')->constrained('calificacion_clientes');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asociados');
    }
};
