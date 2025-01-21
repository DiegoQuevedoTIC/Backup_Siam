@php
    use Illuminate\Support\Facades\DB;
    $conceptos = DB::table('concepto_descuentos')
        ->select('id', 'codigo_descuento', 'descripcion', 'cuenta_contable')
        ->get()
        ->toArray();

    $obligaciones = $this->cliente->obligaciones ?? [];

@endphp

<x-filament-panels::page>
    <x-filament-panels::form>
        {{ $this->form }}

        <hr>

        @if ($this->show)
            <div x-data="data()">

                {{-- Informacion del cliente --}}
                <div class="tabs">
                    <x-filament::tabs label="Content tabs">
                        <x-filament::tabs.item alpine-active="creditos"
                            @click="creditos = true, obligaciones = false, otros_conceptos = false">
                            Creditos vigentes
                        </x-filament::tabs.item>

                        <x-filament::tabs.item alpine-active="obligaciones"
                            @click="obligaciones = true, creditos = false, otros_conceptos = false">
                            Obligaciones servicios
                        </x-filament::tabs.item>

                        <x-filament::tabs.item alpine-active="otros_conceptos"
                            @click="otros_conceptos = true, creditos = false, obligaciones = false">
                            Otros conceptos
                        </x-filament::tabs.item>
                    </x-filament::tabs>
                </div>


                {{-- Creditos vigentes --}}
                <div x-show="creditos">
                    <div
                        class="overflow-hidden mt-6 w-full overflow-x-auto rounded-md border border-neutral-300 dark:border-neutral-700">
                        <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-300">
                            <thead
                                class="border-b border-neutral-300 bg-neutral-50 text-sm text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                                <tr>
                                    <th scope="col" class="p-4">Nro docto</th>
                                    <th scope="col" class="p-4">Nro cuotas</th>
                                    <th scope="col" class="p-4">Linea credito</th>
                                    <th scope="col" class="p-4">Nro dias mora</th>
                                    <th scope="col" class="p-4">Saldo actual</th>
                                    <th scope="col" class="p-4">Fecha desembolso</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-300 dark:divide-neutral-700">
                                @forelse ($this->cliente->carteraEncabezados->where('estado', 'A')->where('tdocto', 'PAG') as $credito)
                                    <tr>
                                        <td class="p-4">{{ $credito->nro_docto }}</td>
                                        <td class="p-4">{{ $credito->nro_cuotas }}</td>
                                        <td class="p-4">{{ $credito->lineaCredito->descripcion }}</td>
                                        <td class="p-4">{{ $credito->nro_dias_mora }}</td>
                                        <td class="p-4">{{ $credito->vlr_saldo_actual }}</td>
                                        <td class="p-4">{{ $credito->created_at }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center p-8">No hay creditos vigentes</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Obligaciones servicios --}}
                <div x-show="obligaciones">
                    <div
                        class="overflow-hidden w-full mt-6 overflow-x-auto rounded-md border border-neutral-300 dark:border-neutral-700">
                        <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-300">
                            <thead
                                class="border-b border-neutral-300 bg-neutral-50 text-sm text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                                <tr>
                                    <th scope="col" class="p-4">Fecha vencimiento</th>
                                    <th scope="col" class="p-4">Con descuento</th>
                                    <th scope="col" class="p-4">Descripcion descuento</th>
                                    <th scope="col" class="p-4">Consecutivo</th>
                                    <th scope="col" class="p-4">Nro cuota</th>
                                    <th scope="col" class="p-4">Valor cuota</th>
                                    <th scope="col" class="p-4">Valor aplicar</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-300 dark:divide-neutral-700">
                                <template x-for="(row, index) in rows" :key="index">
                                    <tr>
                                        <td class="p-4" x-text="row.fecha_vencimiento"></td>
                                        <td class="p-4" x-text="row.con_descuento"></td>
                                        <td class="p-4" x-text="row.descripcion_concepto"></td>
                                        <td class="p-4" x-text="row.consecutivo"></td>
                                        <td class="p-4" x-text="row.nro_cuota"></td>
                                        <td class="p-4" x-text="row.vlr_cuota"></td>
                                        <td class="p-4" x-init="console.log(isEditing)"
                                            @dblclick="toggleEditingState(row.id)">
                                            <input type="text" x-show="isEditing[row.id]"
                                                @click.away="disableEditing(row.id)"
                                                @keydown.enter="disableEditing(row.id)"
                                                @keydown.window.escape="disableEditing(row.id)"
                                                class="bg-white focus:outline-none focus:shadow-outline border border-gray-300 rounded-lg py-2 px-4 appearance-none leading-normal w-128"
                                                x-ref="row.id-class">
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                </div>

                {{-- Otros conceptos --}}
                <div x-show="otros_conceptos" class="grid mt-6 grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        Concepto
                        <x-filament::input.wrapper>
                            <x-slot name="prefix" icon="heroicon-c-magnifying-glass-circle">
                            </x-slot>
                            <x-filament::input.select x-model="selectedConcepto" @change="updateForm(selectedConcepto)">
                                <option value="">Seleccionar</option>
                                <template x-for="(concepto, index) in conceptos" :key="concepto.id">
                                    <option :value="concepto.id"
                                        x-text="concepto.codigo_descuento + ' ' + concepto.descripcion"></option>
                                </template>
                            </x-filament::input.select>
                        </x-filament::input.wrapper>
                    </div>

                    <div class="mb-3">
                        Cuenta contable
                        <x-filament::input.wrapper>
                            <x-filament::input type="text" x-model="newRow.cuenta_contable" />
                        </x-filament::input.wrapper>
                    </div>

                    <div class="mb-3">
                        Valor
                        <x-filament::input.wrapper>
                            <x-filament::input type="text" x-model="newRow.valor" />
                        </x-filament::input.wrapper>
                    </div>

                    <div class="mb-3">
                        <label for=""></label>
                        <x-filament::button icon="heroicon-m-plus" @click="addComposicion()">
                            Agregar
                        </x-filament::button>
                    </div>
                </div>

                <div x-show="otros_conceptos" class="table_conceptos">
                    <div
                        class="overflow-hidden w-full mt-6 overflow-x-auto rounded-md border border-neutral-300 dark:border-neutral-700">
                        <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-300">
                            <thead
                                class="border-b border-neutral-300 bg-neutral-50 text-sm text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                                <tr>
                                    <th scope="col" class="p-4">Codigo Concepto</th>
                                    <th scope="col" class="p-4">Descripción</th>
                                    <th scope="col" class="p-4">Valor</th>
                                    <th scope="col" class="p-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-300 dark:divide-neutral-700">
                                <template x-for="(row, index) in rows" :key="index">
                                    <tr>
                                        <td class="p-4" x-text="row.concepto_descuento"></td>
                                        <td class="p-4" x-text="row.nombre_concepto"></td>
                                        <td class="p-4" x-text="row.valor"></td>
                                        <td class="p-4" style="justify-items: center;">
                                            <x-filament::icon-button icon="heroicon-m-trash" color="danger"
                                                @click="() => { removeRow(index); }" />
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        @endif
    </x-filament-panels::form>

    <script>
        function data() {
            return {
                creditos: true,
                obligaciones: false,
                otros_conceptos: false,
                valorAplicado: '',
                conceptos: @js($conceptos),
                obligaciones: @js($obligaciones),
                selectedConcepto: '',
                isEditing: {},
                rows: [],
                newRow: {
                    id_concepto: '',
                    concepto_descuento: '',
                    nombre_concepto: '',
                    cuenta_contable: '',
                    valor: 0,
                },
                updateForm(concepto) {
                    if (concepto) {
                        this.conceptos.forEach((c) => {
                            if (c.id == concepto) {
                                this.newRow.id_concepto = c.id;
                                this.newRow.nombre_concepto = c.descripcion;
                                this.newRow.concepto_descuento = c.codigo_descuento;
                                this.newRow.cuenta_contable = c.cuenta_contable;
                            }
                        });
                    }
                },
                addComposicion() {
                    this.rows.push({
                        ...this.newRow
                    });
                },
                removeRow(index) {
                    this.rows.splice(index, 1);
                },
                toggleEditingState(id) {
                    this.isEditing[id] = !this.isEditing[id];

                    if (this.isEditing[id]) {
                        this.$nextTick(() => {
                            const inputRef = this.$refs[`${id}-class`]; // Accede directamente al ref
                            const tdElement = document.getElementById(`${id}-td`); // Busca el <td> por ID

                            if (inputRef && tdElement) {
                                tdElement.innerText = inputRef.value; // Almacena el valor actual en el <td>
                            }
                        });
                    }
                },
                disableEditing(id) {
                    this.isEditing[id] = false; // Desactiva la edición para la fila correspondiente
                }
            }
        }
    </script>
</x-filament-panels::page>
