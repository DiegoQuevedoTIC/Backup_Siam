<?php

use App\Http\Controllers\BalanceGeneralController;
use App\Http\Controllers\CentralesGeneralController;
use App\Http\Controllers\ExportController;
use App\Models\Tercero;
use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::post('/generar-pdf', [CentralesGeneralController::class, 'generateCetralesGeneral'])->name('generarpdf');
Route::post('/generar-balance-horizontal', [CentralesGeneralController::class, 'generarCentralesHorizontal'])->name('generar.centrales.riesgo');
Route::post('/generar-balance-por-tercero', [CentralesGeneralController::class, 'generateCentralesTercero'])->name('generar.informe.supersolidaria');
Route::post('/generar-balance-comparativo', [CentralesGeneralController::class, 'generateCentralesComparativo'])->name('generar.informacion.exogena');

Route::post('/export', [CentralesGeneralController::class, 'export'])->name('export');

Route::post('/generar-pdf', [BalanceGeneralController::class, 'generateBalanceGeneral'])->name('generarpdf');
Route::post('/generar-balance-horizontal', [BalanceGeneralController::class, 'generarBalanceHorizontal'])->name('generar.balance.horizontal');
Route::post('/generar-balance-por-tercero', [BalanceGeneralController::class, 'generateBalanceTercero'])->name('generar.balance.tercero');
Route::post('/generar-balance-comparativo', [BalanceGeneralController::class, 'generateBalanceComparativo'])->name('generar.balance.comparativo');

Route::post('/export', [BalanceGeneralController::class, 'export'])->name('export');

Route::post('/exportar-solicitud', [ExportController::class, 'exportSolicitudCredito'])->name('exportar.solicitud');

Route::get('/consulta/comprobantes', [App\Http\Controllers\ConsultaController::class, 'consultaComprobante'])->name('consulta.comprobantes');
Route::get('/consulta/comprobante', [App\Http\Controllers\ConsultaController::class, 'showComprobante'])->name('consulta.comprobante');

Route::get('/read', [BalanceGeneralController::class, 'readCsvFile']);

Route::get('/x', function () {
    $tercero = Tercero::find(1);

    $data = [
        'tercero' => $tercero
    ];

    $pdf = Pdf::loadView('pdf.solicitud_credito', $data);
    return $pdf->stream();
    return view('pdf.solicitud_credito', compact('tercero'));
});
