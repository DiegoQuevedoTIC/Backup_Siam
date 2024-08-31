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
        CREATE OR REPLACE PROCEDURE calcular_saldo_mes(m INT, anio INT, proceso INT)
        LANGUAGE plpgsql AS $$
        DECLARE
            saldo NUMERIC(15, 2);
            total_creditos NUMERIC := 0;
            total_debitos NUMERIC := 0;
            nat CHAR(1);
            comprobante RECORD;  -- Variable para almacenar cada comprobante
            linea RECORD;        -- Variable para almacenar cada línea de comprobante
            puc_value INT;       -- Variable para almacenar el valor de puc
        BEGIN
            -- 1. Buscar en la tabla comprobantes el que corresponda con el mes y año
            FOR comprobante IN SELECT * FROM comprobantes
                                WHERE EXTRACT(MONTH FROM fecha_comprobante) = m
                                AND EXTRACT(YEAR FROM fecha_comprobante) = anio LOOP
                -- Reiniciar totales para cada comprobante
                total_creditos := 0;
                total_debitos := 0;

                -- 2. Buscar luego todas las líneas asociadas a los comprobantes
                FOR linea IN SELECT * FROM comprobante_lineas WHERE comprobante_id = comprobante.id LOOP
                    -- 3. Sumar todos los créditos y los débitos
                    total_creditos := total_creditos + COALESCE(linea.credito, 0);
                    total_debitos := total_debitos + COALESCE(linea.debito, 0);
                END LOOP;

                -- 4. Crear el saldo proveniente de la sumatoria anterior
                saldo := total_creditos - total_debitos;

                -- 5. Revisar naturaleza de cuenta PUC para verificar si se suma o se resta
                SELECT naturaleza, puc INTO nat, puc_value FROM pucs WHERE id = linea.pucs_id;

                -- 6. Realizar la sumatoria o resta al saldo dependiendo de la naturaleza de la cuenta PUC
                IF nat = 'D' THEN
                    saldo := saldo + total_debitos;  -- Si es deudora, se suma
                ELSE
                    saldo := saldo - total_creditos; -- Si es acreedora, se resta
                END IF;

                -- 7. Verificar si los saldos son diferentes de 0 antes de insertar
                IF total_debitos <> 0 OR total_creditos <> 0 OR saldo <> 0 THEN
                    -- Insertar o actualizar el saldo en la tabla correspondiente
                    INSERT INTO saldo_pucs(puc, amo, mes, saldo_debito, saldo_credito, saldo, created_at)
                    VALUES (puc_value, anio, m, total_debitos, total_creditos, saldo, now())
                    ON CONFLICT (puc, amo, mes) DO UPDATE
                    SET saldo_debito = saldo_pucs.saldo_debito + EXCLUDED.saldo_debito,
                        saldo_credito = saldo_pucs.saldo_credito + EXCLUDED.saldo_credito,
                        saldo = saldo_pucs.saldo + EXCLUDED.saldo;

                    -- Insertamos todos los detalles por terceros
                    INSERT INTO saldo_puc_terceros(tercero, puc, amo, mes, saldo_debito, saldo_credito, saldo, created_at)
                    VALUES (linea.tercero_id, puc_value, anio, m, total_debitos, total_creditos, saldo, now())
                    ON CONFLICT (tercero, amo, mes) DO UPDATE
                    SET saldo_debito = saldo_puc_terceros.saldo_debito + EXCLUDED.saldo_debito,
                        saldo_credito = saldo_puc_terceros.saldo_credito + EXCLUDED.saldo_credito,
                        saldo = saldo_puc_terceros.saldo + EXCLUDED.saldo;
                END IF;

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
