@php
    use App\Models\TipoDocumentoContable;

    // Obtener los tipos de documentos
    $tiposComprobante = TipoDocumentoContable::all();
@endphp
<x-filament-panels::page>
    <x-filament-panels::form>

        <link rel="stylesheet" href="{{ asset('css/datatable/datatable.tailwind.css') }}">

        <div class="container mx-auto p-6">
            <h1 class="text-2xl font-bold mb-4">Consulta de Comprobantes</h1>

            <div class="flex space-x-4 mb-4">
                <input style="margin-inline: 5px;" type="text" id="searchNroComprobante"
                    placeholder="Buscar por N° Comprobante"
                    class="border border-gray-300 rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white" />

                <select style="margin-inline: 5px;" id="searchTipoComprobante"
                    class="border border-gray-300 rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                    <option value="">Seleccionar Tipo</option>
                    @foreach ($tiposComprobante as $tipo)
                        <option value="{{ $tipo->id }}">{{ $tipo->tipo_documento }}</option>
                    @endforeach
                </select>

                <x-filament::button type="button" id="searchButton">
                    Buscar
                </x-filament::button>
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

        <script src="{{ asset('js/datatable/jquery.min.js') }}"></script>
        <script src="{{ asset('js/datatable/tailwindcss.js') }}"></script>
        <script src="{{ asset('js/datatable/datatable.min.js') }}"></script>
        <script src="{{ asset('js/datatable/datatable.tailwind.js') }}"></script>

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
                        url: "{{ route('consulta.comprobantes') }}",
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

    </x-filament-panels::form>
</x-filament-panels::page>
