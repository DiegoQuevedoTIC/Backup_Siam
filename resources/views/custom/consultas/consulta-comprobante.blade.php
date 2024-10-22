<x-filament-panels::page>
    <x-filament-panels::form>
        {{ $this->form }}

        <x-filament::button wire:loading.class="pointer-events-none opacity-70" wire:click="generateReport">
            Generar reporte
        </x-filament::button>


    </x-filament-panels::form>
</x-filament-panels::page>
