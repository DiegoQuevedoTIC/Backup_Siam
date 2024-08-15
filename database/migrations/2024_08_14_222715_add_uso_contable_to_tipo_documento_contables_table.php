<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUsoContableToTipoDocumentoContablesTable extends Migration
{
    public function up(): void
    {
        Schema::table('tipo_documento_contables', function (Blueprint $table) {
            $table->boolean('uso_contable')->default(false)->after('fecha_modificable');
        });
    }

    public function down(): void
    {
        Schema::table('tipo_documento_contables', function (Blueprint $table) {
            $table->dropColumn('uso_contable');
        });
    }
}
