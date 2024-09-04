@php
    //dd($nombre_compania);
@endphp

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

        <div class="image">
            {{-- <img src="http://localhost:8001/images/LogoSiam.png" alt="logo"> --}}
        </div>
    </div>

    <div>BALANCE DE PRUEBA POR {{ $nombre_compania }}</div>

    <div>
        <p><strong>FONDEP</strong></p>
        <p>Grupo : GRUPO FINANCIERO - FONDEP</p>
        <p>Rango : 01/03/2024 hasta 22/03/2024</p>
        <p>Nit : 8.000.903.753</p>
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
                <td></td>
            </tr>
            <tr>
                <td>Usuario:</td>
                <td>{{ auth()->user()->name }}</td>
            </tr>
            <tr>
                <td>Estación:</td>
                <td></td>
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
            <tr>
                <td>1</td>
                <td>A C T I V O</td>
                <td>10,417,306,823.72</td>
                <td>733,391,732.00</td>
                <td>891,482,685.44</td>
                <td>10,259,215,870.28</td>
            </tr>
            <tr>
                <td>11</td>
                <td>EFECTIVO Y EQUIVALENTE AL EFECTIVO</td>
                <td>585,917,388.10</td>
                <td>337,224,868.00</td>
                <td>618,552,950.44</td>
                <td>304,589,305.66</td>
            </tr>
            <tr>
                <td>1105</td>
                <td>CAJA</td>
                <td>1,950,000.00</td>
                <td>30,160,081.00</td>
                <td>0.00</td>
                <td>32,110,081.00</td>
            </tr>
            <tr>
                <td>110505</td>
                <td>CAJA GENERAL</td>
                <td>0.00</td>
                <td>30,160,081.00</td>
                <td>0.00</td>
                <td>30,160,081.00</td>
            </tr>
            <tr>
                <td>11050501</td>
                <td>caja general</td>
                <td>0.00</td>
                <td>30,160,081.00</td>
                <td>0.00</td>
                <td>30,160,081.00</td>
            </tr>
            <tr>
                <td>110510</td>
                <td>CAJA MENOR</td>
                <td>1,950,000.00</td>
                <td>0.00</td>
                <td>0.00</td>
                <td>1,950,000.00</td>
            </tr>
            <tr>
                <td>11051001</td>
                <td>caja menor</td>
                <td>1,950,000.00</td>
                <td>0.00</td>
                <td>0.00</td>
                <td>1,950,000.00</td>
            </tr>
            <!-- Agrega más filas según sea necesario -->
        </tbody>
    </table>
</body>

</html>
