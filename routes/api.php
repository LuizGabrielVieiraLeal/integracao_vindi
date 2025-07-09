<?php

use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\WebHookController;
use App\Http\Middleware\VerifyIntegrationToken;
use Illuminate\Support\Facades\Route;

// payments routes
Route::post('/payments/credit_card', [PaymentsController::class, 'credit_card'])->middleware(VerifyIntegrationToken::class);

// Vindi feedbacks routes
Route::post('/webhook/vindi', [WebHookController::class, 'handle'])->middleware(VerifyIntegrationToken::class);
