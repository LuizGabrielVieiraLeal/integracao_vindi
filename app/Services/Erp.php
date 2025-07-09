<?php

namespace App\Services;

use App\Models\Empresa;
use App\Models\Plano;
use App\Models\PlanoEmpresa;
use Illuminate\Support\Facades\DB;

class Erp
{
    public static function makeCustomerParams($code)
    {
        $empresa = Empresa::find($code);

        return [
            'code' => $empresa->id,
            'name' => $empresa->nome,
            'email' => $empresa->email,
            'phones' => [
                [
                    "phone_type" => "mobile",
                    "number" => $empresa->celular
                ]
            ]
        ];
    }

    public static function getCustomerAddress($code)
    {
        $empresa = Empresa::find($code);

        return [
            'street' => $empresa->rua,
            'number' => $empresa->numero,
            'zip_code' => $empresa->cep,
            'city' => $empresa->cidade->nome,
            'state' => $empresa->cidade->uf,
            'country' => 'BR'
        ];
    }

    public static function updatePlan($customerCode, $planCode)
    {
        $empresa = Empresa::findOrFail($customerCode);
        $plano = Plano::findOrFail($planCode);

        PlanoEmpresa::where('empresa_id', $empresa->id)
            ->update([
                'plano_id' => $plano->id,
                'data_expiracao' => DB::raw("DATE_ADD(data_expiracao, INTERVAL {$plano->intervalo_dias} DAY)"),
                'forma_pagamento' => 'Cartão de crédito'
            ]);
    }
}
