<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        DB::statement("
            CREATE VIEW movimiento_auxiliar_cuentas AS
            SELECT
                p.puc,
                p.naturaleza,
                tpd.sigla AS documento,
                t.tercero_id AS tercero,
                cl.descripcion_linea,
                cl.debito,
                cl.credito,
                c.fecha_comprobante AS fecha,
                c.n_documento
            FROM
                comprobantes AS c
            JOIN
                comprobante_lineas AS cl ON c.id = cl.comprobante_id
            LEFT JOIN
                terceros AS t ON cl.tercero_id = t.id
            LEFT JOIN
                pucs AS p ON cl.pucs_id = p.id
            LEFT JOIN
                tipo_documento_contables AS tpd ON c.tipo_documento_contables_id = tpd.id;
        ");
    }

    public function down()
    {
        DB::statement("DROP MATERIALIZED VIEW IF EXISTS movimiento_auxiliar_cuentas;");
    }
};
