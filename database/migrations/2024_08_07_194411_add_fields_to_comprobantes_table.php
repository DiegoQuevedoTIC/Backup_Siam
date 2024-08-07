<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToComprobantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('comprobantes', function (Blueprint $table) {
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');
            $table->string('cheque_id', 10)->nullable();
            $table->string('usuario_original', 12)->nullable();
            $table->string('usuario_modifica', 12)->nullable();
            $table->unsignedInteger('cuenta_bancaria_id')->nullable();
            $table->enum('tipo_giro', ['Cheque', 'Transferencia', 'Efectivo'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('comprobantes', function (Blueprint $table) {
            $table->dropColumn(['estado', 'cheque_id', 'usuario_original', 'usuario_modifica', 'cuenta_bancaria_id', 'tipo_giro']);
        });
    }
}
