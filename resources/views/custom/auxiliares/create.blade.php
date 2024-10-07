<x-filament-panels::page>
    <x-filament-panels::form>
        {{ $this->form }}

        <button style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);"
            class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2‰ rounded-lg fi-color-custom fi-btn-color-primary fi-size-sm fi-btn-size-sm gap-1 px-2.5 py-1.5 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50"
            type="button" wire:click="exportPDF">
            <span class="fi-btn-label">
                Generar reporte
            </span>
        </button>
    </x-filament-panels::form>
</x-filament-panels::page>
