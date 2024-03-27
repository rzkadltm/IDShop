<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Middleware\Authenticate;
use App\Http\Controllers\Api\ModelTypeController;
use App\Http\Controllers\Api\AdvertisementController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/modeltypes', ModelTypeController::class);
Route::apiResource('/advertisements', AdvertisementController::class);