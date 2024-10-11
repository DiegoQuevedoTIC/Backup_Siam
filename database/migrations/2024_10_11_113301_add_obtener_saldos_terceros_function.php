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
        CREATE OR REPLACE FUNCTION obtener_saldos_terceros(fecha_inicio DATE, fecha_fin DATE)
        RETURNS TABLE(cuenta_puc TEXT, tercero TEXT, descripcion TEXT, saldo_anterior NUMERIC, total_debito NUMERIC, total_credito NUMERIC, saldo_nuevo NUMERIC) AS $$
        WITH RECURSIVE Cuentas AS (
            SELECT
                p.puc,
                CAST(t.tercero_id AS TEXT) AS tercero,
                p.descripcion,
                SUM(cl.debito) AS total_debito,
                SUM(cl.credito) AS total_credito,
                p.puc_padre,
                p.naturaleza
            FROM
                comprobantes AS c
            JOIN
                comprobante_lineas AS cl ON cl.comprobante_id = c.id
            LEFT JOIN
                pucs AS p ON cl.pucs_id = p.id
            LEFT JOIN
                terceros AS t ON cl.tercero_id = t.id
            WHERE
                c.fecha_comprobante BETWEEN fecha_inicio AND fecha_fin
            GROUP BY
                p.puc, t.tercero_id, p.descripcion, p.puc_padre, p.naturaleza

            UNION ALL

            SELECT
                p.puc,
                NULL AS tercero,
                p.descripcion,
                c.total_debito,
                c.total_credito,
                p.puc_padre,
                p.naturaleza
            FROM
                Cuentas AS c
            JOIN
                pucs AS p ON c.puc_padre = p.puc
        ),

        SaldoAnterior AS (
            SELECT
                puc,
                saldo
            FROM
                saldo_pucs
            WHERE
                amo = CAST(EXTRACT(YEAR FROM fecha_inicio) AS VARCHAR) AND
                mes = CAST(EXTRACT(MONTH FROM fecha_inicio) - 1 AS VARCHAR)
        )

        SELECT
            c.puc AS cuenta_puc,
            c.tercero,
            c.descripcion AS descripcion,
            COALESCE(sa.saldo, 0) AS saldo_anterior,
            SUM(c.total_debito) AS total_debito,
            SUM(c.total_credito) AS total_credito,
            CASE
                WHEN c.naturaleza = 'C' THEN COALESCE(sa.saldo, 0) + SUM(c.total_credito) - SUM(c.total_debito)
                WHEN c.naturaleza = 'D' THEN COALESCE(sa.saldo, 0) - SUM(c.total_credito) + SUM(c.total_debito)
                ELSE 0
            END AS saldo_nuevo
        FROM
            Cuentas AS c
        LEFT JOIN
            SaldoAnterior AS sa ON c.puc = sa.puc
        GROUP BY
            c.puc, c.tercero, c.descripcion, sa.saldo, c.naturaleza
        HAVING
            SUM(c.total_debito) > 0 OR SUM(c.total_credito) > 0
        ORDER BY
            c.puc;
        $$ LANGUAGE SQL;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP FUNCTION obtener_saldos_terceros");
    }
};
