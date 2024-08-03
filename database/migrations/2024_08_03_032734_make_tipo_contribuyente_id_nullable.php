<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeTipoContribuyenteIdNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('terceros', function (Blueprint $table) {
            $table->unsignedBigInteger('tipo_contribuyente_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('terceros', function (Blueprint $table) {
            $table->unsignedBigInteger('tipo_contribuyente_id')->nullable(false)->change();
        });
    }
}
