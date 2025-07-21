<?php

use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\WebHookController;
use Illuminate\Support\Facades\Route;

// payments routes
Route::post('/payments/credit_card', [PaymentsController::class, 'credit_card']);
Route::post('/payments/bank_slip', [PaymentsController::class, 'bank_slip']);
Route::post('/payments/pix', [PaymentsController::class, 'pix']);

// Vindi feedbacks routes
Route::post('/webhook/vindi/{token}', [WebHookController::class, 'handle']);
