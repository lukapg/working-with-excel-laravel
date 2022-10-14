<?php

use App\Http\Controllers\ImportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('import/laravel-excel', [ImportController::class, 'laravelExcel'])->name('import.laravel-excel');
Route::post('import/simple-excel-reader', [ImportController::class, 'simpleExcelReader'])->name('import.simple-excel-reader');
Route::post('import/fast-excel', [ImportController::class, 'fastExcel'])->name('import.fast-excel');