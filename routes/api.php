<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DispositivosController;
use App\Http\Controllers\Api\InfoGeneralController;
use App\Http\Controllers\Api\SensorController;
use App\Http\Controllers\Api\LoginController;

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

Route::controller(DispositivosController::class)->group(function(){
    Route::get('dispositivos', 'index');
    Route::post('dispositivos','store');
    Route::put('dispositivos/{registro}', 'update');
    Route::delete('dispositivos/{registro}', 'destroy');
});

Route::controller(InfoGeneralController::class)->group(function(){
    Route::get('infogeneral', 'index');
    Route::post('infogeneral','store');
    Route::put('infogeneral/{registro}', 'update');
    Route::delete('infogeneral/{registro}', 'destroy');
});

Route::controller(SensorController::class)->group(function(){
    Route::get('sensor', 'index');
    Route::post('sensor','store');
    Route::put('sensor/{registro}', 'update');
    Route::delete('sensor/{registro}', 'destroy');
});

Route::controller(LoginController::class)->group(function(){
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
});



