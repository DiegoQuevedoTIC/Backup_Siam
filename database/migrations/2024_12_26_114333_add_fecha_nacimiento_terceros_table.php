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
        //
        Schema::table('terceros', function (Blueprint $table) {
            $table->string('fecha_nacimiento')->nullable()->after('segundo_apellido');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('terceros', function (Blueprint $table) {
            $table->dropColumn('fecha_nacimiento');
        });
    }
};
