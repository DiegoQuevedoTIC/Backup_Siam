@php
    use App\Models\CreditoLinea;

    $lineas = CreditoLinea::all()->pluck('descripcion', 'id');
@endphp
<div>

    <x-filament::section class="grid grid-cols-3 gap-4">
        <x-slot name="heading">
            Solicitud de credito
        </x-slot>

        <x-filament::fieldset>
            <x-slot name="label">
                Linea
            </x-slot>

            <x-filament::input.wrapper>
                <x-filament::input.select id="linea" name="linea">
                    <option disabled selected>Seleccionar linea</option>
                    @foreach ($lineas as $key => $linea)
                        <option value="{{ $linea[$key] }}">{{ $linea }}</option>
                    @endforeach
                </x-filament::input.select>
            </x-filament::input.wrapper>
        </x-filament::fieldset>

        <x-filament::fieldset>
            <x-slot name="label">
                Empresa
            </x-slot>

            <x-filament::input.wrapper>
                <x-filament::input type="text" wire:model="empresa" />
            </x-filament::input.wrapper>
        </x-filament::fieldset>


        <x-filament::fieldset>
            <x-slot name="label">
                Tipo Desembolso
            </x-slot>

            <x-filament::input.wrapper>
                <x-filament::input type="text" wire:model="tipo_desembolso" />
            </x-filament::input.wrapper>
        </x-filament::fieldset>


        <x-filament::fieldset>
            <x-slot name="label">
                Valor de la solicitud
            </x-slot>

            <x-filament::input.wrapper>
                <x-filament::input type="text" wire:model="vlr_solicitud" />
            </x-filament::input.wrapper>
        </x-filament::fieldset>

        <x-filament::fieldset>
            <x-slot name="label">
                cuotas maxima
            </x-slot>

            <x-filament::input.wrapper>
                <x-filament::input type="text" wire:model="nro_cuotas_max" />
            </x-filament::input.wrapper>
        </x-filament::fieldset>

        <x-filament::fieldset>
            <x-slot name="label">
                Cuotas de gracia
            </x-slot>

            <x-filament::input.wrapper>
                <x-filament::input type="text" wire:model="nro_cuotas_gracia" />
            </x-filament::input.wrapper>
        </x-filament::fieldset>

        <x-filament::fieldset>
            <x-slot name="label">
                Fecha primera cuota
            </x-slot>

            <x-filament::input.wrapper>
                <x-filament::input type="text" wire:model="fecha_primer_vto" />
            </x-filament::input.wrapper>
        </x-filament::fieldset>

        <x-filament::fieldset>
            <x-slot name="label">
                Tasa Interes
            </x-slot>

            <x-filament::input.wrapper>
                <x-filament::input type="text" wire:model="tasa_id" />
            </x-filament::input.wrapper>
        </x-filament::fieldset>

        <x-filament::fieldset>
            <x-slot name="label">
                Tercero asesor
            </x-slot>

            <x-filament::input.wrapper>
                <x-filament::input type="text" wire:model="tercero_asesor" />
            </x-filament::input.wrapper>
        </x-filament::fieldset>

        <x-filament::fieldset>
            <x-slot name="label">
                Observaciones
            </x-slot>

            <x-filament::input.wrapper>
                <x-filament::input type="text" wire:model="observaciones" />
            </x-filament::input.wrapper>
        </x-filament::fieldset>
    </x-filament::section>

</div>
