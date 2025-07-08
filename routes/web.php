<?php

use App\Http\Controllers\PaymentsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    abort(403, 'Unauthorized access');
});

// payments routes
Route::get('/payments/checkout', [PaymentsController::class, 'checkout']);
