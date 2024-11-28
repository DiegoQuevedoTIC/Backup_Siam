<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar las migraciones.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE OR REPLACE FUNCTION obtener_saldos(fecha_inicio DATE, fecha_fin DATE, mes_trece BOOLEAN)
            RETURNS TABLE(cuenta_puc TEXT, descripcion TEXT, saldo_anterior NUMERIC, total_debito NUMERIC, total_credito NUMERIC, saldo_nuevo NUMERIC) AS $$
            WITH RECURSIVE Cuentas AS (
                SELECT
                    p.puc,
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
                WHERE
                    c.fecha_comprobante BETWEEN fecha_inicio AND fecha_fin
                AND
                    c.estado = 'Activo'
                GROUP BY
                    p.puc, p.descripcion, p.puc_padre, p.naturaleza

                UNION ALL

                SELECT
                    p.puc,
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
                    sp.puc,
                    CASE
                        WHEN p.naturaleza = 'C' THEN -sp.saldo  -- Invertir el saldo para cuentas de naturaleza crédito
                        WHEN p.naturaleza = 'D' THEN sp.saldo   -- Mantener el saldo para cuentas de naturaleza débito
                        ELSE sp.saldo
                    END AS saldo
                FROM
                    saldo_pucs sp
                LEFT JOIN pucs p ON sp.puc = p.puc
                WHERE
                    sp.amo = CAST(EXTRACT(YEAR FROM fecha_inicio) - CASE WHEN EXTRACT(MONTH FROM fecha_inicio) = 1 THEN 1 ELSE 0 END AS VARCHAR) AND
                    sp.mes = CAST(CASE WHEN EXTRACT(MONTH FROM fecha_inicio) = 1 THEN 12 ELSE EXTRACT(MONTH FROM fecha_inicio) - 1 END AS VARCHAR)
            )

            SELECT
                c.puc AS cuenta_puc,
                c.descripcion AS descripcion,
                COALESCE(sa.saldo, 0) AS saldo_anterior,
                COALESCE(SUM(c.total_debito), 0.00) AS total_debito,
                COALESCE(SUM(c.total_credito), 0.00) AS total_credito,
                CASE
                    WHEN c.naturaleza = 'C' THEN COALESCE(sa.saldo, 0) + COALESCE(SUM(c.total_credito), 0) - COALESCE(SUM(c.total_debito), 0)
                    WHEN c.naturaleza = 'D' THEN COALESCE(sa.saldo, 0) - COALESCE(SUM(c.total_credito), 0) + COALESCE(SUM(c.total_debito), 0)
                    ELSE 0
                END AS saldo_nuevo
            FROM
                Cuentas AS c
            LEFT JOIN
                SaldoAnterior AS sa ON c.puc = sa.puc
            GROUP BY
                c.puc, c.descripcion, sa.saldo, c.naturaleza

            UNION ALL

            SELECT
                p.puc AS cuenta_puc,
                p.descripcion AS descripcion,
                COALESCE(sa.saldo, 0) AS saldo_anterior,
                0.00 AS total_debito,
                0.00 AS total_credito,
                COALESCE(sa.saldo, 0) AS saldo_nuevo
            FROM
                pucs AS p
            LEFT JOIN
                SaldoAnterior AS sa ON p.puc = sa.puc
            WHERE
                COALESCE(sa.saldo, 0) != 0
            AND NOT EXISTS (
                SELECT 1
                FROM Cuentas c
                WHERE c.puc = p.puc
            )
            ORDER BY
                cuenta_puc;
            $$ LANGUAGE SQL;
        ");
    }

    /**
     * Revertir las migraciones.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP FUNCTION IF EXISTS obtener_saldos(DATE, DATE);");
    }
};
