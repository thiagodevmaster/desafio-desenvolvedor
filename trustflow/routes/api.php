<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:sanctum')->prefix('/v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});