<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo e($data['titulo']); ?></title>

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

        .image {
            display: flex;
            flex-direction: column;
            width: 100px;
            float: right;
        }

        .descripcion {
            width: 80px;
            overflow: auto;
            word-wrap: break-word;
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
    </style>
</head>

<body>

    <div class="header">
        <img src="<?php echo e(public_path('images/Icons1.png')); ?>" alt="logo" class="logo">
    </div>

    <div>
        SIAM ®<br>
    </div>

    <div>BALANCE DE PRUEBA POR <?php echo e($data['nombre_compania']); ?></div>

    <div>
        <p><strong>FONDEP</strong></p>
        <p>Grupo : <?php echo e($data['nombre_compania']); ?></p>
        <p>Rango : <?php echo e($data['fecha_inicial']); ?> hasta <?php echo e($data['fecha_final']); ?></p>
        <p>Nit : <?php echo e($data['nit']); ?></p>
    </div>

    <div>
        <table class="table">
            <tr>
                <td>Fecha de Control:</td>
                <td><?php echo e(now()->format('d/m/Y')); ?></td>
            </tr>
            <tr>
                <td>Fecha de Impresión:</td>
                <td><?php echo e(now()->format('d/m/Y')); ?></td>
            </tr>
            <tr>
                <td>Hora de Impresión:</td>
                <td><?php echo e(now()->format('h:i A')); ?></td>
            </tr>
            <tr>
                <td>Usuario:</td>
                <td><?php echo e(auth()->user()->name); ?></td>
            </tr>
        </table>
    </div>

    <table class="table">
        <thead>
            <?php switch($data['tipo_balance']):
                case ('balance_horizontal'): ?>
                    <tr>
                        <th>PUC</th>
                        <th>DESCRIPCION</th>
                        <th>PRIMER RANGO</th>
                        <th>SEGUNDO RANGO</th>
                    </tr>
                <?php break; ?>

                <?php case ('balance_comparativo'): ?>
                    <tr>
                        <th>PUC</th>
                        <th>DESCRIPCION</th>
                        <th>SALDO</th>
                        <th>PORCENTAJE</th>
                    </tr>
                <?php break; ?>

                <?php case ('balance_tercero'): ?>
                    <tr>
                        <th>PUC</th>
                        <th>DESCRIPCION</th>
                        <th>TERCERO</th>
                        <th>SALDO ANTERIOR</th>
                        <th>DEBITOS</th>
                        <th>CREDITOS</th>
                        <th>NUEVO SALDO</th>
                    </tr>
                <?php break; ?>

                <?php default: ?>
                    <tr>
                        <th>PUC</th>
                        <th>DESCRIPCION</th>
                        <th>SALDO ANTERIOR</th>
                        <th>DEBITOS</th>
                        <th>CREDITOS</th>
                        <th>NUEVO SALDO</th>
                    </tr>
            <?php endswitch; ?>
        </thead>
        <tbody>
            <?php switch($data['tipo_balance']):
                case ('balance_horizontal'): ?>
                    <?php $__currentLoopData = $data['cuentas']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cuenta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($cuenta->puc); ?></td>
                            <td class="description"><?php echo e($cuenta->descripcion ?? 'sin descripción'); ?></td>
                            <td><?php echo e(number_format($cuenta->primer_rango, 2) ?? 0.0); ?></td>
                            <td><?php echo e(number_format($cuenta->segundo_rango, 2) ?? 0.0); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php break; ?>

                <?php case ('balance_comparativo'): ?>
                    <?php $__currentLoopData = $data['cuentas']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cuenta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($cuenta->puc); ?></td>
                            <td class="description"><?php echo e($cuenta->descripcion ?? 'sin descripción'); ?></td>
                            <td><?php echo e(number_format($cuenta->saldo, 2) ?? 0.0); ?></td>
                            <td>
                                <?php if($data['total_saldo'] > 0): ?>
                                    <?php echo e(number_format(($cuenta->saldo / $data['total_saldo']) * 100, 2)); ?>%
                                <?php else: ?>
                                    0%
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <tr class="total">
                        <td colspan="2"><strong>Total:</strong></td>
                        <td><strong><?php echo e($data['total_saldo']); ?></strong></td>
                        <td>100%</td>
                    </tr>
                <?php break; ?>

                <?php case ('balance_tercero'): ?>
                    <?php $__currentLoopData = $data['cuentas']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cuenta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($cuenta->cuenta_puc); ?></td>
                            <td class="description"><?php echo e($cuenta->descripcion); ?></td>
                            <td><?php echo e($cuenta->tercero ?? ''); ?></td>
                            <td><?php echo e(number_format($cuenta->saldo_anterior, 2) ?? 0.0); ?></td>
                            <td><?php echo e(number_format($cuenta->total_debito, 2) ?? 0.0); ?></td>
                            <td><?php echo e(number_format($cuenta->total_credito, 2) ?? 0.0); ?></td>
                            <td><?php echo e(number_format($cuenta->saldo_nuevo, 2) ?? 0.0); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php break; ?>

                <?php default: ?>
                    
                    <?php $__currentLoopData = $data['cuentas']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cuenta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($cuenta['cuenta_puc']); ?></td>
                            <td class="description"><?php echo e($cuenta['descripcion']); ?></td>
                            <td><?php echo e(number_format($cuenta['saldo_anterior'], 2) ?? 0.0); ?></td>
                            <td><?php echo e(number_format($cuenta['total_debito'], 2) ?? 0.0); ?></td>
                            <td><?php echo e(number_format($cuenta['total_credito'], 2) ?? 0.0); ?></td>
                            <td><?php echo e(number_format($cuenta['saldo_nuevo'], 2) ?? 0.0); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <tr class="total">
                        <td colspan="2"><strong>Total Balance General:</strong></td>
                        <td><strong><?php echo e(number_format($data['total_saldo_anteriores'], 2)); ?></strong></td>
                        <td><strong><?php echo e(number_format($data['total_debitos'], 2)); ?></strong></td>
                        <td><strong><?php echo e(number_format($data['total_creditos'], 2)); ?></strong></td>
                        <td><strong><?php echo e(number_format($data['total_saldo_nuevo'], 2)); ?></strong></td>
                    </tr>

            <?php endswitch; ?>
        </tbody>
    </table>
</body>

</html>
<?php /**PATH /Users/macbook/Herd/SiamERP/resources/views/excel/balance.blade.php ENDPATH**/ ?>