<?php
    use App\Models\Firma;
    $firmantes = Firma::first();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($titulo); ?></title>

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
        <img src="<?php echo e(public_path('images/Icons1.png')); ?>" alt="logo" class="logo">
    </div>

    <div>
        SIAM ®<br><br>
    </div>

    <div>AUXILIAR A TERCERO POR <?php echo e($nombre_compania); ?></div>

    <div>
        <p><strong>FONDEP</strong></p>
        <p>Grupo : <?php echo e($nombre_compania); ?></p>
        <p>Rango : <?php echo e($fecha_inicial->format('d/m/Y')); ?> hasta <?php echo e($fecha_final->format('d/m/Y')); ?></p>
        <p>Nit : <?php echo e($nit); ?></p>
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
                <td><?php echo e(auth()->user()->name ?? ''); ?></td>
            </tr>
        </table>
    </div>


    <table class="table">
        <thead>
            <?php switch($tipo_balance):
                case ('auxiliar_cuentas'): ?>
                    <tr>
                        <th>FECHA</th>
                        <th>DOCUMENTO</th>
                        <th>DETALLE</th>
                        <th>TERCERO</th>
                        <th>DEBITO</th>
                        <th>CREDITO</th>
                        <th>SALDO</th>
                    </tr>
                <?php break; ?>

                <?php default: ?>
                    <tr>
                        <th>FECHA</th>
                        <th>DOCUMENTO</th>
                        <th>DETALLE</th>
                        <th>DEBITO</th>
                        <th>CREDITO</th>
                        <th>SALDO</th>
                    </tr>
            <?php endswitch; ?>

        </thead>
        <tbody>
            <?php if($tipo_balance == 'auxiliar_tercero'): ?>
                <tr>
                    <td colspan="6" style="text-align: left; background-color: #f2f2f2"><strong>TERCERO:
                            <?php echo e($tercero->tercero . ' ' . $tercero->tercero_nombre . ' ' . $tercero->primer_apellido . ' ' . $tercero->segundo_apellido); ?></strong>
                    </td>
                </tr>
            <?php endif; ?>
            <?php $__currentLoopData = $cuentas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $puc => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <?php if($tipo_balance == 'auxiliar_cuentas'): ?>
                        <td colspan="5" style="font-weight: bold; text-align: left; background-color: #f2f2f2">CUENTA
                            :
                            <?php echo e($puc); ?>

                            <?php echo e($data['descripcion']); ?></td>
                    <?php else: ?>
                        <td colspan="4" style="font-weight: bold; text-align: left; background-color: #f2f2f2">CUENTA
                            :
                            <?php echo e($puc); ?>

                            <?php echo e($data['descripcion']); ?></td>
                    <?php endif; ?>
                    <td colspan="2" style="font-weight: bold; text-align: left; background-color: #f2f2f2">SALDO
                        ANTERIOR: <?php echo e(number_format($data['movimientos'][0]->saldo_anterior, 2)); ?></td>
                </tr>

                <?php switch($tipo_balance):
                    case ('auxiliar_cuentas'): ?>
                        <?php $__currentLoopData = $data['movimientos']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $movimiento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($movimiento->fecha); ?></td>
                                <td><?php echo e($movimiento->documento); ?></td>
                                <td class="description"><?php echo e($movimiento->n_documento . ' ' . $movimiento->descripcion_linea); ?>

                                </td>
                                <td><?php echo e($movimiento->tercero ?? 'N/A'); ?></td>
                                <td><?php echo e(number_format($movimiento->debito, 2)); ?></td>
                                <td><?php echo e(number_format($movimiento->credito, 2)); ?></td>
                                <td><?php echo e(number_format($movimiento->saldo_nuevo, 2)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php break; ?>

                    <?php default: ?>
                        <?php $__currentLoopData = $data['movimientos']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $movimiento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($movimiento->fecha); ?></td>
                                <td><?php echo e($movimiento->documento); ?></td>
                                <td class="description"><?php echo e($movimiento->n_documento . ' ' . $movimiento->descripcion_linea); ?>

                                </td>
                                <td><?php echo e(number_format($movimiento->debito, 2)); ?></td>
                                <td><?php echo e(number_format($movimiento->credito, 2)); ?></td>
                                <td><?php echo e(number_format($movimiento->saldo_nuevo, 2)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endswitch; ?>
                <tr style="background-color: #f2f2f2">
                    <?php if($tipo_balance == 'auxiliar_cuentas'): ?>
                        <td colspan="4" style="font-weight: bold; text-align: left;">TOTAL
                            <?php echo e($data['descripcion']); ?>

                        </td>
                    <?php else: ?>
                        <td colspan="3" style="font-weight: bold; text-align: left;">TOTAL
                            <?php echo e($data['descripcion']); ?>

                        </td>
                    <?php endif; ?>
                    <td><?php echo e(number_format(array_sum(array_column($data['movimientos'], 'debito')), 2)); ?></td>
                    <td><?php echo e(number_format(array_sum(array_column($data['movimientos'], 'credito')), 2)); ?></td>
                    <td><?php echo e(number_format(array_sum(array_column($data['movimientos'], 'saldo_nuevo')), 2)); ?>

                    </td>
                </tr>
                <br>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <!-- Sección de firmas -->
    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <tr>
            <td style="text-align: center; width: 33%; padding-top: 20px;">
                <p>_________________________</p>
                <p><?php echo e($firmantes->representante_legal ?? ''); ?></p>
                <p><?php echo e($firmantes->ci_representante_legal ?? ''); ?></p>
                <p></p>
                <p></p>
                <strong>REPRESENTANTE LEGAL</strong>
            </td>
            <td style="text-align: center; width: 33%; padding-top: 20px;">
                <p>_________________________</p>
                <p><?php echo e($firmantes->revisor_fiscal ?? ''); ?></p>
                <p><?php echo e($firmantes->ci_revisor_fiscal ?? ''); ?></p>
                <p><?php echo e($firmantes->matricula_revisor_fiscal ?? ''); ?></p>
                <strong>REVISOR FISCAL</strong>
            </td>
            <td style="text-align: center; width: 33%; padding-top: 20px;">
                <p>_________________________</p>
                <p><?php echo e($firmantes->contador ?? ''); ?></p>
                <p><?php echo e($firmantes->ci_contador ?? ''); ?></p>
                <p><?php echo e($firmantes->matricula_contador ?? ''); ?></p>
                <strong>CONTADOR</strong>
            </td>
        </tr>
    </table>
</body>

</html>
<?php /**PATH /Users/macbook/Herd/SiamERP/resources/views/pdf/auxiliar_tercero.blade.php ENDPATH**/ ?>