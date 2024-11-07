<?php if (isset($component)) { $__componentOriginal166a02a7c5ef5a9331faf66fa665c256 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal166a02a7c5ef5a9331faf66fa665c256 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-panels::components.page.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-panels::page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>


    <div id="no_print_section">
        <?php if (isset($component)) { $__componentOriginald09a0ea6d62fc9155b01d885c3fdffb3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald09a0ea6d62fc9155b01d885c3fdffb3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-panels::components.form.index','data' => ['wire:submit' => 'save']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-panels::form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:submit' => 'save']); ?>
            <?php echo e($this->form); ?>


            <?php if (isset($component)) { $__componentOriginal742ef35d02cb00943edd9ad8ebf61966 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal742ef35d02cb00943edd9ad8ebf61966 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-panels::components.form.actions','data' => ['actions' => $this->getCachedFormActions(),'fullWidth' => $this->hasFullWidthFormActions()]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-panels::form.actions'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['actions' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($this->getCachedFormActions()),'full-width' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($this->hasFullWidthFormActions())]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal742ef35d02cb00943edd9ad8ebf61966)): ?>
<?php $attributes = $__attributesOriginal742ef35d02cb00943edd9ad8ebf61966; ?>
<?php unset($__attributesOriginal742ef35d02cb00943edd9ad8ebf61966); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal742ef35d02cb00943edd9ad8ebf61966)): ?>
<?php $component = $__componentOriginal742ef35d02cb00943edd9ad8ebf61966; ?>
<?php unset($__componentOriginal742ef35d02cb00943edd9ad8ebf61966); ?>
<?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald09a0ea6d62fc9155b01d885c3fdffb3)): ?>
<?php $attributes = $__attributesOriginald09a0ea6d62fc9155b01d885c3fdffb3; ?>
<?php unset($__attributesOriginald09a0ea6d62fc9155b01d885c3fdffb3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald09a0ea6d62fc9155b01d885c3fdffb3)): ?>
<?php $component = $__componentOriginald09a0ea6d62fc9155b01d885c3fdffb3; ?>
<?php unset($__componentOriginald09a0ea6d62fc9155b01d885c3fdffb3); ?>
<?php endif; ?>

        <!--[if BLOCK]><![endif]--><?php if(count($relationManagers = $this->getRelationManagers())): ?>
            <?php if (isset($component)) { $__componentOriginal66235374c4c55de4d5fac61c84f69826 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal66235374c4c55de4d5fac61c84f69826 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-panels::components.resources.relation-managers','data' => ['activeManager' => $this->activeRelationManager,'managers' => $relationManagers,'ownerRecord' => $record,'pageClass' => static::class]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-panels::resources.relation-managers'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['active-manager' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($this->activeRelationManager),'managers' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($relationManagers),'owner-record' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($record),'page-class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(static::class)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal66235374c4c55de4d5fac61c84f69826)): ?>
<?php $attributes = $__attributesOriginal66235374c4c55de4d5fac61c84f69826; ?>
<?php unset($__attributesOriginal66235374c4c55de4d5fac61c84f69826); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal66235374c4c55de4d5fac61c84f69826)): ?>
<?php $component = $__componentOriginal66235374c4c55de4d5fac61c84f69826; ?>
<?php unset($__componentOriginal66235374c4c55de4d5fac61c84f69826); ?>
<?php endif; ?>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>


    <style>
        .logo {
            width: 100px;
            margin-top: 10px;
        }

        #print_section {
            visibility: hidden;
        }

        .form-container {
            visibility: hidden;
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
            display: none;
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
        }

        /* Estilo para el encabezado de la tabla */
        .table th {
            font-weight: bold;
        }

        .main-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sub-section {
            flex: 1;
            padding: 10px;
            min-height: 80px;
        }

        .description-section {
            padding: 10px;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            .button {
                display: none;
            }

            .table td,
            th {
                color: black;
            }

            .space_cheque {
                border: 1px solid black;
            }

            .space_cheque {
                display: block !important;
            }

            .main-section {
                border: 1px solid black;
                color: black;
            }

            .sub-section {
                border-right: 1px solid black;
                color: black;
            }

            .description-section {
                border: 1px solid black;
                color: black;
            }

            #no_print_section {
                display: none;
            }

            #print_section,
            #print_section * {
                visibility: visible;
                /* Solo muestra el div que queremos imprimir */
            }

            #print_section {
                position: absolute;
                /* Asegura que el div se imprima correctamente */
                left: 0;
                top: 0;
            }

            .form-container {
                visibility: visible;
            }

            .descripcion-completa {
                display: block;
                /* Muestra la descripción completa al imprimir */
            }
        }
    </style>


    <div id="print_section">
        <img style="width: 10%;" src="<?php echo e(asset('images/Icons1.png')); ?>" class="logo" alt="logo" srcset="">
        <br>

        <div>
            <div class="main-section">
                <div class="sub-section">
                    <h2>Número de Comprobante:</h2>
                    <p><?php echo e($this->getRecord()->n_documento); ?></p>
                </div>
                <div class="sub-section">
                    <h2>Fecha de comprobante:</h2>
                    <p><?php echo e($this->getRecord()->fecha_comprobante); ?></p>
                </div>
                <div class="sub-section">
                    <h2>Tipo de Comprobante:</h2>
                    <p><?php echo e($this->getRecord()->tipoDocumentoContable->tipo_documento); ?></p>
                </div>
                <div class="sub-section">
                    <h2>Tercero Comprobante:</h2>
                    <p><?php echo e($this->getRecord()->tercero->tercero_id ?? ''); ?></p>
                </div>
            </div>
            <div class="description-section">
                <h2>Descripción del Comprobante:</h2>
                <p><?php echo e($this->getRecord()->descripcion_comprobante); ?></p>
            </div>
        </div>

        <!--[if BLOCK]><![endif]--><?php if(isset($this->getRecord()->tipo_documento_contables_id) &&
                ($this->getRecord()->tipo_documento_contables_id === 17 ||
                    $this->getRecord()->tipo_documento_contables_id === 28 ||
                    $this->getRecord()->tipo_documento_contables_id === 35)): ?>
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
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <br>

        <!--[if BLOCK]><![endif]--><?php if(count($lineas = $this->getRecord()->comprobanteLinea)): ?>
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
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $lineas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $linea): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($linea->puc->puc ?? ''); ?></td>
                            <td><?php echo e($linea->puc->descripcion ?? ''); ?></td>
                            <td class="description"><?php echo e($linea->tercero->tercero_id ?? ''); ?></td>
                            <td><?php echo e($linea->descripcion_linea ?? ''); ?></td>
                            <td><?php echo e(number_format($linea->debito, 2) ?? 0.0); ?></td>
                            <td><?php echo e(number_format($linea->credito, 2) ?? 0.0); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    <tr class="total">
                        <td colspan="4"><strong>Sumas iguales:</strong></td>
                        <td><strong><?php echo e(number_format($lineas->sum('debito'), 2)); ?></strong></td>
                        <td><strong><?php echo e(number_format($lineas->sum('credito'), 2)); ?></strong></td>
                    </tr>
                </tbody>
            </table>
        <?php else: ?>
            <div class="flex items-center justify-center py-6">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    No hay líneas de cobro disponibles
                </p>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

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


    <script>
        window.addEventListener('print', event => {
            window.print();
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal166a02a7c5ef5a9331faf66fa665c256)): ?>
<?php $attributes = $__attributesOriginal166a02a7c5ef5a9331faf66fa665c256; ?>
<?php unset($__attributesOriginal166a02a7c5ef5a9331faf66fa665c256); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal166a02a7c5ef5a9331faf66fa665c256)): ?>
<?php $component = $__componentOriginal166a02a7c5ef5a9331faf66fa665c256; ?>
<?php unset($__componentOriginal166a02a7c5ef5a9331faf66fa665c256); ?>
<?php endif; ?>
<?php /**PATH /Users/macbook/Herd/SiamERP/resources/views/custom/comprobante/edit-comprobante.blade.php ENDPATH**/ ?>