<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
            text-align: left;
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
</head>

<body>
    <div>
        SIAM ®<br>
    </div>

    <div>BALANCE DE PRUEBA POR {{ $nombre_compania }}</div>

    <div>
        <p><strong>FONDEP</strong></p>
        <p>Grupo : {{ $nombre_compania }}</p>
        <p>Rango : {{ $fecha_inicial }} hasta {{ $fecha_final }}</p>
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
                <td>{{ auth()->user()->name }}</td>
            </tr>
        </table>
    </div>

    <table class="table">
        <thead>
            @switch($tipo_balance)
                @case('balance_horizontal')
                    <tr>
                        <th>PUC</th>
                        <th>DESCRIPCION</th>
                        <th>PRIMER RANGO</th>
                        <th>SEGUNDO RANGO</th>
                    </tr>
                @break

                @case('balance_comparativo')
                    <tr>
                        <th>PUC</th>
                        <th>DESCRIPCION</th>
                        <th>SALDO</th>
                        <th>PORCENTAJE</th>
                    </tr>
                @break

                @case('balance_tercero')
                    <tr>
                        <th>PUC</th>
                        <th>DESCRIPCION</th>
                        <th>TERCERO</th>
                        <th>SALDO ANTERIOR</th>
                        <th>DEBITOS</th>
                        <th>CREDITOS</th>
                        <th>NUEVO SALDO</th>
                    </tr>
                @break

                @default
                    <tr>
                        <th>PUC</th>
                        <th>DESCRIPCION</th>
                        <th>SALDO ANTERIOR</th>
                        <th>DEBITOS</th>
                        <th>CREDITOS</th>
                        <th>NUEVO SALDO</th>
                    </tr>
            @endswitch
        </thead>
        <tbody>
            @switch($tipo_balance)
                @case('balance_horizontal')
                    @foreach ($cuentas as $cuenta)
                        <tr>
                            <td>{{ $cuenta->puc }}</td>
                            <td class="description">{{ $cuenta->descripcion ?? 'sin descripción' }}</td>
                            <td>{{ $cuenta->primer_rango ?? 0.0 }}</td>
                            <td>{{ $cuenta->segundo_rango ?? 0.0 }}</td>
                        </tr>
                    @endforeach
                @break

                @case('balance_comparativo')
                    @foreach ($cuentas as $cuenta)
                        <tr>
                            <td>{{ $cuenta->puc }}</td>
                            <td class="description">{{ $cuenta->descripcion ?? 'sin descripción' }}</td>
                            <td>{{ $cuenta->saldo ?? 0.0 }}</td>
                            <td>
                                @if ($total_saldo > 0)
                                    {{ number_format(($cuenta->saldo / $total_saldo) * 100, 2) }}%
                                @else
                                    0%
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    <tr class="total">
                        <td colspan="2"><strong>Total:</strong></td>
                        <td><strong>{{ $total_saldo }}</strong></td>
                        <td>100%</td>
                    </tr>
                @break

                @case('balance_tercero')
                    @foreach ($cuentas as $cuenta)
                        <tr>
                            <td>{{ $cuenta->puc }}</td>
                            <td class="description">{{ $cuenta->descripcion }}</td>
                            <td>{{ $cuenta->tercero ?? '' }}</td>
                            <td>{{ $cuenta->saldo_anterior ?? 0.0 }}</td>
                            <td>{{ $cuenta->debitos ?? 0.0 }}</td>
                            <td>{{ $cuenta->creditos ?? 0.0 }}</td>
                            <td>{{ $cuenta->saldo_nuevo ?? 0.0 }}</td>
                        </tr>
                    @endforeach
                @break

                @default
                    {{--  @dd($cuentas) --}}
                    @foreach ($cuentas as $cuenta)
                        <tr>
                            <td>{{ $cuenta['puc'] }}</td>
                            <td class="description">{{ $cuenta['descripcion'] }}</td>
                            <td>{{ $cuenta['saldo_anterior'] ?? 0.0 }}</td>
                            <td>{{ $cuenta['debitos'] ?? 0.0 }}</td>
                            <td>{{ $cuenta['creditos'] ?? 0.0 }}</td>
                            <td>{{ $cuenta['saldo_nuevo'] ?? 0.0 }}</td>
                        </tr>
                    @endforeach
            @endswitch
        </tbody>
    </table>
</body>

</html>
