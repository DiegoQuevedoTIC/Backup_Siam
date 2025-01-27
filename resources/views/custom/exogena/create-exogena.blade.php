<div>
    <h2 class="text-center text-xl font-bold mb-4 mt-6" style="font-size: 32px;">Informacion Exogena</h2>
    <div class="flex gap-8 mt-6">
        <div class="flex-none w-72">
            <ul class="hidden flex-col gap-y-7 md:flex m-5">
                <form id="form_data">
                    <div class="mb-6 mt-3">
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo de
                            Informe</label>

                        <x-filament::input.wrapper>
                            <x-filament::input.select id="tipo_balance" name="tipo_balance" required>
                                <option value="0" selected disabled>Seleccionar tipo de Informe</option>
                                <option value="1">Formato 1001 - Pagos o abonos en cuenta</option>
                                <option value="2">Formato 1003 - Retenciones en la fuente practicadas</option>
                                <option value="3">Formato 1007 - Ingresos recibidos</option>
                                <option value="4">Formato 1008 - Ingresos recibidos por entidades del régimen especial</option>
                                <option value="5">Formato 1011 - Información de socios o accionistas</option>
                                <option value="6">Formato 1012 - Aportes recibidos por entidades del sector solidario</option>
                                <option value="7">Formato 1013 - Operaciones con socios o accionistas</option>
                                <option value="8">Formato 2276 - Información de control sobre activos en el exterior</option>
                                <option value="9">Formato 2277 - Pagos al exterior</option>
                                <option value="10">Formato 2279 - Operaciones con entidades no obligadas a declarar</option>
                                <option value="11">Formato 1647 - Información de terceros</option>
                                <option value="12">Formato 2601 - Información de descuentos tributarios</option>
                                <option value="13">Formato 2613 - Información de contratos</option>
                                <option value="14">Formato 2616 - Movimientos de bienes entregados en consignación</option>
                                <option value="15">Formato 2624 - Operaciones con tarjetas de crédito o débito</option>
                                <option value="16">Formato 2634 - Información de deudas incobrables</option>
                                <option value="17">Formato 2636 - Operaciones de leasing</option>
                                <option value="18">Formato 2673 - Información de inversiones</option>
                                <option value="19">Formato 2674 - Préstamos otorgados o recibidos</option>

                            </x-filament::input.select>
                        </x-filament::input.wrapper>
                    </div>

                    <div class="mb-6 mt-3">
                        <label for="fecha_inicial"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha Inicial</label>
                        <x-filament::input.wrapper>
                            <x-filament::input id="fecha_inicial" name="fecha_inicial" type="date" required />
                        </x-filament::input.wrapper>
                    </div>

                    <div class="mb-6 mt-3">
                        <label for="fecha_final"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha Final</label>
                        <x-filament::input.wrapper>
                            <x-filament::input id="fecha_final" name="fecha_final" type="date" required />
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

                <div id="export_button" style="display: contents;">

                </div>
            </ul>
        </div>
        <div
            class="flex-1 mt-6 border border-dashed border-gray-300 rounded-lg flex items-center justify-center relative">

            <embed id="pdf" type="application/pdf" width="100%" height="600px" class="rounded-lg hidden" />

            <div id="divEmpty">
                <x-filament::icon id="empty" icon="heroicon-m-document-text"
                    class="h-20 w-20 text-gray-500 dark:text-gray-400" />
            </div>

            <x-filament::loading-indicator id="loading" class="h-20 w-20 hidden" />
        </div>
    </div>


    <script src="{{ asset('js/lib/jquery.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
            });
            var e = $("#form_data"),
                a = $("#generar"),
                n = $("#pdf"),
                t = $("#empty"),
                o = $("#loading"),
                r = $("#tipo_balance"),
                i = $("#fecha_inicial"),
                s = $("#fecha_final"),
                c = $("#is_13_month"),
                l = $("#nivel"),
                d = $("#export_button");
            e.on("input change", function n() {
                var t = !0;
                e.find("input, select").each(function() {
                        if ("" === $(this).val()) return t = !1,
                            a.addClass("pointer-events-none opacity-70"),
                            !1
                    }),
                    a.prop("disabled", !t),
                    t && a.removeClass("pointer-events-none opacity-70")
            }), a.on("click", function() {
                o.removeClass("hidden"),
                    t.addClass("hidden"),
                    n.addClass("hidden");
                var e = {
                        tipo_balance: r.val(),
                        fecha_inicial: i.val(),
                        fecha_final: s.val(),
                        is_13_month: c.is(":checked"),
                        nivel: l.val()
                    },
                    p = "{{ route('generarpdf') }}";
                switch (e.tipo_balance) {
                    case "2":
                        f(p = "{{ route('generar.balance.horizontal') }}");
                        break;
                    case "3":
                        f(p = "{{ route('generar.balance.tercero') }}");
                        break;
                    case "4":
                        f(p = "{{ route('generar.balance.comparativo') }}");
                        break;
                    default:
                        f(p);
                        return
                }

                function f(r) {
                    d.has("button_export_excel") && $(".button_export_excel").remove(),
                        $.ajax({
                            url: r,
                            type: "POST",
                            data: e,
                            success: function(e) {
                                if (e.pdf && n.attr("src", "data:application/pdf;base64," + e.pdf),
                                    e.excel) {
                                    var t = new Blob([new Uint8Array(atob(e.excel).split("").map(
                                        function(e) {
                                            return e.charCodeAt(0)
                                        }))], {
                                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                                    });
                                    d.append(
                                            `<x-filament::button color="info" class="button_export_excel" style="witdh: 100%;" href="${URL.createObjectURL(t)}" tag="a" download="${e.excel_file_name}" icon="heroicon-o-document-arrow-down" icon-position="after">  Exportar Excel </x-filament::button>`
                                            ),

                                            new FilamentNotification()
                                            .title("El reporte se encuentra disponible para la exportaci\xf3n a excel.")
                                            .success()
                                            .send()
                                }
                                o.addClass("hidden"), n.removeClass("hidden"), a.prop("disabled", !0)
                            },
                            error: function(e, a, n) {
                                console.log(n), e.responseJSON && e.responseJSON.message ?
                                    new FilamentNotification().title(e.responseJSON.message)
                                    .danger().send() : new FilamentNotification().title(
                                        "Ocurri\xf3 un error inesperado.").danger().send(), o
                                    .addClass("hidden"), t.removeClass("hidden")
                            }
                        })
                }
            })
        });
        </script>
</div>
