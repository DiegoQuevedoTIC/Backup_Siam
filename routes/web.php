<?php

use App\Http\Controllers\BalanceGeneralController;
use App\Http\Controllers\CierreMensualController;
use Illuminate\Support\Facades\Route;
use App\Jobs\CierreMensual;


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

Route::post('/generar-pdf', [BalanceGeneralController::class, 'generarPdf'])->name('generarpdf');


Route::get('/x', function () {
    return view('welcome');
});
