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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        thead,
        .total {
            background-color: #c0c0c0;
        }

        th,
        td {
            text-align: right;
            padding: 0.3rem;
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
        <table>
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

    <table>
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
                    @foreach ($cuentas as $cuenta)
                        <tr>
                            <td>{{ $cuenta->puc }}</td>
                            <td class="description">{{ $cuenta->descripcion }}</td>
                            <td>{{ $cuenta->saldo_anterior ?? 0.0 }}</td>
                            <td>{{ $cuenta->debitos ?? 0.0 }}</td>
                            <td>{{ $cuenta->creditos ?? 0.0 }}</td>
                            <td>{{ $cuenta->saldo_nuevo ?? 0.0 }}</td>
                        </tr>
                    @endforeach
            @endswitch
        </tbody>
    </table>
</body>

</html>
