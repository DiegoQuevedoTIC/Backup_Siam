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
        CREATE MATERIALIZED VIEW vista_balance_tercero AS
        WITH RECURSIVE cuentas_jerarquicas AS (
            SELECT
                p.puc,
                p.descripcion,
                SUM(CASE WHEN cl.debito > 0 THEN cl.debito ELSE 0 END) AS debitos,
                SUM(CASE WHEN cl.credito > 0 THEN cl.credito ELSE 0 END) AS creditos,
                CASE
                    WHEN p.naturaleza = 'D' THEN SUM(CASE WHEN cl.debito > 0 THEN cl.debito ELSE 0 END) - SUM(CASE WHEN cl.credito > 0 THEN cl.credito ELSE 0 END)
                    WHEN p.naturaleza = 'C' THEN -SUM(CASE WHEN cl.debito > 0 THEN cl.debito ELSE 0 END) + SUM(CASE WHEN cl.credito > 0 THEN cl.credito ELSE 0 END)
                END AS saldo_nuevo,
                MAX(c.fecha_comprobante) AS fecha_comprobante
            FROM
                pucs p
                LEFT JOIN comprobante_lineas cl ON p.id = cl.pucs_id
                LEFT JOIN comprobantes c ON cl.comprobante_id = c.id
            GROUP BY
                p.puc, p.descripcion, p.naturaleza
        ),
        cuentas_superiores AS (
            SELECT
                puc,
                descripcion,
                SUBSTRING(puc FROM 1 FOR LENGTH(puc) - 2) AS jerarquia
            FROM
                pucs
            WHERE
                LENGTH(puc) > 1
        )
        SELECT
            cj.puc,
            cj.descripcion,
            cj.fecha_comprobante,
            COALESCE((SELECT CAST(sp.tercero AS numeric) FROM saldo_puc_terceros sp WHERE sp.puc = cj.puc ORDER BY sp.amo DESC LIMIT 1), NULL) AS tercero,
            COALESCE((SELECT CAST(sp.saldo AS numeric) FROM saldo_puc_terceros sp WHERE sp.puc = cj.puc ORDER BY sp.amo DESC LIMIT 1), 0.00) AS saldo_anterior,
            COALESCE(SUM(cj.debitos), 0.00) AS debitos,
            COALESCE(SUM(cj.creditos), 0.00) AS creditos,
            COALESCE(SUM(cj.saldo_nuevo), 0.00) AS saldo_nuevo
        FROM
            cuentas_jerarquicas cj
        GROUP BY
            cj.puc, cj.descripcion, cj.fecha_comprobante
        HAVING
            (COALESCE(SUM(cj.debitos), 0.00) > 0 OR COALESCE(SUM(cj.creditos), 0.00) > 0)
            OR (MAX(cj.fecha_comprobante) IS NULL)  -- Verifica si no hay comprobantes
        UNION ALL
        SELECT
            cs.puc,
            cs.descripcion,
            NULL AS fecha_comprobante,
            NULL AS tercero,
            0.00 AS saldo_anterior,
            0.00 AS debitos,
            0.00 AS creditos,
            0.00 AS saldo_nuevo
        FROM
            cuentas_superiores cs
        WHERE
            cs.puc NOT IN (SELECT cj.puc FROM cuentas_jerarquicas cj)
        ORDER BY
            puc
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP MATERIALIZED VIEW vista_balance_tercero");
    }
};
