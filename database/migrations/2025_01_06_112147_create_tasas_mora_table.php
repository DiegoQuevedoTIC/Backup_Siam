<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasasMoraTable extends Migration
{
    /**
     * Ejecutar la migración.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasas_mora', function (Blueprint $table) {
            $table->double('tasa_int_mora')->notNullable();
            $table->double('tasa_int_mora_nominal')->default(0);
        });
    }

    /**
     * Revertir la migración.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasas_mora');
    }
}
