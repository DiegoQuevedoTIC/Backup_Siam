<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarteraEncabezadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cartera_encabezados', function (Blueprint $table) {
            $table->id(); // BIGSERIAL PRIMARY KEY
            $table->string('tdocto', 3); // VARCHAR(3) NOT NULL
            $table->bigInteger('nro_docto'); // BIGINT NOT NULL
            $table->bigInteger('cliente'); // BIGINT NOT NULL
            $table->bigInteger('linea'); // BIGINT NOT NULL
            $table->string('estado', 1)->nullable(); // VARCHAR(1)
            $table->smallInteger('periodo_pago')->nullable(); // SMALLINT
            $table->smallInteger('moneda')->nullable(); // SMALLINT
            $table->decimal('interes_cte', 15, 4)->nullable(); // NUMERIC(15, 4)
            $table->decimal('interes_mora', 15, 4)->nullable(); // NUMERIC(15, 4)
            $table->string('tipo_cuota', 1)->nullable(); // VARCHAR(1)
            $table->string('forma_pago_int', 1)->nullable(); // VARCHAR(1)
            $table->string('forma_descuento', 1)->nullable(); // VARCHAR(1)
            $table->string('tipo_tasa', 1)->nullable(); // VARCHAR(1)
            $table->smallInteger('nro_cuotas_gracia')->default(0); // SMALLINT DEFAULT 0 NOT NULL
            $table->string('abonos_extra', 1)->nullable(); // VARCHAR(1)
            $table->string('extra_periodico', 1)->nullable(); // VARCHAR(1)
            $table->smallInteger('periodo_abono')->nullable(); // SMALLINT
            $table->decimal('vlr_abono', 15, 2)->default(0); // NUMERIC(15, 2) DEFAULT 0
            $table->date('fecha_docto')->nullable(); // DATE
            $table->date('fecha_primer_vto')->nullable(); // DATE
            $table->decimal('vlr_docto_vto', 15, 2)->default(0); // NUMERIC(15, 2) DEFAULT 0
            $table->decimal('vlr_ini_cuota', 15, 2)->default(0); // NUMERIC(15, 2) DEFAULT 0
            $table->date('fecha_desembolso')->nullable(); // DATE
            $table->decimal('vlr_desembolsado', 15, 2)->default(0); // NUMERIC(15, 2) DEFAULT 0
            $table->date('fecha_ult_pago_cte')->nullable(); // DATE
            $table->date('fecha_ult_pago_mora')->nullable(); // DATE
            $table->decimal('vlr_saldo_actual', 15, 2)->default(0); // NUMERIC(15, 2) DEFAULT 0
            $table->decimal('vlr_abono_rec', 15, 2)->default(0); // NUMERIC(15, 2) DEFAULT 0
            $table->decimal('vlr_abono_ncr', 15, 2)->default(0); // NUMERIC(15, 2) DEFAULT 0
            $table->decimal('vlr_abono_dpa', 15, 2)->default(0); // NUMERIC(15, 2) DEFAULT 0
            $table->date('fecha_pago_total')->nullable(); // DATE
            $table->smallInteger('nro_cuotas')->nullable(); // SMALLINT
            $table->smallInteger('nro_dias_mora')->nullable(); // SMALLINT
            $table->string('ult_categoria', 1)->nullable(); // VARCHAR(1)
            $table->string('usuario_crea', 12); // VARCHAR(12) NOT NULL
            $table->string('tdocto_cancel', 3)->nullable(); // VARCHAR(3)
            $table->bigInteger('nro_docto_cancel')->nullable(); // BIGINT
            $table->decimal('vlr_reliquidado', 15, 2)->default(0); // NUMERIC(15, 2) DEFAULT 0
            $table->string('nro_docto_anterior', 15)->nullable(); // VARCHAR(15)
            $table->string('categoria_actual', 1)->nullable(); // VARCHAR(1)
            $table->decimal('vlr_congelada', 15, 2)->default(0); // NUMERIC(15, 2) DEFAULT 0
            $table->decimal('vlr_provision_acum', 15, 2)->default(0); // NUMERIC(15, 2) DEFAULT 0 NOT NULL
            $table->decimal('vlr_ult_provision', 15, 2)->nullable(); // NUMERIC(15, 2)
            $table->decimal('vlr_cuentas_orden', 15, 2)->default(0); // NUMERIC(15, 2) DEFAULT 0 NOT NULL
            $table->decimal('vlr_causado', 15, 2)->default(0); // NUMERIC(15, 2) DEFAULT 0 NOT NULL
            $table->string('docto_cargado', 1)->default('N'); // VARCHAR(1) DEFAULT 'N'
            $table->decimal('vlr_provision', 15, 2)->default(0); // NUMERIC(15, 2) DEFAULT 0 NOT NULL
            $table->decimal('vlr_causacion_mes', 15, 2)->default(0); // NUMERIC(15, 2) DEFAULT 0 NOT NULL
            $table->decimal('vlr_cuentas_orden_mes', 15, 2)->default(0); // NUMERIC(15, 2) DEFAULT 0 NOT NULL
            $table->string('tdocto_desembolso', 3)->nullable(); // VARCHAR(3)
            $table->bigInteger('nro_docto_desembolso')->default(0); // BIGINT DEFAULT 0
            $table->decimal('vlr_cuota_tabla', 15, 2)->default(0); // NUMERIC(15, 2) DEFAULT 0
            $table->bigInteger('empresa')->default(0); // BIGINT DEFAULT 0
            $table->smallInteger('nro_cuotas_iniciales')->default(0); // SMALLINT DEFAULT 0
            $table->bigInteger('tercero_asesor')->nullable(); // BIGINT
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cartera_encabezados');
    }
}
