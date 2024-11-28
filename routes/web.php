<?php

use App\Http\Controllers\BalanceGeneralController;
use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;


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
    return view('welcome');
});
