@php
    use App\Models\Puc;
    use App\Models\Tercero;
@endphp
<x-filament-panels::page>

    <style>
        .logo {
            width: 100px;
            margin-top: 10px;
        }

        .form-container {
            visibility: hidden;
            border: 2px solid black;
            max-width: 800px;
            margin: 0 auto;
            margin-top: 20px;
        }

        .main-section {
            display: flex;
            border-bottom: 2px solid black;
        }

        .sub-section {
            flex: 1;
            padding: 10px;
            border-right: 1px solid black;
            min-height: 80px;
        }

        .sub-section:last-child {
            border-right: none;
        }

        .signature-section {
            padding: 10px;
            min-height: 80px;
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

        @media print {
            body * {
                visibility: hidden;
                /* Oculta todo el contenido */
            }

            .button {
                display: none;
                /* Oculta el botón */
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
        <img style="width: 10%;" src="{{ asset('images/Icons1.png') }}" class="logo" alt="logo" srcset="">
        <br>


        <x-filament::button style="float: right;" onclick="imprimirDiv()" class="button" icon="heroicon-m-printer">
            Imprimir
        </x-filament::button>

        @if ($this->hasInfolist())
            {{ $this->infolist }}
        @else
            {{ $this->form }}
        @endif

        {{-- @if (count($relationManagers = $this->getRelationManagers()))
            <x-filament-panels::resources.relation-managers :active-manager="$this->activeRelationManager" :managers="$relationManagers" :owner-record="$record"
                :page-class="static::class" />
        @endif --}}

        @if (count($lineas = $this->getRecord()->comprobanteLinea))
            <table class="filament-table-repeater w-full text-ri rtl:text-right table-auto mx-4 table mt-4">
                <thead>
                    <tr>
                        <th class="filament-table-repeater-header-cell p-2">
                            <span>
                                Cuenta PUC
                            </span>
                        </th>
                        <th class="filament-table-repeater-header-cell p-2">
                            <span>
                                Tercero Registro
                            </span>
                        </th>
                        <th class="filament-table-repeater-header-cell p-2">
                            <span>
                                Descripcion Linea
                            </span>
                        </th>
                        <th class="filament-table-repeater-header-cell p-2">
                            <span>
                                Debito
                            </span>
                        </th>
                        <th class="filament-table-repeater-header-cell p-2">
                            <span>
                                Credito
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lineas as $linea)
                        <tr>
                            <td class="filament-table-repeater-tbody-cell px-1">
                                <div class="fi-fo-field-wrp">
                                    <label class="sr-only">
                                        Cuenta PUC
                                    </label>

                                    <div class="grid gap-y-2">
                                        <div class="grid gap-y-2">
                                            <div
                                                class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 fi-disabled bg-gray-50 dark:bg-transparent ring-gray-950/10 dark:ring-white/10 fi-fo-text-input overflow-hidden">
                                                <div class="min-w-0 flex-1">
                                                    <input
                                                        class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] sm:text-sm sm:leading-6 dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] bg-white/0 ps-3 pe-3"
                                                        disabled="disabled" type="text"
                                                        value="{{ Puc::where('id', $linea->pucs_id)->first()->puc ?? '' }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="filament-table-repeater-tbody-cell px-1">
                                <div class="fi-fo-field-wrp">
                                    <label class="sr-only">
                                        Tercero Registro
                                    </label>

                                    <div class="grid gap-y-2">
                                        <div class="grid gap-y-2">
                                            <div
                                                class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 fi-disabled bg-gray-50 dark:bg-transparent ring-gray-950/10 dark:ring-white/10 fi-fo-text-input overflow-hidden">
                                                <div class="min-w-0 flex-1">
                                                    <input
                                                        class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] sm:text-sm sm:leading-6 dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] bg-white/0 ps-3 pe-3"
                                                        disabled="disabled" type="text"
                                                        value="{{ Tercero::where('id', $linea->tercero_id)->first()->tercero_id ?? '' }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="filament-table-repeater-tbody-cell px-1">
                                <div class="fi-fo-field-wrp">
                                    <label class="sr-only">
                                        Descripcion Linea
                                    </label>

                                    <div class="grid gap-y-2">
                                        <div class="grid gap-y-2">
                                            <div
                                                class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 fi-disabled bg-gray-50 dark:bg-transparent ring-gray-950/10 dark:ring-white/10 fi-fo-text-input overflow-hidden">
                                                <div class="min-w-0 flex-1">
                                                    <input
                                                        class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] sm:text-sm sm:leading-6 dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] bg-white/0 ps-3 pe-3"
                                                        disabled="disabled" type="text"
                                                        value="{{ $linea->descripcion_linea }}">
                                                </div>
                                            </div>
                                            <div class="descripcion-completa" style="display: none;">
                                                {{ $linea->descripcion_linea }}
                                                <!-- Aquí puedes mostrar la descripción completa -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="filament-table-repeater-tbody-cell px-1">
                                <div class="fi-fo-field-wrp">
                                    <label class="sr-only">
                                        Debito
                                    </label>

                                    <div class="grid gap-y-2">
                                        <div class="grid gap-y-2">
                                            <div
                                                class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 fi-disabled bg-gray-50 dark:bg-transparent ring-gray-950/10 dark:ring-white/10 fi-fo-text-input overflow-hidden">
                                                <div
                                                    class="items-center gap-x-3 ps-3 flex border-e border-gray-200 pe-3 ps-3 dark:border-white/10">
                                                    <span
                                                        class="fi-input-wrp-label whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                        $
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <input style="text-align: right;"
                                                        class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] sm:text-sm sm:leading-6 dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] bg-white/0 ps-3 pe-3"
                                                        disabled="disabled" inputmode="decimal" placeholder="Debito"
                                                        step="0.00" type="number"
                                                        value="{{ $linea->debito ?? '0.00' }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </td>
                            <td class="filament-table-repeater-tbody-cell px-1">
                                <div data-field-wrapper="" class="fi-fo-field-wrp">
                                    <label class="sr-only">
                                        Credito
                                    </label>
                                    <!--[if ENDBLOCK]><![endif]-->

                                    <div class="grid gap-y-2">
                                        <div class="grid gap-y-2">
                                            <div
                                                class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 fi-disabled bg-gray-50 dark:bg-transparent ring-gray-950/10 dark:ring-white/10 fi-fo-text-input overflow-hidden">
                                                <div
                                                    class="items-center gap-x-3 ps-3 flex border-e border-gray-200 pe-3 ps-3 dark:border-white/10">
                                                    <span
                                                        class="fi-input-wrp-label whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                        $
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <input style="text-align: right;"
                                                        class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] sm:text-sm sm:leading-6 dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] bg-white/0 ps-3 pe-3"
                                                        disabled="disabled" inputmode="decimal" placeholder="Credito"
                                                        step="any" type="number"
                                                        value="{{ $linea->credito ?? '0.00' }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        @else
            <div class="flex items-center justify-center py-6">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    No hay líneas de cobro disponibles
                </p>
            </div>
        @endif

        @if (count($lineas))
            <hr>
            <table class="filament-table-repeater w-full text-left rtl:text-right table-auto mx-4">
                <thead>
                    <tr>
                        <th class="filament-table-repeater-header-cell p-2">
                            <span>
                            </span>
                        </th>
                        <th class="filament-table-repeater-header-cell p-2">
                            <span>
                            </span>
                        </th>
                        <th class="filament-table-repeater-header-cell p-2">
                            <span>
                            </span>
                        </th>
                        <th class="filament-table-repeater-header-cell p-2">
                            <span>
                            </span>
                        </th>
                        <th class="filament-table-repeater-header-cell p-2">
                            <span>
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="3" class="filament-table-repeater-tbody-cell px-1 text-center">
                            <div data-field-wrapper="" class="fi-fo-field-wrp">
                                <label class="sr-only">
                                    Descripcion Linea
                                </label>

                                <div class="grid gap-y-2">
                                    <div class="grid gap-y-2">
                                        <div
                                            class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 fi-disabled bg-gray-50 dark:bg-transparent ring-gray-950/10 dark:ring-white/10 fi-fo-text-input overflow-hidden">
                                            <div class="min-w-0 flex-1">
                                                <input
                                                    class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] sm:text-sm sm:leading-6 dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] bg-white/0 ps-3 pe-3"
                                                    disabled="disabled" type="text" value="SUMAS IGUALES">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </td>
                        <td class="filament-table-repeater-tbody-cell px-1">
                            <div class="fi-fo-field-wrp">
                                <label class="sr-only">
                                    Debito
                                </label>

                                <div class="grid gap-y-2">
                                    <div class="grid gap-y-2">
                                        <div
                                            class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 fi-disabled bg-gray-50 dark:bg-transparent ring-gray-950/10 dark:ring-white/10 fi-fo-text-input overflow-hidden">
                                            <div class="min-w-0 flex-1">
                                                <input style="text-align: right;"
                                                    class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] sm:text-sm sm:leading-6 dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] bg-white/0 ps-3 pe-3"
                                                    disabled="disabled" inputmode="decimal" placeholder="Debito"
                                                    step="0.00" type="number"
                                                    value="{{ $linea->sum('debito') ?? '0.00' }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </td>
                        <td class="filament-table-repeater-tbody-cell px-1">
                            <div data-field-wrapper="" class="fi-fo-field-wrp">
                                <label class="sr-only">
                                    Credito
                                </label>
                                <!--[if ENDBLOCK]><![endif]-->

                                <div class="grid gap-y-2">
                                    <div class="grid gap-y-2">
                                        <div
                                            class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 fi-disabled bg-gray-50 dark:bg-transparent ring-gray-950/10 dark:ring-white/10 fi-fo-text-input overflow-hidden">
                                            <div class="min-w-0 flex-1">
                                                <input style="text-align: right;"
                                                    class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] sm:text-sm sm:leading-6 dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] bg-white/0 ps-3 pe-3"
                                                    disabled="disabled" inputmode="decimal" placeholder="Credito"
                                                    step="any" type="number"
                                                    value="{{ $linea->sum('credito') ?? '0.00' }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </td>
                        <td class="flex items-center gap-x-3 py-2 max-w-20">
                        </td>
                    </tr>
                </tbody>
            </table>

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
        @endif
    </div>

    <script>
        function imprimirDiv() {
            window.print();
        }
    </script>
</x-filament-panels::page>
