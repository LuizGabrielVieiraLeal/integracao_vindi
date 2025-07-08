<?php

namespace App\Http\Controllers;

use App\Services\Erp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebHookController extends Controller
{
    public function handle(Request $request)
    {
        $event = $request->input('event');

        switch ($event['type']) {
            case 'bill_paid':
                $plan = $event['data']['bill']['subscription']['plan'];
                $customer = $event['data']['bill']['customer'];
                Erp::updatePlan($customer['code'], $plan['code']);
                break;
            default:
                Log::channel('stderr')->info('Requisição recebida: ' . json_encode($request->all()));
                break;
        }
    }
}
