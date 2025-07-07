<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\WebHookController;
use Illuminate\Support\Facades\Route;

// auth routes
Route::post('/login', [AuthController::class, 'login']);

// payments routes
Route::prefix('payments')->middleware('auth:sanctum')->controller(PaymentsController::class)->group(function () {
    Route::post('/pix', 'pix');
});

// feedbacks routes
Route::post('/webhook/vindi', [WebHookController::class, 'handle'])
     ->middleware('verify.vindi.token');
