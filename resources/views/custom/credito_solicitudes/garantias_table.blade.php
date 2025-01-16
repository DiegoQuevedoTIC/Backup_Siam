@php
    use Illuminate\Support\Facades\DB;

    $data = DB::table('garantias as g')
        ->leftJoin('terceros as t', 'g.tercero_garantia', '=', DB::raw('t.tercero_id::bigint'))
        ->where('g.numero_documento_garantia', $solicitud)
        ->select(
            'g.*',
            DB::raw("CONCAT(t.nombres, ' ', t.primer_apellido, ' ', t.segundo_apellido) AS nombre_completo"),
        )
        ->get()
        ->toArray();

    $garantia_reales = [];
    $garantia_personales = [];
    foreach ($data as $garantia) {
        if ($garantia->tipo_garantia_id == 'R') {
            $garantia_reales[] = $garantia;
        } elseif ($garantia->tipo_garantia_id == 'P') {
            $garantia_personales[] = $garantia;
        }
    }

    //dd($garantia_reales, $garantia_personales);

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

    <br>

    <div>
        <h4 class="text-header">Garantias Reales</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Nro. Matricula</th>
                    <th>Ciudad de registro</th>
                    <th>Valor Avaluo</th>
                    <th>Avaluo Comercial</th>
                    <th>Fecha Avaluo</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    @foreach ($garantia_reales as $row)
                        <td>{{ $row->nro_escr_o_matri }}</td>
                        <td>{{ $row->ciudad_registro }}</td>
                        <td>{{ format_number($row->valor_avaluo) }}</td>
                        <td>{{ format_number($row->valor_avaluo_comercial) }}</td>
                        <td>{{ $row->fecha_avaluo }}</td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>

    <br>

    <div>
        <h4 class="text-header">Garantias Personales</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>ID Garantia</th>
                    <th>Tercero Garantia</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    @foreach ($garantia_personales as $row)
                        <td>{{ $row->tercero_garantia }}</td>
                        <td>{{ $row->nombre_completo }}</td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>

</div>
