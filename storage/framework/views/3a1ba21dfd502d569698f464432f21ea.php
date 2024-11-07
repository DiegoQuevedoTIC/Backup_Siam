<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Comprobante PDF</title>
    <style>
        .logo {
            width: 100px;
            margin-top: 10px;
            border: none;
        }

        .form-container {
            max-width: 800px;
            margin: 0 auto;
            margin-top: 20px;
        }

        .main-section {
            display: flex;
        }

        .sub-section {
            flex: 1;
            padding: 10px;
            min-height: 80px;
        }

        .sub-section:last-child {
            border-right: none;
        }

        .signature-section {
            padding: 10px;
            min-height: 80px;
            color: black;
        }

        h2 {
            margin: 0;
            font-size: 14px;
            font-weight: bold;
        }

        p {
            margin: 5px 0 0;
            font-size: 12px;
        }

        .space_cheque {
            display: block !important;
            flex-direction: column;
            height: 30vh;
            border: 1px solid black;
            border-radius: 5px;
            margin-top: 20px;
        }

        .content {
            flex: 1;
        }

        .footer {
            padding: 10px;
        }

        /* Estilo general para la tabla */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        /* Estilo para las celdas de la tabla */
        .table th,
        .table td {
            border: 1px solid black;
            padding: 8px;
            font-size: 10px;
            text-align: right;
            color: black;
        }

        /* Estilo para el encabezado de la tabla */
        .table th {
            font-weight: bold;
        }

        .main-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid black;
            color: black;
        }

        .sub-section {
            flex: 1;
            padding: 10px;
            min-height: 80px;
            border-right: 1px solid black;
            color: black;
        }

        .description-section {
            padding: 10px;
            border: 1px solid black;
            color: black;
        }

        .descripcion-completa {
            display: block;
        }
    </style>

<body>
    <div>
        <img style="width: 10%;" src="<?php echo e(public_path('images/Icons1.png')); ?>" class="logo" alt="logo" srcset="">
        <br>


        <div>
            <div class="main-section">
                <div class="sub-section">
                    <h2>Número de Comprobante:</h2>
                    <p><?php echo e($n_documento); ?></p>
                </div>
                <div class="sub-section">
                    <h2>Fecha de comprobante:</h2>
                    <p><?php echo e($fecha_comprobante); ?></p>
                </div>
                <div class="sub-section">
                    <h2>Tipo de Comprobante:</h2>
                    <p><?php echo e($tipoDocumentoContable); ?></p>
                </div>
                <div class="sub-section">
                    <h2>Tercero Comprobante:</h2>
                    <p><?php echo e($tercero ?? ''); ?></p>
                </div>
            </div>
            <div class="description-section">
                <h2>Descripción del Comprobante:</h2>
                <p><?php echo e($descripcion_comprobante); ?></p>
            </div>
        </div>

        <?php if(isset($tipo_documento_contables_id) &&
                ($tipo_documento_contables_id === 17 ||
                    $tipo_documento_contables_id === 28 ||
                    $tipo_documento_contables_id === 35)): ?>
            <div class="space_cheque">
                <div class="content">
                </div>
            </div>
            <div id="div_firmas" class="form-container">
                <div class="main-section">
                    <div class="sub-section">
                        <h2>FECHA: </h2>
                        <P><?php echo e(now()->format('d/m/Y')); ?></P>
                    </div>
                    <div class="sub-section">
                        <h2>TIPO DE GIRO:</h2>
                    </div>
                    <div class="sub-section">
                        <h2>NR CHEQUE:</h2>
                    </div>
                    <div class="sub-section">
                        <h2>MONEDA:</h2>
                        <p>PESOS</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <br>


        <?php if(count($comprobanteLinea) > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Cuenta</th>
                        <th>Nombre de la cuenta</th>
                        <th>Tercero Registro</th>
                        <th>Descripción linea</th>
                        <th>DEBITO</th>
                        <th>CREDITO</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $comprobanteLinea; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $linea): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($linea->puc->puc ?? ''); ?></td>
                            <td><?php echo e($linea->puc->descripcion ?? ''); ?></td>
                            <td class="description"><?php echo e($linea->tercero->tercero_id ?? ''); ?></td>
                            <td><?php echo e($linea->descripcion_linea ?? ''); ?></td>
                            <td><?php echo e(number_format($linea->debito, 2) ?? 0.0); ?></td>
                            <td><?php echo e(number_format($linea->credito, 2) ?? 0.0); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <tr class="total">
                        <td colspan="4"><strong>Sumas iguales:</strong></td>
                        <td><strong><?php echo e(number_format($comprobanteLinea->sum('debito'), 2)); ?></strong></td>
                        <td><strong><?php echo e(number_format($comprobanteLinea->sum('credito'), 2)); ?></strong></td>
                    </tr>
                </tbody>
            </table>
        <?php else: ?>
            <div class="flex items-center justify-center py-6">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    No hay líneas de cobro disponibles
                </p>
            </div>
        <?php endif; ?>

        <br><br>

        <div id="div_firmas" class="form-container">
            <div class="main-section">
                <div class="sub-section">
                    <h2>PREPARADO</h2>
                    <p><?php echo e(strtoupper(Auth::user()->name)); ?></p>
                </div>
                <div class="sub-section">
                    <h2>REVISADO</h2>
                </div>
                <div class="sub-section">
                    <h2>APROBADO</h2>
                </div>
                <div class="sub-section">
                    <h2>CONTABILIZADO</h2>
                </div>
            </div>
            <div class="signature-section">
                <h2>FIRMA Y SELLO</h2>
                <p>C.C. / Nit</p>
            </div>
        </div>
    </div>
</body>

</html>
<?php /**PATH /Users/macbook/Herd/SiamERP/resources/views/pdf/comprobante.blade.php ENDPATH**/ ?>