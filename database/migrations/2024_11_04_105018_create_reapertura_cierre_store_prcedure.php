<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
        CREATE OR REPLACE PROCEDURE reaperturar_cierre(mes_param VARCHAR, anio_param VARCHAR)
        LANGUAGE plpgsql AS $$
        BEGIN
            -- Eliminar registros de la tabla saldo_pucs
            DELETE FROM saldo_pucs
            WHERE (amo::INT > anio_param::INT) OR (amo::INT = anio_param::INT AND mes::INT >= mes_param::INT);

            -- Eliminar registros de la tabla saldo_puc_terceros
            DELETE FROM saldo_puc_terceros
            WHERE (amo::INt > anio_param::INT) OR (amo::INT = anio_param::INT AND mes::INT >= mes_param::INT);

            RAISE NOTICE 'Registros eliminados desde %/% hasta hoy.', mes_param, anio_param;
        END;
        $$;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP PROCEDURE IF EXISTS reaperturar_cierre(VARCHAR, VARCHAR);');
    }
};
