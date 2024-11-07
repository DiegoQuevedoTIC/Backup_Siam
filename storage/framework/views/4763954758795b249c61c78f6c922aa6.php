<?php
    use App\Models\TipoDocumentoContable;

    // Obtener los tipos de documentos
    $tiposComprobante = TipoDocumentoContable::all();
?>
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
    <?php if (isset($component)) { $__componentOriginald09a0ea6d62fc9155b01d885c3fdffb3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald09a0ea6d62fc9155b01d885c3fdffb3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-panels::components.form.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-panels::form'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>

        <link rel="stylesheet" href="<?php echo e(asset('css/datatable/datatable.tailwind.css')); ?>">

        <div class="container mx-auto p-6">
            <h1 class="text-2xl font-bold mb-4">Consulta de Comprobantes</h1>

            <div class="flex space-x-4 mb-4">
                <input style="margin-inline: 5px;" type="text" id="searchNroComprobante"
                    placeholder="Buscar por N° Comprobante"
                    class="border border-gray-300 rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white" />

                <select style="margin-inline: 5px;" id="searchTipoComprobante"
                    class="border border-gray-300 rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                    <option value="">Seleccionar Tipo</option>
                    <?php $__currentLoopData = $tiposComprobante; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($tipo->id); ?>"><?php echo e($tipo->tipo_documento); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>

                <?php if (isset($component)) { $__componentOriginal6330f08526bbb3ce2a0da37da512a11f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.button.index','data' => ['type' => 'button','id' => 'searchButton']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','id' => 'searchButton']); ?>
                    Buscar
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6330f08526bbb3ce2a0da37da512a11f)): ?>
<?php $attributes = $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f; ?>
<?php unset($__attributesOriginal6330f08526bbb3ce2a0da37da512a11f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6330f08526bbb3ce2a0da37da512a11f)): ?>
<?php $component = $__componentOriginal6330f08526bbb3ce2a0da37da512a11f; ?>
<?php unset($__componentOriginal6330f08526bbb3ce2a0da37da512a11f); ?>
<?php endif; ?>
            </div>

            <br><br>

            <table id="comprobantes"
                class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md dark:bg-gray-800 dark:border-gray-600">
                <thead>
                    <tr
                        class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal dark:bg-gray-700 dark:text-gray-300">
                        <th class="py-3 px-6 text-left">Fecha</th>
                        <th class="py-3 px-6 text-left">N° Documento</th>
                        <th class="py-3 px-6 text-left">Descripción</th>
                        <th class="py-3 px-6 text-left">Total Débito</th>
                        <th class="py-3 px-6 text-left">Total Crédito</th>
                        <th class="py-3 px-6 text-left">Acción</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light dark:text-gray-300">
                    <!-- Los datos se llenarán aquí -->
                </tbody>
            </table>
        </div>

        <script src="<?php echo e(asset('js/datatable/jquery.min.js')); ?>"></script>
        <script src="<?php echo e(asset('js/datatable/tailwindcss.js')); ?>"></script>
        <script src="<?php echo e(asset('js/datatable/datatable.min.js')); ?>"></script>
        <script src="<?php echo e(asset('js/datatable/datatable.tailwind.js')); ?>"></script>

        <script>
            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var table = $('#comprobantes').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "<?php echo e(route('consulta.comprobantes')); ?>",
                        data: function(d) {
                            d.nro_comprobante = $('#searchNroComprobante').val();
                            d.tipo_comprobante = $('#searchTipoComprobante').val();
                        }
                    },
                    columns: [{
                            data: 'fecha_comprobante'
                        },
                        {
                            data: 'n_documento'
                        },
                        {
                            data: 'descripcion_comprobante'
                        },
                        {
                            data: 'total_debito'
                        },
                        {
                            data: 'total_credito'
                        },
                        {
                            data: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ],
                    responsive: true,
                    language: {
                        "sProcessing": "Procesando...",
                        "sLengthMenu": "Mostrar _MENU_ registros",
                        "sZeroRecords": "No se encontraron resultados",
                        "sInfo": "Mostrando de _START_ a _END_ de _TOTAL_ registros",
                        "sInfoEmpty": "Mostrando 0 a 0 de 0 registros",
                        "sInfoFiltered": "(filtrado de _MAX_ registros en total)",
                        "sSearch": "Buscar:",
                        "oPaginate": {
                            "sFirst": "Primero",
                            "sLast": "Último",
                            "sNext": "Siguiente",
                            "sPrevious": "Anterior"
                        }
                    }
                });

                // Deshabilitar el botón de búsqueda inicialmente
                $('#searchButton').prop('disabled', true).addClass('pointer-events-none opacity-70');

                // Función para habilitar/deshabilitar el botón de búsqueda
                function toggleSearchButton() {
                    const nroComprobante = $('#searchNroComprobante').val();
                    const tipoComprobante = $('#searchTipoComprobante').val();
                    if (nroComprobante.length > 1 || tipoComprobante.length > 1) {
                        $('#searchButton').prop('disabled', false).removeClass('pointer-events-none opacity-70');
                    } else {
                        $('#searchButton').prop('disabled', true).addClass('pointer-events-none opacity-70');
                    }
                }

                // Eventos para los inputs y el select
                $('#searchNroComprobante, #searchTipoComprobante').on('input change', toggleSearchButton);

                // Evento para el botón de búsqueda
                $('#searchButton').click(function(event) {
                    event.preventDefault(); // Previene el refresco de la página
                    table.draw(); // Redibuja la tabla con los nuevos parámetros
                });
            });
        </script>

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
<?php /**PATH /Users/macbook/Herd/SiamERP/resources/views/custom/consultas/consulta-comprobante.blade.php ENDPATH**/ ?>