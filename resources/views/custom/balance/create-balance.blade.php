<div>
    <h2 class="text-center text-xl font-bold mb-4 mt-6">Generar Reporte Balance General</h2>
    <div class="flex gap-8 mt-6">
        <div class="flex-none w-72">
            <ul class="hidden flex-col gap-y-7 md:flex m-5">
                <form id="form_data">
                    <div class="mb-6 mt-3">
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo de
                            Balance</label>

                        <x-filament::input.wrapper>
                            <x-filament::input.select id="tipo_balance" name="tipo_balance" required>
                                <option value="0" selected disabled>Seleccionar tipo de balance</option>
                                <option value="1">Estado Situacion Financiera (Balance General)</option>
                                <option value="2">Balance Horizontal</option>
                                <option value="3">Balance por Tercero</option>
                                <option value="4">Balance Comparativo</option>
                            </x-filament::input.select>
                        </x-filament::input.wrapper>
                    </div>

                    <div class="mb-6 mt-3">
                        <label for="fecha_inicial"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha Inicial</label>
                        <x-filament::input.wrapper>
                            <x-filament::input id="fecha_inicial" name="fecha_inicial" type="date" required />
                        </x-filament::input.wrapper>
                    </div>

                    <div class="mb-6 mt-3">
                        <label for="fecha_final"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha Final</label>
                        <x-filament::input.wrapper>
                            <x-filament::input id="fecha_final" name="fecha_final" type="date" required />
                        </x-filament::input.wrapper>
                    </div>

                    <div class="mb-6 mt-3">
                        <label for="is_13_month"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">¿Incluye Mes
                            Trece?</label>
                        <label class="p-5">
                            <x-filament::input.checkbox id="is_13_month" name="is_13_month" />
                        </label>
                    </div>


                    <div class="mb-6 mt-3">
                        <label for="nivel"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nivel</label>
                        <x-filament::input.wrapper>
                            <x-filament::input id="nivel" name="nivel" type="number" required />
                        </x-filament::input.wrapper>
                    </div>

                </form>

                <button style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);"
                    class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 pointer-events-none opacity-70 rounded-lg fi-color-custom fi-btn-color-primary fi-size-sm fi-btn-size-sm gap-1 px-2.5 py-1.5 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50"
                    disabled type="button" wire:loading.attr="disabled" id="generar">
                    <span class="fi-btn-label">
                        Generar reporte
                    </span>
                </button>


                {{-- <button style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);"
                    class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50"
                    type="button" id="export">
                    <span class="fi-btn-label">
                        Exportar
                    </span>
                </button> --}}
            </ul>
        </div>
        <div
            class="flex-1 mt-6 border border-dashed border-gray-300 rounded-lg flex items-center justify-center relative">

            <embed id="pdf" type="application/pdf" width="100%" height="600px" class="rounded-lg hidden" />

            <div id="empty">
                <x-filament::icon icon="heroicon-m-document-text" class="h-20 w-20 text-gray-500 dark:text-gray-400" />
            </div>

            <x-filament::loading-indicator id="loading" class="h-20 w-20 hidden" />
        </div>
    </div>


    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            console.log("ready!");

            // Configurar el token CSRF para las solicitudes AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var form = $('#form_data');
            var buttonGenerate = $('#generar');
            var pdf = $('#pdf');
            var empty = $('#empty');
            var loading = $('#loading');
            var tipo_balance = $('#tipo_balance');
            var fecha_inicial = $('#fecha_inicial');
            var fecha_final = $('#fecha_final');
            var is_13_month = $('#is_13_month');
            var nivel = $('#nivel');
            var buttonExport = $('#export');

            // Función para verificar si todos los campos están llenos
            function validateForm() {
                var isValid = true;

                form.find('input, select').each(function() {
                    if ($(this).val() === '') {
                        isValid = false;
                        buttonGenerate.addClass('pointer-events-none opacity-70');
                        return false;
                    }
                });

                buttonGenerate.prop('disabled', !isValid);
                if (isValid) {
                    buttonGenerate.removeClass('pointer-events-none opacity-70');
                }
            }

            // Escuchar cambios en los campos del formulario
            form.on('input change', validateForm);

            buttonGenerate.on('click', function() {
                // Mostrar el efecto de carga
                loading.removeClass('hidden');
                empty.addClass('hidden');
                pdf.addClass('hidden');
                console.log('Generating PDF...');

                // Preparar los datos para enviar
                var data = {
                    tipo_balance: tipo_balance.val(),
                    fecha_inicial: fecha_inicial.val(),
                    fecha_final: fecha_final.val(),
                    is_13_month: is_13_month.is(':checked'),
                    nivel: nivel.val()
                };

                var url = "{{ route('generarpdf') }}";

                //console.log(data.tipo_balance);

                switch (data.tipo_balance) {
                    case '2':
                        url = "{{ route('generar.balance.horizontal') }}"
                        generateReport(url);
                        break;
                    case '3':
                        url = "{{ route('generar.balance.tercero') }}"
                        generateReport(url);
                        break;
                    case '4':
                        url = "{{ route('generar.balance.comparativo') }}"
                        generateReport(url);
                        break;
                    default:
                        generateReport(url);
                        return;
                }

                function generateReport(url) {
                    //console.log(url);

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: data,
                        success: function(response) {
                            //console.log('PDF generado con éxito:', response);
                            pdf.attr('src', 'data:application/pdf;base64,' + response.pdf);
                            loading.addClass('hidden');
                            pdf.removeClass('hidden');
                            buttonGenerate.prop('disabled', true);
                            //buttonExport.removeClass('hidden');

                            new FilamentNotification()
                                .title('PDF Generado exitosamente.')
                                .success()
                                .send();

                            //exportExcel();
                        },
                        error: function(xhr, status, error) {
                            //console.error('Error al generar el PDF:', error);
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                new FilamentNotification()
                                    .title(xhr.responseJSON.message)
                                    .danger()
                                    .send();
                            } else {
                                new FilamentNotification()
                                    .title('Ocurrió un error inesperado.')
                                    .danger()
                                    .send();
                            }
                            loading.addClass('hidden');
                            empty.removeClass('hidden'); // Mostrar mensaje de error
                        }
                    });
                }

                buttonExport.on('click', function(e) {
                    e.preventDefault();
                    //exportExcel();
                });

                function exportExcel() {
                    console.log('Exportando a Excel...');

                    var data = {
                        tipo_balance: tipo_balance.val(),
                        fecha_inicial: fecha_inicial.val(),
                        fecha_final: fecha_final.val(),
                    };

                    $.ajax({
                        url: "{{ route('export') }}",
                        type: 'POST',
                        data: data,
                        xhrFields: {
                            responseType: 'blob' // Esto es importante para manejar el archivo
                        },
                        success: function(response, status, xhr) {
                            // Crear un enlace para descargar el archivo
                            var filename =
                                ""; // Aquí puedes obtener el nombre del archivo desde el encabezado de la respuesta
                            var disposition = xhr.getResponseHeader('Content-Disposition');
                            if (disposition && disposition.indexOf('attachment') !== -1) {
                                var matches = /filename[^;=\n]*=((['"]).*?\2|([^;\n]*))/;
                                var parts = matches.exec(disposition);
                                if (parts != null && parts[3]) {
                                    filename = parts[3];
                                }
                            }

                            // Crear un objeto Blob y un enlace para descargar
                            var blob = new Blob([response], {
                                type: xhr.getResponseHeader('Content-Type')
                            });
                            var link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob);
                            link.download = filename || 'export.xlsx';
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);

                            new FilamentNotification()
                                .title('Excel exportado exitosamente.')
                                .success()
                                .send();
                        },
                        error: function(xhr, status, error) {
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                new FilamentNotification()
                                    .title(xhr.responseJSON.message)
                                    .danger()
                                    .send();
                            } else {
                                new FilamentNotification()
                                    .title('Ocurrió un error inesperado.')
                                    .danger()
                                    .send();
                            }
                        }
                    });
                }
            });
        });
    </script>
</div>
