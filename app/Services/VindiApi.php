<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class VindiApi
{
    //customers
    public static function getCustomerById($id)
    {
        $response = Http::vindi()->get('/customers/' . $id);

        if ($response->successful()) return $response->json();
        else return null;
    }

    public static function getCustomerByCode($code)
    {
        $response = Http::vindi()->get('/customers?query=code:' . $code);

        if ($response->successful()) return collect($response->json()['customers'])->first();
        else return null;
    }

    public static function createCustomer($code, $name, $email)
    {
        $response = Http::vindi()->post('/customers', [
            'code' => $code,
            'name' => $name,
            'email' => $email
        ]);

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

    // payments
    public static function subscribe($plan_id, $customer_id, $address, $next_invoice_at, $payment_method_code, $coupon_code = null)
    {
        $response = Http::vindi()->post('/subscriptions', [
            'plan_id' => $plan_id,
            'customer_id' => $customer_id,
            'next_invoice_at' => $next_invoice_at,
            'payment_method_code' => $payment_method_code,
            'coupon_code' => $coupon_code,
            'address' => $address
        ]);

        if ($response->successful()) return $response->json();
        else return null;
    }

    // invoices
    public static function getInvoice($subscription_id)
    {
        $response = Http::vindi()->get('/bills?query=subscription_id:' . $subscription_id);

        if ($response->successful()) return $response->json();
        else return null;
    }
}
