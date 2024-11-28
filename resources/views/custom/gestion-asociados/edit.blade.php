<x-filament-panels::page>
    <x-filament-panels::form wire:submit="save">
        {{ $this->form }}

        <x-filament-panels::form.actions :actions="$this->getCachedFormActions()" :full-width="$this->hasFullWidthFormActions()" />
    </x-filament-panels::form>

    @if (count($relationManagers = $this->getRelationManagers()))
        <x-filament-panels::resources.relation-managers :active-manager="$this->activeRelationManager" :managers="$relationManagers" :owner-record="$record"
            :page-class="static::class" />
    @endif

    <script src="{{ asset('js/datatable/jquery.min.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        window.addEventListener('download', event => {
            const asociado = event.detail[0][0][0];
            $.ajax({
                url: "{{ route('exportar.solicitud') }}",
                data: {
                    asociado: asociado.id
                },
                method: 'POST',
                dataType: 'json',
                success: function(response) {
                    // Recibir el Base64 del PDF
                    const pdfData = response.pdf;

                    // Crear y descargar el PDF automáticamente
                    const pdfBlob = atob(pdfData).split('').map(char => char.charCodeAt(0));
                    const pdfFile = new Blob([new Uint8Array(pdfBlob)], {
                        type: 'application/pdf'
                    });
                    const pdfUrl = URL.createObjectURL(pdfFile);

                    const downloadLink = document.createElement('a');
                    downloadLink.href = pdfUrl;
                    downloadLink.setAttribute('download', 'downloaded.pdf');
                    document.body.appendChild(downloadLink);
                    downloadLink.click();
                    document.body.removeChild(downloadLink);

                    URL.revokeObjectURL(pdfUrl);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    </script>
</x-filament-panels::page>
