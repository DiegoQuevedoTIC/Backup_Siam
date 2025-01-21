@php
    use Illuminate\Support\Facades\DB;

    $capital = DB::table('cuotas_encabezados as ce')
        ->join('cuotas_detalles as cd', function ($join) {
            $join->on('cd.nro_docto', '=', 'ce.nro_docto')->on('cd.nro_cuota', '=', 'ce.nro_cuota');
        })
        ->selectRaw("'Capital' AS concepto, SUM(cd.vlr_detalle) AS total")
        ->where('ce.nro_docto', $nro_documento)
        ->where('ce.tdocto', 'PAG')
        ->where('ce.estado', 'A')
        ->where('cd.con_descuento', 1);

    $interesCorriente = DB::table('cuotas_encabezados as ce')
        ->join('cuotas_detalles as cd', function ($join) {
            $join->on('cd.nro_docto', '=', 'ce.nro_docto')->on('cd.nro_cuota', '=', 'ce.nro_cuota');
        })
        ->selectRaw("'Interés Corriente' AS concepto, SUM(cd.vlr_detalle) AS total")
        ->where('ce.nro_docto', $nro_documento)
        ->where('ce.tdocto', 'PAG')
        ->where('ce.estado', 'A')
        ->where('cd.con_descuento', 2);

    $mora = DB::table('cuotas_encabezados as ce')
        ->join('cuotas_detalles as cd', function ($join) {
            $join->on('cd.nro_docto', '=', 'ce.nro_docto')->on('cd.nro_cuota', '=', 'ce.nro_cuota');
        })
        ->selectRaw("'Mora' AS concepto, SUM(cd.vlr_detalle) AS total")
        ->where('ce.nro_docto', $nro_documento)
        ->where('ce.tdocto', 'PAG')
        ->where('ce.estado', 'A')
        ->where('cd.con_descuento', 3);

    $otros = DB::table('cuotas_encabezados as ce')
        ->join('cuotas_detalles as cd', function ($join) {
            $join->on('cd.nro_docto', '=', 'ce.nro_docto')->on('cd.nro_cuota', '=', 'ce.nro_cuota');
        })
        ->selectRaw("'Otros' AS concepto, SUM(cd.vlr_detalle) AS total")
        ->where('ce.nro_docto', $nro_documento)
        ->where('ce.tdocto', 'PAG')
        ->where('ce.estado', 'A')
        ->where('cd.con_descuento', 85);

    // Unir todos los resultados
    $result = $capital->union($interesCorriente)->union($mora)->union($otros)->get();

    //dd($result);

    function format_number($number)
    {
        return number_format($number, 2, '.', ',');
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
                <th>Capital</th>
                <th>Interés Corriente</th>
                <th>Mora</th>
                <th>Otros</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ format_number($result[1]->total) }}</td>
                <td>{{ format_number($result[2]->total) }}</td>
                <td>{{ format_number($result[3]->total) }}</td>
                <td>{{ format_number($result[0]->total) }}</td>
            </tr>
        </tbody>
    </table>

</div>
