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
        $transaction = $request->input('data.transaction');

        // atualizando tabela no ERP
        if ($event === 'transaction_paid') {
            $customer = VindiApi::getCustomerById($transaction['customer_id']);
            $empresa = Empresa::find($customer['code']);

            Log::info("Pagamento recebido.", [
                'subscription_id' => $transaction['subscription_id'],
                'customer_id' => $transaction['customer_id'],
                'empresa_id' => $empresa->id
            ]);
        }
    }
}
