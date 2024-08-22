<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cierre_mensuales', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_cierre');
            $table->enum('mes_cierre', [
                '1',
                '2',
                '3',
                '4',
                '5',
                '6',
                '7',
                '8',
                '9',
                '10',
                '11',
                '12'
            ]);
            $table->enum('estado', ['procesando', 'completado', 'fallido'])->default('procesando');
            $table->unsignedBigInteger('user_id')->default(Auth::id());
            $table->foreign('user_id')->on('users')->references('id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cierre_mensuales');
    }
};
