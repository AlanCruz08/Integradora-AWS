<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\LoginController;

Route::controller(SensorController::class)->group(function(){
    Route::post('datos', 'datos');
    Route::post('carga','carga');
    Route::post('historico/{id}','historico')->where('id', '[0-9]+');
    Route::post('actual', 'actual');
});

Route::controller(LoginController::class)->group(function(){
    Route::post('login', 'login');
    Route::post('register', 'register');
});