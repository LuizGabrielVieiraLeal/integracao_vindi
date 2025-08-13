<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class VindiApi
{
    //customers
    public static function getCustomerByCode($code)
    {
        $response = Http::vindi()->get('/customers?query=code:' . $code);
        if ($response->successful()) return collect($response->json()['customers'])->first();
        else return null;
    }

    public static function createCustomer($customerParams)
    {
        $response = Http::vindi()->post('/customers', $customerParams);
        if ($response->successful()) return $response->json()['customer'];
        else return null;
    }

    //plans
    public static function getPlanByCode($code)
    {
        $response = Http::vindi()->get('/plans?query=code:' . $code);
        if ($response->successful()) return collect($response->json()['plans'])->first();
        else return null;
    }

    public static function getProductByCode($code)
    {
        $response = Http::vindi()->get('/products?query=code:' . $code);
        if ($response->successful()) return collect($response->json()['products'])->first();
        else return null;
    }

    // payments
    public static function subscribe($plan_id, $plan_code, $customer_id, $payment_method_code, $discounts)
    {
        $product = self::getProductByCode($plan_code);
        $params = [
            'plan_id' => $plan_id,
            'customer_id' => $customer_id,
            'payment_method_code' => $payment_method_code,
            'product_items' => [
                [
                    'product_id' => $product['id'],
                ]
            ]
        ];

        if (!empty($discounts)) $params['product_items'][0]['discounts'] = $discounts;

        $response = Http::vindi()->post('/subscriptions', $params);
        if ($response->successful()) return $response->json();
        else return null;
    }
}
