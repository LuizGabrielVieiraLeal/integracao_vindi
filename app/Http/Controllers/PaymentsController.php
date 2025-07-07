<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionRequest;
use App\Models\Empresa;
use App\Models\Subscription;
use App\Services\ApiResponse;
use App\Services\VindiApi;

class PaymentsController extends Controller
{
    // subscriptions
    public function pix(SubscriptionRequest $request)
    {
        $request->validated();

        $next_invoice_at = $request->input('data_vencimento');
        $coupon_code = $request->input('cupom');

        // pegando id do plano correspondente na Vindi
        $plan = VindiApi::getPlanByCode($request->input('id_plano'));
        $plan_id = $plan['id'] ?? null;

        // pegando o id do cliente correspondente na Vindi
        $customer = VindiApi::getCustomerByCode($request->input('id_empresa'));
        $customer_id = $customer['id'] ?? null;

        if (!$customer_id) {
            // criando um cliente na Vindi caso não exista
            $empresa = Empresa::find($request->input('id_empresa'));

            $code = $empresa->id;
            $name = $empresa->nome;
            $email = $empresa->email;
            $address = [
                'street' => $empresa->rua,
                'number' => $empresa->numero,
                'zip_code' => $empresa->cep,
                'city' => $empresa->cidade->nome,
                'state' => $empresa->cidade->uf,
                'country' => 'BR'
            ];

            $customer = VindiApi::createCustomer($code, $name, $email);

            if (!$customer) return ApiResponse::error('Erro ao criar cliente na Vindi.');
            else $customer_id = $customer['id'];
        }

        // criando assinatura
        $response = VindiApi::subscribe($plan_id, $customer_id, $next_invoice_at, 'pix', $coupon_code);

        if (!$response) return ApiResponse::error('Erro ao realizar assinatura.');

        $subscription = $response['subscription'];
        $subscriptionId = $subscription['id'];

        // buscando a URL última cobrança gerada
        $response = VindiApi::getInvoice($subscriptionId);
        if (!$response) return ApiResponse::error('Erro ao buscar faturas da assinatura.');

        $bills = collect($response['bills'])->filter(function ($bill) use ($subscriptionId) {
            return isset($bill['subscription']['id']) && $bill['subscription']['id'] == $subscriptionId;
        });

        $lastBill = collect($bills)->sortByDesc('created_at')->first();
        $paymentURL = $lastBill['url'] ?? null;

        return ApiResponse::success('Assinatura realizada com sucesso.', ['url' => $paymentURL]);
    }
}
