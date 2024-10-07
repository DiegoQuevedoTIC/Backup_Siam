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
            width: 100%;
            position: relative;
        }

        .logo {
            float: right;
            width: 100px;
            /* Ajusta el tamaño del logo según sea necesario */
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
            padding: 5px;
            width: 0.1rem;
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
    <div class="header">
        <img src="{{ public_path('images/Icons1.png') }}" alt="logo" class="logo">
    </div>

    <div>
        SIAM ®<br><br>
    </div>

    <div>AUXILIAR A TERCERO POR {{ $nombre_compania }}</div>

    <div>
        <p><strong>FONDEP</strong></p>
        <p>Grupo : {{ $nombre_compania }}</p>
        <p>Rango : {{ $fecha_inicial->format('d/m/Y') }} hasta {{ $fecha_final->format('d/m/Y') }}</p>
        <p>Nit : {{ $nit }}</p>
    </div>

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
            @switch($tipo_balance)
                @case('auxiliar_cuentas')
                    <tr>
                        <th>FECHA</th>
                        <th>DOCUMENTO</th>
                        <th>DETALLE</th>
                        <th>TERCERO</th>
                        <th>DEBITO</th>
                        <th>CREDITO</th>
                        <th>SALDO</th>
                    </tr>
                @break

                @default
                    <tr>
                        <th>FECHA</th>
                        <th>DOCUMENTO</th>
                        <th>DETALLE</th>
                        <th>DEBITO</th>
                        <th>CREDITO</th>
                        <th>SALDO</th>
                    </tr>
            @endswitch

        </thead>
        <tbody>
            @if ($tipo_balance == 'auxiliar_tercero')
                <tr>
                    <td colspan="6" style="text-align: left; background-color: #f2f2f2"><strong>TERCERO:
                            {{ $tercero->tercero . ' ' . $tercero->tercero_nombre . ' ' . $tercero->primer_apellido . ' ' . $tercero->segundo_apellido }}</strong>
                    </td>
                </tr>
            @endif
            @foreach ($cuentas as $puc => $data)
                <tr>
                    @if ($tipo_balance == 'auxiliar_cuentas')
                        <td colspan="5" style="font-weight: bold; text-align: left; background-color: #f2f2f2">CUENTA
                            :
                            {{ $puc }}
                            {{ $data['descripcion'] }}</td>
                    @else
                        <td colspan="4" style="font-weight: bold; text-align: left; background-color: #f2f2f2">CUENTA
                            :
                            {{ $puc }}
                            {{ $data['descripcion'] }}</td>
                    @endif
                    <td colspan="2" style="font-weight: bold; text-align: left; background-color: #f2f2f2">SALDO
                        ANTERIOR: {{ number_format($data['movimientos'][0]->saldo_anterior, 2) }}</td>
                </tr>

                @switch($tipo_balance)
                    @case('auxiliar_cuentas')
                        @foreach ($data['movimientos'] as $movimiento)
                            <tr>
                                <td>{{ $movimiento->fecha }}</td>
                                <td>{{ $movimiento->documento }}</td>
                                <td class="description">{{ $movimiento->n_documento . ' ' . $movimiento->descripcion_linea }}
                                </td>
                                <td>{{ $movimiento->tercero ?? 'N/A' }}</td>
                                <td>{{ number_format($movimiento->debito, 2) }}</td>
                                <td>{{ number_format($movimiento->credito, 2) }}</td>
                                <td>{{ number_format($movimiento->saldo_nuevo, 2) }}</td>
                            </tr>
                        @endforeach
                    @break

                    @default
                        @foreach ($data['movimientos'] as $movimiento)
                            <tr>
                                <td>{{ $movimiento->fecha }}</td>
                                <td>{{ $movimiento->documento }}</td>
                                <td class="description">{{ $movimiento->n_documento . ' ' . $movimiento->descripcion_linea }}
                                </td>
                                <td>{{ number_format($movimiento->debito, 2) }}</td>
                                <td>{{ number_format($movimiento->credito, 2) }}</td>
                                <td>{{ number_format($movimiento->saldo_nuevo, 2) }}</td>
                            </tr>
                        @endforeach
                @endswitch
                <tr style="background-color: #f2f2f2">
                    @if ($tipo_balance == 'auxiliar_cuentas')
                        <td colspan="4" style="font-weight: bold; text-align: left;">TOTAL
                            {{ $data['descripcion'] }}
                        </td>
                    @else
                        <td colspan="3" style="font-weight: bold; text-align: left;">TOTAL
                            {{ $data['descripcion'] }}
                        </td>
                    @endif
                    <td>{{ number_format(array_sum(array_column($data['movimientos'], 'debito')), 2) }}</td>
                    <td>{{ number_format(array_sum(array_column($data['movimientos'], 'credito')), 2) }}</td>
                    <td>{{ number_format(array_sum(array_column($data['movimientos'], 'saldo_nuevo')), 2) }}
                    </td>
                </tr>
                <br>
            @endforeach
        </tbody>
    </table>

    <!-- Sección de firmas -->
    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <tr>
            <td style="text-align: center; width: 33%; padding-top: 20px;">
                <p>_________________________</p>
                <p>{{ $firmantes->representante_legal ?? '' }}</p>
                <p>{{ $firmantes->ci_representante_legal ?? '' }}</p>
                <p></p>
                <p></p>
                <strong>REPRESENTANTE LEGAL</strong>
            </td>
            <td style="text-align: center; width: 33%; padding-top: 20px;">
                <p>_________________________</p>
                <p>{{ $firmantes->revisor_fiscal ?? '' }}</p>
                <p>{{ $firmantes->ci_revisor_fiscal ?? '' }}</p>
                <p>{{ $firmantes->matricula_revisor_fiscal ?? '' }}</p>
                <strong>REVISOR FISCAL</strong>
            </td>
            <td style="text-align: center; width: 33%; padding-top: 20px;">
                <p>_________________________</p>
                <p>{{ $firmantes->contador ?? '' }}</p>
                <p>{{ $firmantes->ci_contador ?? '' }}</p>
                <p>{{ $firmantes->matricula_contador ?? '' }}</p>
                <strong>CONTADOR</strong>
            </td>
        </tr>
    </table>
</body>

</html>
