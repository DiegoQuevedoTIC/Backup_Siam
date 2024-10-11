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
    public function up()
    {
        $sql = <<<SQL
        CREATE OR REPLACE PROCEDURE cierre_mensual(fecha_inicial DATE, fecha_final DATE, proceso INT)
        LANGUAGE plpgsql AS $$
        DECLARE
            registro RECORD;
            registro_tercero RECORD;
        BEGIN
            FOR registro IN SELECT * FROM obtener_saldos(fecha_inicial, fecha_final) LOOP
                INSERT INTO saldo_pucs(puc, amo, mes, saldo_debito, saldo_credito, saldo, created_at)
                VALUES (registro.cuenta_puc, EXTRACT(YEAR FROM fecha_inicial), EXTRACT(MONTH FROM fecha_inicial),
                        registro.total_debito, registro.total_credito, registro.saldo_nuevo, now())
                ON CONFLICT (puc, amo, mes) DO UPDATE
                SET saldo_debito = saldo_pucs.saldo_debito + EXCLUDED.saldo_debito,
                    saldo_credito = saldo_pucs.saldo_credito + EXCLUDED.saldo_credito,
                    saldo = saldo_pucs.saldo + EXCLUDED.saldo;
            END LOOP;

            FOR registro_tercero IN SELECT * FROM obtener_saldos_terceros(fecha_inicial, fecha_final) LOOP
                INSERT INTO saldo_puc_terceros(tercero, puc, amo, mes, saldo_debito, saldo_credito, saldo, created_at)
                VALUES (registro_tercero.tercero::TEXT, registro_tercero.cuenta_puc, EXTRACT(YEAR FROM fecha_inicial), EXTRACT(MONTH FROM fecha_inicial),
                        registro_tercero.total_debito, registro_tercero.total_credito, registro_tercero.saldo_nuevo, now())
                ON CONFLICT (tercero, amo, mes) DO UPDATE
                SET saldo_debito = saldo_puc_terceros.saldo_debito + EXCLUDED.saldo_debito,
                    saldo_credito = saldo_puc_terceros.saldo_credito + EXCLUDED.saldo_credito,
                    saldo = saldo_puc_terceros.saldo + EXCLUDED.saldo;
            END LOOP;

            UPDATE cierre_mensuales SET estado = 'completado' WHERE id = proceso;

        EXCEPTION
            WHEN OTHERS THEN
                UPDATE cierre_mensuales SET estado = 'fallido' WHERE id = proceso;
                RAISE;
        END;
        $$;
        SQL;

        DB::unprepared($sql);
    }

    public function down()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS calcular_saldo_mes");
    }
};
