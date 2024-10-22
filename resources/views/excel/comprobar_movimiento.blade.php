<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Excel</title>
    <style>
        .h {
            color: black;
        }
    </style>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th class="h">Fecha comprobante</th>
                <th class="h">Nro Documento</th>
                <th class="h">Descripcion comprobante</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr>
                    <td>{{ $row->fecha_comprobante }}</td>
                    <td>{{ $row->n_documento }}</td>
                    <td>{{ $row->descripcion_comprobante }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
