<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Balance General</title>

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

        thead {
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
            <tr>
                <th>PUC</th>
                <th>DESCRIPCION</th>
                <th>SALDO ANTERIOR</th>
                <th>DEBITOS</th>
                <th>CREDITOS</th>
                <th>NUEVO SALDO</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cuentas as $cuenta)
                <tr>
                    <td>{{ $cuenta->puc }}</td>
                    <td class="description">{{ $cuenta->descripcion }}</td>
                    <td>{{ $cuenta->saldo_anterior }}</td>
                    <td>{{ $cuenta->debitos }}</td>
                    <td>{{ $cuenta->creditos }}</td>
                    <td>{{ $cuenta->saldo_nuevo }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
