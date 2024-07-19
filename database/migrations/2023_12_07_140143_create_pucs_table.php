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
        Schema::create('pucs', function (Blueprint $table) {
            $table->id();
            $table->string('puc',10)->unique();
            $table->string('grupo',1);
            $table->string('descripcion');
            $table->string('nivel' );
            $table->string('naturaleza',1);
            $table->boolean('mayor_rep')->default(false);
            $table->boolean('movimiento')->default(false);
            $table->boolean('subcentro')->default(false);
            $table->boolean('bancaria')->default(false);
            $table->boolean('tercero')->default(false);
            $table->string('puc_padre',10);
            $table->unsignedBigInteger('pucs_id')->nullable();
            $table->foreign('pucs_id')->on('pucs')->references('id');
            $table->boolean('base_gravable')->default(false);
            $table->boolean('mueve_modulo')->default(false);
            $table->boolean('codigo_dian')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pucs');
    }
};
