@php
    use Illuminate\Support\Facades\DB;

    $data = DB::table('plan_desembolsos as pd')->where('pd.solicitud_id', $solicitud)->first();
    $plan_desembolso_composicion = DB::table('cartera_composicion_conceptos as ccp')
        ->where('numero_documento', $data->nro_documento_vto_enc)
        ->join('concepto_descuentos as cd', 'ccp.concepto_descuento', 'cd.codigo_descuento')
        ->select('ccp.*', 'cd.descripcion as nombre_concepto')
        ->orderBy('ccp.prioridad')
        ->get()
        ->toArray();

    $conceptos = DB::table('concepto_descuentos')->select('id', 'codigo_descuento', 'descripcion')->get()->toArray();

    //dd($plan_desembolso_composicion);

    function format_number($number)
    {
        return number_format($number, 2, '.', '');
    }

@endphp
<div x-data="{
    rows: @js($plan_desembolso_composicion),
    conceptos: @js($conceptos),
    selectedConcepto: '',
    showSelect: false,
    selectedMode: '',
    showComposicion: false,
    showForm: false,
    newRow: {
        concepto_descuento: '',
        nombre_concepto: '',
        prioridad: '',
        valor_con_descuento: 0.00,
        porcentaje_descuento: 0.00,
        comodin: '',
    },
    updateForm(concepto) {
        if (concepto) {
            console.log(concepto);
            const conceptoSeleccion = this.conceptos.forEach((c) => {
                if (c.id == concepto) {
                    this.newRow.nombre_concepto = c.descripcion;
                    this.newRow.concepto_descuento = c.codigo_descuento;
                    this.newRow.prioridad = this.rows.length > 0 ? this.rows[this.rows.length - 1].prioridad + 1 : 1;
                }
            });
        }
    },
    addRow() {
        console.log('Adding row:', this.newRow);
        this.rows.push({ ...this.newRow });
        this.newRow = { concepto_descuento: '', nombre_concepto: '', prioridad: '', valor_con_descuento: 0, porcentaje_descuento: 0, comodin: '' };
        console.log('Current rows:', this.rows);
    },
    removeRow(index) {
        console.log('Removing row at index:', index);
        this.rows.splice(index, 1);
        console.log('Current rows after removal:', this.rows);
    }
}">

    <style>
        .descripcion {
            width: 80px;
            overflow: auto;
            word-wrap: break-word;
        }

        .text-header {
            text-align: center;
            font-weight: bold;
            margin-bottom: 5px;
        }

        /* Estilo general para la tabla */
        .table {
            width: 100%;
            /* Asegúrate de que la tabla ocupe todo el ancho disponible */
            border-collapse: collapse;
            /* Colapsar bordes para un mejor aspecto */
        }

        /* Estilo para las celdas de la tabla */
        .table th,
        .table td {
            border: 1px solid #ddd;
            /* Bordes de las celdas */
            padding: 5px;
            width: 0.1rem;
            /* Espaciado interno */
            font-size: 15px;
            /* Ajustar el tamaño de la fuente */
            text-align: right;
            /* Alinear texto a la izquierda */
        }

        /* Estilo para el encabezado de la tabla */
        .table th {
            background-color: #f2f2f2;
            /* Color de fondo para el encabezado */
            font-weight: bold;
            text-align: center;
            /* Negrita para el encabezado */
        }

        /* Ajustar el ancho de columnas específicas si es necesario */
        .table .col-1 {
            width: 30%;
            /* Ancho específico para la primera columna */
        }

        .table .col-2 {
            width: 50%;
            /* Ancho específico para la segunda columna */
        }

        .table .col-3 {
            width: 20%;
            /* Ancho específico para la tercera columna */
        }

        .d-none {
            display: none;
        }

        .bottom-right {
            float: right;
            margin-top: 15px;
        }

        .form-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            /* Space between columns */
        }

        .form-group {
            flex: 1 1 calc(50% - 20px);
            /* Adjust width for two columns */
            min-width: 250px;
            /* Minimum width for responsiveness */
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            /* Include padding in width */
        }
    </style>


    <div>
        <table class="table">
            <tr>
                <td>Fecha de Control:</td>
                <td>{{ now()->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td>Fecha de Impresión:</td>
                <td>{{ now()->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td>Hora de Impresión:</td>
                <td>{{ now()->format('h:i A') }}</td>
            </tr>
            <tr>
                <td>Usuario:</td>
                <td>{{ auth()->user()->name ?? '' }}</td>
            </tr>
        </table>
    </div>

    <br>

    <x-filament::tabs x-data="{ activeTab: 'plan_desembolso' }">
        <x-filament::tabs.item alpine-active="!showComposicion" @click="showComposicion = false">
            Planes de desembolso
        </x-filament::tabs.item>

        <x-filament::tabs.item alpine-active="showComposicion" @click="showComposicion = true">
            Composición de Cuotas
        </x-filament::tabs.item>
    </x-filament::tabs>

    <br>

    <div x-show="!showComposicion">
        <table class="table">
            <thead>
                <tr>
                    <th>Fecha Plan</th>
                    <th>Fecha Inicio</th>
                    <th>Valor Plan</th>
                    <th>Modo Desembolso</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $data->fecha_plan }}</td>
                    <td>{{ $data->fecha_inicio }}</td>
                    <td>{{ format_number($data->valor_plan) }}</td>
                    <td>
                        <div>
                            <strong id="value_modo_desembolso" x-show="!showSelect">
                                @switch($data->modo_desembolso)
                                    @case('E')
                                        EFECTIVO
                                    @break

                                    @case('T')
                                        TRANSFERENCIA
                                    @break

                                    @case('C')
                                        CHEQUE
                                    @break

                                    @default
                                        N/A
                                @endswitch
                            </strong>

                            <div x-show="showSelect">
                                <select class="form-select" x-model="selectedMode"
                                    @change="$wire.updatePlanDesembolso(selectedMode, {{ $solicitud }}), showSelect = !showSelect">
                                    <option disabled selected>Seleccionar modo desembolso</option>
                                    <option value="C">Cheque</option>
                                    <option value="T">Transferencia</option>
                                    <option value="E">Efectivo</option>
                                    <option value="O">Otro</option>
                                </select>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="bottom-right">
            <x-filament::button icon="heroicon-o-pencil-square" @click="showSelect = !showSelect">
                Modificar Modo Desembolso
            </x-filament::button>
        </div>
    </div>

    <div x-show="showComposicion">
        <div>
            <x-filament::button @click="showForm = true" style="float: right; margin-bottom: 15px;"
                icon="heroicon-m-plus" label="Nueva composición">
                Añadir nueva composición
            </x-filament::button>
            <br>
            <x-filament::icon-button
                @click="$wire.addComposicion(newRow, {{ $data->nro_documento_vto_enc }}, addRow(), showForm = false)"
                x-show="showForm" style="float: right; margin-bottom: 15px;" icon="heroicon-o-inbox-arrow-down"
                label="Nueva composición" x-init="console.log(rows)" />
        </div>

        <div class="form-container" x-show="showForm">

            <div class="mb-3">
                <x-filament::input.wrapper>
                    Concepto
                    <x-filament::input.select x-model="selectedConcepto" @change="updateForm(selectedConcepto)">
                        <option value="">Seleccionar</option>
                        <template x-for="(concepto, index) in conceptos" :key="concepto.id">
                            <option :value="concepto.id" x-text="concepto.codigo_descuento"></option>
                        </template>
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </div>

            <div class="mb-3">
                Descripción
                <x-filament::input.wrapper>
                    <x-filament::input type="text" x-model="newRow.nombre_concepto" />
                </x-filament::input.wrapper>
            </div>

            <div class="mb-3">
                Prioridad
                <x-filament::input.wrapper>
                    <x-filament::input type="text" readonly x-model="newRow.prioridad" />
                </x-filament::input.wrapper>
            </div>

            <div class="mb-3">
                Valor
                <x-filament::input.wrapper>
                    <x-filament::input type="numeric" x-model="newRow.valor_con_descuento" />
                </x-filament::input.wrapper>
            </div>

            <div class="mb-3">
                Porcentaje
                <x-filament::input.wrapper>
                    <x-filament::input type="numeric" x-model="newRow.porcentaje_descuento" />
                </x-filament::input.wrapper>
            </div>

            <div class="mb-3">
                Comodin
                <label>
                    <span>SI</span>
                    <x-filament::input.checkbox value="S" x-model="newRow.comodin" />
                    &nbsp;&nbsp;
                    <x-filament::input.checkbox value="N" x-model="newRow.comodin" />
                    <span>
                        NO
                    </span>
                </label>
            </div>

        </div>

        <table class="table" x-show="showComposicion">
            <thead>
                <tr>
                    <th>Concepto Descuento</th>
                    <th>Nombre Concepto</th>
                    <th>Prioridad</th>
                    <th>Valor</th>
                    <th>Porcentaje</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="(row, index) in rows" :key="index">
                    <tr>
                        <td class="descripcion" x-text="row.concepto_descuento"></td>
                        <td class="descripcion" x-text="row.nombre_concepto"></td>
                        <td x-text="row.prioridad"></td>
                        <td x-text="row.valor_con_descuento"></td>
                        <td x-text="row.porcentaje_descuento"></td>
                        <td style="justify-items: center;">
                            <x-filament::icon-button icon="heroicon-m-trash" color="danger"
                                @click="() => { $wire.removeItem(row.concepto_descuento, {{ $data->nro_documento_vto_enc }}); removeRow(index); }" />
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>
