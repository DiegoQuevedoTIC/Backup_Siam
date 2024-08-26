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
        Schema::create('saldo_puc_terceros', function (Blueprint $table) {
            $table->id();
            $table->string('tercero')->nullable();
            $table->string('puc')->nullable();
            $table->string('amo')->nullable();
            $table->string('mes')->nullable();
            $table->decimal('saldo_debito', 15, 2)->nullable();
            $table->decimal('saldo_credito', 15, 2)->nullable();
            $table->decimal('saldo', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saldo_puc_terceros');
    }
};
