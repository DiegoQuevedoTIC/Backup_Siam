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
                <th class="h">Total debito</th>
                <th class="h">Total credito</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($row->fecha_comprobante); ?></td>
                    <td><?php echo e($row->n_documento); ?></td>
                    <td><?php echo e($row->descripcion_comprobante); ?></td>
                    <td><?php echo e(number_format($row->total_debito, 2)); ?></td>
                    <td><?php echo e(number_format($row->total_credito, 2)); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</body>

</html>
<?php /**PATH /Users/macbook/Herd/SiamERP/resources/views/excel/desbalance.blade.php ENDPATH**/ ?>