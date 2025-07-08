<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Services\VindiApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebHookController extends Controller
{
    public function handle(Request $request)
    {
        $event = $request->input('event');

        switch ($event['type']) {
            case 'bill_paid':
                Log::channel('stderr')->info('Recebi webhook: ' . json_encode($event['data']));
            default:
                break;
        }
    }
}
