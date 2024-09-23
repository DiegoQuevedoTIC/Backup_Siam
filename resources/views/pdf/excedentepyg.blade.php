@php
    use App\Models\Firma;
    $firmantes = Firma::first();
@endphp

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo }}</title>

    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            text-align: right;
            font-size: 12px;
        }

        .descripcion {
            width: 80px;
            overflow: auto;
            word-wrap: break-word;
        }

        .image {
            display: flex;
            flex-direction: column;
            width: 100px;
            float: right;
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
            padding: 8px;
            /* Espaciado interno */
            font-size: 10px;
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

        .total {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 12px;
            text-align: right;
            padding: 8px;
            border-top: 2px solid #ddd;
            border-bottom: 2px solid #ddd;
            border-left: none;
            border-right: none;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .firmas-container {
            display: flex;
            /* Utiliza flexbox para alinear horizontalmente */
            justify-content: space-between;
            /* Espacio entre las firmas */
            margin-top: 20px;
            /* Espacio superior opcional */
        }

        .firma {
            text-align: center;
            /* Centra el texto dentro de cada firma */
            flex: 1;
            /* Permite que cada firma ocupe el mismo espacio */
        }
    </style>
</head>

<body>
    <div>
        SIAM ®<br>
    </div>

    <div style="text-align: center;">
        <h1>{{ $nombre_compania }}</h1>
        <p>NIT: {{ $nit }}</p>
        <h3>ESTADO DE GANANCIAS Y PERDIDAS POR COMPAÑIA</h3>
        <p>Fecha: {{ $fecha_inicial->format('d/m/Y') }} hasta {{ $fecha_final->format('d/m/Y') }}</p>
    </div>


    @if ($tipo_informe === '3')
        <table class="table">
            <thead>
                <tr>
                    <th>PUC</th>
                    <th>Descripción</th>
                    <th>Saldo (Rango 1)</th>
                    <th>Saldo (Rango 2)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($resultados_comparativos as $resultado)
                    <tr>
                        <td>{{ $resultado['puc'] }}</td>
                        <td>{{ $resultado['descripcion'] }}</td>
                        <td>{{ number_format($resultado['saldo_rango_1'], 2) }}</td>
                        <td>{{ number_format($resultado['saldo_rango_2'], 2) }}</td>
                    </tr>
                @endforeach


                <tr class="total">
                    <td colspan="2">Total Ingresos: {{ number_format($total_ingresos, 2) }}</td>
                    <td>Total Egresos: {{ number_format($total_egresos, 2) }}</td>
                    <td>Total Saldo: {{ number_format($total_saldo, 2) }}</td>
                </tr>
            </tbody>
        </table>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th></th>
                    <th style="text-align: center;">INGRESOS</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ingresos as $ingreso)
                    <tr>
                        <td>{{ $ingreso->puc }}</td>
                        <td class="description">{{ $ingreso->descripcion }}</td>
                        <td>{{ number_format($ingreso->saldo, 2) ?? 0.0 }}</td>
                    </tr>
                @endforeach

                <tr class="total">
                    <td colspan="2">Total Ingresos</td>
                    <td>{{ number_format($total_ingresos, 2) ?? 0.0 }}</td>
                </tr>
            </tbody>
        </table>

        <table class="table">
            <thead>
                <tr>
                    <th></th>
                    <th style="text-align: center;">EGRESOS</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($egresos as $egreso)
                    <tr>
                        <td>{{ $egreso->puc }}</td>
                        <td class="description">{{ $egreso->descripcion }}</td>
                        <td>{{ number_format($egreso->saldo, 2) ?? 0.0 }}</td>
                    </tr>
                @endforeach

                <tr class="total">
                    <td colspan="2">Total Egresos</td>
                    <td>{{ number_format($total_egresos, 2) ?? 0.0 }}</td>
                </tr>
            </tbody>
        </table>
    @endif

    <!-- Sección de firmas -->
    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <tr>
            <td style="text-align: center; width: 33%; padding-top: 20px;">
                <p>_________________________</p>
                <p>{{ $firmantes->representante_legal ?? '' }}</p>
                <p>REPRESENTANTE LEGAL</p>
            </td>
            <td style="text-align: center; width: 33%; padding-top: 20px;">
                <p>_________________________</p>
                <p>{{ $firmantes->revisor_fiscal ?? '' }}</p>
                <p>REVISOR FISCAL</p>
            </td>
            <td style="text-align: center; width: 33%; padding-top: 20px;">
                <p>_________________________</p>
                <p>{{ $firmantes->contador ?? '' }}</p>
                <p>CONTADOR</p>
            </td>
        </tr>
    </table>



</body>

</html>
