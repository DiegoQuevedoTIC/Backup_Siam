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
                                <option value="3">Balance Horizontal Comparativo</option>
                                <option value="4">Balance por Tercero</option>
                            </x-filament::input.select>
                        </x-filament::input.wrapper>
                    </div>

                    <div class="mb-6 mt-3">
                        <label for="fecha_inicial"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha Inicial</label>
                        <x-filament::input.wrapper>
                            <x-filament::input name="fecha_inicial" type="date" required />
                        </x-filament::input.wrapper>
                    </div>

                    <div class="mb-6 mt-3">
                        <label for="fecha_final"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha Final</label>
                        <x-filament::input.wrapper>
                            <x-filament::input name="fecha_final" type="date" required />
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
                            <x-filament::input name="nivel" type="number" required />
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
            </ul>
        </div>
        <div
            class="flex-1 mt-6 border border-dashed border-gray-300 rounded-lg flex items-center justify-center relative">

            <embed id="pdf" src="{{ route('generarpdf') }}" type="application/pdf" width="100%" height="600px" class="rounded-lg hidden" />

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

            // Función para verificar si todos los campos están llenos
            function validateForm() {
                var isValid = true;
                //console.log("Validando formulario...");

                // Iterar sobre cada input en el formulario
                form.find('input, select').each(function() {
                    //console.log("Validando campo:", $(this).attr('name')); // o .attr('id')
                    if ($(this).val() === '') {
                        //console.log('Campo vacío:', $(this).attr('name'));
                        isValid = false; // Si algún campo está vacío, cambiar a false
                        buttonGenerate.addClass(
                        'pointer-events-none opacity-70'); // Agregar clases para indicar deshabilitado
                        return false; // Salir del bucle each
                    }
                });

                // Habilitar o deshabilitar el botón según la validez del formulario
                buttonGenerate.prop('disabled', !isValid);

                if (isValid) {
                    buttonGenerate.removeClass(
                    'pointer-events-none opacity-70'); // Eliminar clases si todos los campos son válidos
                }
                //console.log("Botón habilitado:", isValid);
            }

            // Escuchar cambios en los campos del formulario
            form.on('input change', validateForm);

            buttonGenerate.on('click', function() {
                // Mostrar el efecto de carga
                loading.removeClass('hidden');
                empty.addClass('hidden');
                pdf.addClass('hidden');
                console.log('Generating PDF...');

                //console.log(tipo_balance.val());
                switch (tipo_balance.val()) {
                    case '1':
                        console.log('Generando balance general...');
                        break;
                    case '2':
                        console.log('Generando balance horizontal...');
                        break;
                    case '3':
                        console.log('Generando balance horizontal comparativo...');
                        break;
                    case '4':
                        console.log('Generando balance por tercero...');
                        break;
                    default:
                        console.log('Tipo de balance no válido...');
                        break;
                }

                // Simulación de generación del PDF (puedes reemplazarlo con tu lógica real)
                setTimeout(function() {
                    // Ocultar el efecto de carga y mostrar el PDF
                    loading.addClass('hidden');
                    pdf.removeClass('hidden');
                    buttonGenerate.prop('disabled', true);
                }, 1000); // Simula un tiempo de carga de 1 segundo
            });
        });
    </script>
</div>
