<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateTotalesTableroView extends Migration
{
    /**
     * Ejecutar la migración.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE OR REPLACE VIEW public.totales_tablero AS
            SELECT
                (SELECT count(*) AS count
                 FROM asociados a
                 JOIN estado_clientes e ON e.id = a.estado_cliente_id
                 WHERE e.codigo::text = '1'::text) AS total_asociados,
                (SELECT obtener_saldos.saldo_nuevo
                 FROM obtener_saldos('2014-01-12'::date, '2024-12-11'::date) obtener_saldos(cuenta_puc, descripcion, saldo_anterior, total_debito, total_credito, saldo_nuevo)
                 WHERE obtener_saldos.cuenta_puc = '1'::text) AS total_activo;
        ");
    }

    /**
     * Revertir la migración.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS public.totales_tablero;");
    }
}
