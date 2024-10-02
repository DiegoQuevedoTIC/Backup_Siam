<x-filament-panels::page>
    <div class="flex gap-8">
        <div class="flex-none w-72">
            <ul class="hidden flex-col gap-y-7 md:flex m-5">
                <x-filament-panels::form wire:submit="create">
                    {{ $this->form }}
                </x-filament-panels::form>

                <button style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);"
                    class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2‰ rounded-lg fi-color-custom fi-btn-color-primary fi-size-sm fi-btn-size-sm gap-1 px-2.5 py-1.5 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50"
                    type="button" wire:click="exportPDF">
                    <span class="fi-btn-label">
                        Generar reporte
                    </span>
                </button>
            </ul>
        </div>
        <div
            class="flex-1 mt-6 border border-dashed border-gray-300 rounded-lg flex items-center justify-center relative">

            @if ($showPDF)
                <embed id="pdf" src="{{ $src }}" type="application/pdf" width="100%" height="600px"
                    class="rounded-lg" />
            @else
                @if ($loading)
                    <x-filament::loading-indicator class="h-20 w-20" />
                @else
                    <div id="empty">
                        <x-filament::icon icon="heroicon-m-document-text"
                            class="h-20 w-20 text-gray-500 dark:text-gray-400" />
                    </div>
                    <br>
                    <div wire:poll.5s="checkJobStatus">
                        @if ($this->checkJobStatus())
                            @if ($this->checkJobStatus())
                                <p>El PDF ha sido generado y está listo para descargar.</p>
                                <a href="{{ Storage::url('pdfs/auxiliar_tercero.pdf') }}" target="_blank">Descargar
                                    PDF</a>
                            @endif
                        @endif
                    </div>
                @endif
            @endif
        </div>
    </div>

</x-filament-panels::page>
