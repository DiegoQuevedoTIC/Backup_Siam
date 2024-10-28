<x-filament-panels::page>


    <div id="no_print_section">
        <x-filament-panels::form wire:submit="save">
            {{ $this->form }}

            <x-filament-panels::form.actions :actions="$this->getCachedFormActions()" :full-width="$this->hasFullWidthFormActions()" />
        </x-filament-panels::form>

        @if (count($relationManagers = $this->getRelationManagers()))
            <x-filament-panels::resources.relation-managers :active-manager="$this->activeRelationManager" :managers="$relationManagers" :owner-record="$record"
                :page-class="static::class" />
        @endif
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
        <img style="width: 10%;" src="{{ asset('images/Icons1.png') }}" class="logo" alt="logo" srcset="">
        <br>

        <div>
            <div class="main-section">
                <div class="sub-section">
                    <h2>Número de Comprobante:</h2>
                    <p>{{ $this->getRecord()->n_documento }}</p>
                </div>
                <div class="sub-section">
                    <h2>Fecha de comprobante:</h2>
                    <p>{{ $this->getRecord()->fecha_comprobante }}</p>
                </div>
                <div class="sub-section">
                    <h2>Tipo de Comprobante:</h2>
                    <p>{{ $this->getRecord()->tipoDocumentoContable->tipo_documento }}</p>
                </div>
                <div class="sub-section">
                    <h2>Tercero Comprobante:</h2>
                    <p>{{ $this->getRecord()->tercero->tercero_id ?? '' }}</p>
                </div>
            </div>
            <div class="description-section">
                <h2>Descripción del Comprobante:</h2>
                <p>{{ $this->getRecord()->descripcion_comprobante }}</p>
            </div>
        </div>

        @if (isset($this->getRecord()->tipo_documento_contables_id) &&
                ($this->getRecord()->tipo_documento_contables_id === 17 ||
                    $this->getRecord()->tipo_documento_contables_id === 28 ||
                    $this->getRecord()->tipo_documento_contables_id === 35))
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
        @endif

        <br>

        @if (count($lineas = $this->getRecord()->comprobanteLinea))
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
                    @foreach ($lineas as $linea)
                        <tr>
                            <td>{{ $linea->puc->puc ?? '' }}</td>
                            <td>{{ $linea->puc->descripcion ?? '' }}</td>
                            <td class="description">{{ $linea->tercero->tercero_id ?? '' }}</td>
                            <td>{{ $linea->descripcion_linea ?? '' }}</td>
                            <td>{{ number_format($linea->debito, 2) ?? 0.0 }}</td>
                            <td>{{ number_format($linea->credito, 2) ?? 0.0 }}</td>
                        </tr>
                    @endforeach
                    <tr class="total">
                        <td colspan="4"><strong>Sumas iguales:</strong></td>
                        <td><strong>{{ number_format($lineas->sum('debito'), 2) }}</strong></td>
                        <td><strong>{{ number_format($lineas->sum('credito'), 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        @else
            <div class="flex items-center justify-center py-6">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    No hay líneas de cobro disponibles
                </p>
            </div>
        @endif

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


    <script>
        window.addEventListener('print', event => {
            window.print();
        });
    </script>
</x-filament-panels::page>
