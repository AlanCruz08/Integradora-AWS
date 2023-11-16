<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegistroController;

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

Route::controller(RegistroController::class)->group(function(){
    Route::get('registros', [RegistroController::class, 'index']);
    Route::post('registros', [RegistroController::class, 'store']);
    Route::put('registros/{registro}', [RegistroController::class, 'update']);
    Route::delete('registros/{registro}', [RegistroController::class, 'destroy']);
});
