<x-filament-panels::page>
    <x-filament-panels::form>

        <style>
            <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
        </style>

        @if (!$showTable || $showOne)
            {{ $this->form }}


            <x-filament::button wire:loading.class="pointer-events-none opacity-70" wire:click="generateReport">
                Generar reporte
            </x-filament::button>
        @endif


        @if ($showOne)
            <div>
                datos
            </div>
        @endif

        {{-- @if ($showTable)
            <div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>N° Documento</th>
                            <th>Descripción</th>
                            <th>Total Débito</th>
                            <th>Total Crédito</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datatable as $comprobante)
                            <tr>
                                <td>{{ $comprobante->fecha_comprobante }}</td>
                                <td>{{ $comprobante->n_documento }}</td>
                                <td>{{ $comprobante->descripcion_comprobante }}</td>
                                <td>{{ $comprobante->total_debito }}</td>
                                <td>{{ $comprobante->total_credito }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif --}}

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#miTabla').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.21/dataTables.spanish.json" // Cambia el idioma si es necesario
                    }
                });
            });
        </script>

    </x-filament-panels::form>
</x-filament-panels::page>
