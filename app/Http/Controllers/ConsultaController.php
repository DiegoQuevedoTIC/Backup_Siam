<?php

namespace App\Http\Controllers;

use App\Models\Comprobante;
use App\Models\TipoDocumentoContable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;

class ConsultaController extends Controller
{
    //
    public function consultaComprobante(Request $request)
    {
        //dd($request->all());

        $nro_comprobante = $request->nro_comprobante;
        $tipo_comprobante = $request->tipo_comprobante;

        if ($request->ajax()) {
            $data = DB::table('comprobantes AS c')
                ->select(
                    'c.id',
                    'c.fecha_comprobante',
                    'c.n_documento',
                    'c.descripcion_comprobante',
                    DB::raw('SUM(cl.debito) AS total_debito'),
                    DB::raw('SUM(cl.credito) AS total_credito')
                )
                ->join('comprobante_lineas AS cl', 'c.id', '=', 'cl.comprobante_id')
                ->where('c.tipo_documento_contables_id', $tipo_comprobante)
                ->when($nro_comprobante, function ($query) use ($nro_comprobante) {
                    return $query->orWhere('c.n_documento', $nro_comprobante);
                })
                ->groupBy('c.fecha_comprobante', 'c.n_documento', 'c.descripcion_comprobante', 'c.id')
                ->orderBy('c.fecha_comprobante')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<button data-id="' . $row->id . '" style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);" class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50" type="button">
                            <span class="fi-btn-label">
                                Ver
                            </span>
                        </button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}
