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

    <div style="text-align: center;">
        <h1>{{ $nombre_compania }}</h1>
        <p>NIT: {{ $nit }}</p>
        <h3>ESTADO DE GANANCIAS Y PERDIDAS POR COMPAÑIA</h3>
        <p>Fecha: {{ $fecha_inicial }} hasta {{ $fecha_final }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th></th>
                <th></th>
                <th style="text-align: center;">INGRESOS</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ingresos as $ingreso)
                <tr>
                    <td>{{ $ingreso->puc }}</td>
                    <td class="description">{{ $ingreso->descripcion }}</td>
                    <td>{{ $ingreso->saldo_anterior ?? 0.0 }}</td>
                    <td>{{ $ingreso->debitos ?? 0.0 }}</td>
                    <td>{{ $ingreso->creditos ?? 0.0 }}</td>
                    <td>{{ $ingreso->saldo_nuevo ?? 0.0 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th></th>
                <th></th>
                <th style="text-align: center;">EGRESOS</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($egresos as $egreso)
                <tr>
                    <td>{{ $egreso->puc }}</td>
                    <td class="description">{{ $egreso->descripcion }}</td>
                    <td>{{ $egreso->saldo_anterior ?? 0.0 }}</td>
                    <td>{{ $egreso->debitos ?? 0.0 }}</td>
                    <td>{{ $egreso->creditos ?? 0.0 }}</td>
                    <td>{{ $egreso->saldo_nuevo ?? 0.0 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
