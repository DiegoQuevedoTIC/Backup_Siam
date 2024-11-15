@php
    use App\Models\TipoDocumentoContable;

    // Obtener los tipos de documentos
    $tiposComprobante = TipoDocumentoContable::all();
@endphp
<x-filament-panels::page>

    <style>
        .logo {
            width: 100px;
            margin-top: 10px;
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
            font-size: 15px;
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

    <x-filament-panels::form>

        <link rel="stylesheet" href="{{ asset('css/datatable/datatable.tailwind.css') }}">

        <div class="container mx-auto p-6" id="section_table">
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

            <x-filament::loading-indicator class="h-12 w-12 flex m-auto hidden loading" />

            <div class="hidden section-table">
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

            <div class="empty">
                <div class="flex items-center justify-center h-full">
                    <div class="text-center">
                        <x-heroicon-o-document-magnifying-glass />
                        <p class="text-2xl font-bold">No hay datos disponibles</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mx-auto p-6 hidden" id="section_view">
            <div class="flex">
                <x-filament::button type="button" id="back">
                    Regresar
                </x-filament::button>
            </div>
            <br>
            <div>
                <div class="main-section">
                    <div class="sub-section">
                        <h2>Número de Comprobante:</h2>
                        <p id="n_documento"></p>
                    </div>
                    <div class="sub-section">
                        <h2>Fecha de comprobante:</h2>
                        <p id="fecha_comprobante"></p>
                    </div>
                    <div class="sub-section">
                        <h2>Tipo de Comprobante:</h2>
                        <p id="tipo_documento_contables_id"></p>
                    </div>
                    <div class="sub-section">
                        <h2>Tercero Comprobante:</h2>
                        <p id="tercero_id"></p>
                    </div>
                </div>
                <div class="description-section">
                    <h2>Descripción del Comprobante:</h2>
                    <p id="descripcion_comprobante"></p>
                </div>
            </div>

            {{-- @if (isset($this->getRecord()->tipo_documento_contables_id) && ($this->getRecord()->tipo_documento_contables_id === 17 || $this->getRecord()->tipo_documento_contables_id === 28 || $this->getRecord()->tipo_documento_contables_id === 35))
                <div class="space_cheque">
                    <div class="content">
                    </div>
                </div>
                <div id="div_firmas" class="form-container">
                    <div class="main-section">
                        <div class="sub-section">
                            <h2>FECHA: </h2>
                            <P>{{ now()->format('d/m/Y') }}</P>
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
            @endif --}}

            <br>


            <table class="table" id="lineas_table">
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
                <tbody id="body_comprobante">

                </tbody>

            </table>

            {{-- Total debitos y creditos --}}
            <div class="flex justify-between py-6">
                <h2>Total Débito:</h2>
                <h2 id="total_debito_table"></h2>
                <h2>Total Crédito:</h2>
                <h2 id="total_credito_table"></h2>
            </div>

            <div class="flex items-center justify-center py-6 hidden" id="no_lineas">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    No hay líneas de cobro disponibles
                </p>
            </div>

            <br><br>

            <div id="div_firmas" class="form-container">
                <div class="main-section">
                    <div class="sub-section">
                        <h2>PREPARADO</h2>
                        <p>{{ strtoupper(Auth::user()->name) }}</p>
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

                var loading = $('.loading');
                var sectionTable = $('.section-table');
                var empty = $('.empty');
                var cardTable = $('#section_table');
                var cardView = $('#section_view');


                $('#back').on('click', function() {
                    cardTable.removeClass('hidden');
                    cardView.addClass('hidden');
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
                    event.preventDefault();
                    table.draw();
                    loading.removeClass('hidden');
                    empty.addClass('hidden');
                    sectionTable.removeClass('hidden');


                    setTimeout(function() {
                        loading.addClass('hidden');
                    }, 2000);
                });

                // Ver comprobante
                $(document).on('click', '.show_comprobante', function() {
                    const comprobanteId = $(this).attr('data-id');


                    //buscamos la informacion de ese comprobante
                    $.ajax({
                        url: "{{ route('consulta.comprobante') }}",
                        data: {
                            comprobante: comprobanteId
                        },
                        method: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            console.log(response);

                            cardTable.addClass('hidden');
                            cardView.removeClass('hidden');

                            $('#no_lineas').addClass('hidden');
                            $('#lineas_table').removeClass('hidden');

                            const data = response.comprobante;

                            //llenamos los datos en la card
                            $("#n_documento").text(data.n_documento);
                            $("#fecha_comprobante").text(data.fecha_comprobante);
                            $("#tipo_documento_contables_id").text(data
                                .tipo_documento_contables_id);
                            $("#tercero_id").text(data.tercero_id);
                            $("#descripcion_comprobante").text(data.descripcion_comprobante);

                            let lineas = data.comprobante_linea;
                            let totalDebito = 0;
                            let totalCredito = 0;
                            let htmlLineas = '';

                            if (Array.isArray(lineas)) {
                                lineas.forEach(function(linea) {
                                    // Convertir debito y credito a números
                                    let debito = parseFloat(linea.debito) ||
                                        0; // Usa 0 si no se puede convertir
                                    let credito = parseFloat(linea.credito) ||
                                        0; // Usa 0 si no se puede convertir

                                    htmlLineas += `
                                        <tr>
                                            <td>${linea.puc}</td>
                                            <td>${linea.descripcion_puc}</td>
                                            <td>${linea.tercero}</td>
                                            <td>${linea.descripcion_linea}</td>
                                            <td>${debito.toFixed(2)}</td>
                                            <td>${credito.toFixed(2)}</td>
                                        </tr>
                                    `;
                                    totalDebito += debito;
                                    totalCredito += credito;
                                });

                                $("#lineas_table tbody").html(htmlLineas);
                                $("#total_debito_table").text(totalDebito.toFixed(2));
                                $("#total_credito_table").text(totalCredito.toFixed(2));
                            } else {
                                $('#no_lineas').removeClass('hidden');
                                $('#lineas_table').addClass('hidden');
                            }

                        }
                    });
                });
            });
        </script>

    </x-filament-panels::form>
</x-filament-panels::page>
