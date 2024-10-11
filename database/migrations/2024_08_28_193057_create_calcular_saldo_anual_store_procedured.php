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
        CREATE OR REPLACE PROCEDURE cierre_anual(anio INT, proceso INT)
        LANGUAGE plpgsql AS $$
        DECLARE
            saldo NUMERIC(15, 2);
            total_creditos NUMERIC := 0;
            total_debitos NUMERIC := 0;
            nat CHAR(1);
            comprobante RECORD;  -- Variable para almacenar cada comprobante
            linea RECORD;        -- Variable para almacenar cada línea de comprobante
            puc_value INT;       -- Variable para almacenar el valor de puc
            grupo_puc INT;       -- Variable para almacenar el grupo de la cuenta PUC
            nuevo_comprobante_id INT; -- Variable para almacenar el ID del nuevo comprobante
            linea_num INT := 1;  -- Inicializar el contador de líneas
        BEGIN
            -- 1. Crear un nuevo comprobante
            INSERT INTO comprobantes (tipo_documento_contables_id, n_documento, tercero_id, is_plantilla, descripcion_comprobante, fecha_comprobante, estado)
            VALUES (14, '39', 1454, false, 'Comprobante de Cierre Anual', CURRENT_DATE, 'Activo')
            RETURNING id INTO nuevo_comprobante_id; -- Obtener el ID del nuevo comprobante

            -- 2. Buscar en la tabla comprobantes el que corresponda con el año
            FOR comprobante IN SELECT * FROM comprobantes WHERE EXTRACT(YEAR FROM fecha_comprobante) = anio LOOP
                -- Reiniciar totales para cada comprobante
                total_creditos := 0;
                total_debitos := 0;

                -- 3. Buscar luego todas las líneas asociadas a los comprobantes
                FOR linea IN SELECT * FROM comprobante_lineas WHERE comprobante_id = comprobante.id LOOP

                    -- 4. Obtener el grupo de la cuenta PUC
                    SELECT grupo INTO grupo_puc FROM pucs WHERE id = linea.pucs_id;

                    -- Validar que el grupo sea 4, 5 o 6
                    IF grupo_puc IN (4, 5, 6) THEN
                        -- 5. Sumar todos los créditos y los débitos
                        total_creditos := total_creditos + COALESCE(linea.credito, 0);
                        total_debitos := total_debitos + COALESCE(linea.debito, 0);
                    ELSE
                        RAISE NOTICE 'Cuenta PUC % ignorada, pertenece al grupo %', linea.pucs_id, grupo_puc;
                    END IF;
                END LOOP;

                -- 6. Crear el saldo proveniente de la sumatoria anterior
                saldo := total_creditos - total_debitos;

                -- 7. Ignorar el registro si todos los totales son cero
                IF total_debitos <> 0 OR total_creditos <> 0 OR saldo <> 0 THEN

                    -- 8. Revisar naturaleza de cuenta PUC para verificar si se suma o se resta
                    SELECT naturaleza INTO nat FROM pucs WHERE id = linea.pucs_id;

                    -- 9. Realizar la sumatoria o resta al saldo dependiendo de la naturaleza de la cuenta PUC
                    IF nat = 'D' THEN
                        saldo := saldo + total_debitos;  -- Si es deudora, se suma
                    ELSE
                        saldo := saldo - total_creditos; -- Si es acreedora, se resta
                    END IF;

                    -- 10. Crear las líneas asociadas al nuevo comprobante solo si hay saldo
                    IF saldo <> 0 THEN
                        INSERT INTO comprobante_lineas (pucs_id, tercero_id, descripcion_linea, debito, credito, comprobante_id, created_at, updated_at, linea)
                        VALUES (linea.pucs_id, linea.tercero_id, 'Línea de cierre anual',
                                CASE WHEN nat = 'D' THEN saldo ELSE 0 END,
                                CASE WHEN nat = 'C' THEN saldo ELSE 0 END,
                                nuevo_comprobante_id, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, linea_num);

                        -- Incrementar el número de línea para la próxima inserción
                        linea_num := linea_num + 1;
                    END IF;

                END IF;

            END LOOP;

            UPDATE cierre_anuales SET estado = 'completado' WHERE id = proceso;

        EXCEPTION
            WHEN OTHERS THEN
                UPDATE cierre_anuales SET estado = 'fallido' WHERE id = proceso;
                RAISE;
        END;
        $$;
        SQL;

        DB::unprepared($sql);
    }

    public function down()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS calcular_saldo_anual");
    }
};
