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
        Schema::create('comprobantes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tipo_documento_contables_id');
            $table->foreign('tipo_documento_contables_id')->on('tipo_documento_contables')->references('id');
            $table->string('n_documento', 50);
            $table->unsignedBigInteger('tercero_id');
            $table->foreign('tercero_id')->on('terceros')->references('id')->onDelete('restrict')->onUpdate('restrict');
            $table->boolean('is_plantilla');
            $table->string('descripcion_comprobante');
            $table->date('fecha_comprobante')->default(date('Y-m-d'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comprobantes');
    }
};
