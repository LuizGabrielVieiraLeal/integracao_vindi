<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\WebHookController;
use App\Http\Middleware\VerifyVindiWebhookToken;
use Illuminate\Support\Facades\Route;

// auth routes
Route::post('/login', [AuthController::class, 'login']);

// payments routes
Route::post('/payments/credit_card', [PaymentsController::class, 'credit_card'])->middleware('auth:sanctum');

// feedbacks routes
Route::post('/webhook/vindi', [WebHookController::class, 'handle'])->middleware(VerifyVindiWebhookToken::class);
