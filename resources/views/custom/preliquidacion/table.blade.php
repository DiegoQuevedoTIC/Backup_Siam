@php
    use Illuminate\Support\Facades\DB;

    $data = DB::table('plan_desembolsos as pd')
        ->join('cuotas_encabezados as ce', 'ce.nro_docto', 'pd.nro_documento_vto_enc')
        ->join('cuotas_detalles as cd', 'cd.nro_docto', 'pd.nro_documento_vto_enc')
        ->where('pd.solicitud_id', $solicitud)
        ->where('pd.tipo_documento_enc', 'PLI')
        ->select(
            'ce.nro_cuota',
            'ce.fecha_vencimiento',
            'ce.vlr_cuota',
            'ce.saldo_capital',
            'cd.vlr_detalle',
            'cd.con_descuento',
        )
        ->orderBy('ce.nro_cuota')
        ->get()
        ->toArray();

    //dd($data);

    $cuotas = [];
    foreach ($data as $row) {
        $cuotas[$row->nro_cuota]['nro_cuota'] = $row->nro_cuota;
        $cuotas[$row->nro_cuota]['fecha_vencimiento'] = $row->fecha_vencimiento;
        $cuotas[$row->nro_cuota]['vlr_detalle'][$row->con_descuento] = $row->vlr_detalle;
        $cuotas[$row->nro_cuota]['vlr_cuota'] = $row->vlr_cuota;
        $cuotas[$row->nro_cuota]['saldo_capital'] = $row->saldo_capital;
    }

    function format_number($number)
    {
        return number_format($number, 2, '.', '');
    }
@endphp
<div>

    <style>
        .descripcion {
            width: 80px;
            overflow: auto;
            word-wrap: break-word;
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


    <table class="table">
        <thead>
            <tr>
                <th>Nro. Cuota</th>
                <th>Fecha Vencimiento</th>
                <th>Capital</th>
                <th>Intereses</th>
                <th>Int Mora</th>
                <th>Seguro Cartera</th>
                <th>Valor Cuota</th>
                <th>Saldo Capital</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cuotas as $cuota)
                <tr>
                    <td>{{ $cuota['nro_cuota'] }}</td>
                    <td>{{ $cuota['fecha_vencimiento'] }}</td>
                    <td>{{ isset($cuota['vlr_detalle'][1]) ? format_number($cuota['vlr_detalle'][1]) : format_number(0) }}
                    </td>
                    <td>{{ isset($cuota['vlr_detalle'][2]) ? format_number($cuota['vlr_detalle'][2]) : format_number(0) }}
                    </td>
                    <td>{{ isset($cuota['vlr_detalle'][3]) ? format_number($cuota['vlr_detalle'][3]) : format_number(0) }}
                    </td>
                    <td>{{ isset($cuota['vlr_detalle'][85]) ? format_number($cuota['vlr_detalle'][85]) : format_number(0) }}
                    </td>
                    <td>{{ format_number($cuota['vlr_cuota']) }}</td>
                    <td>{{ format_number($cuota['saldo_capital']) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
