<?php

namespace App\Services;

use App\Models\Empresa;
use App\Models\Plano;
use App\Models\PlanoEmpresa;
use Illuminate\Support\Facades\DB;

class Erp
{
    public static function makeCustomerParams($idEmpresa)
    {
        $empresa = Empresa::find($idEmpresa);

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

    public static function makeCheckoutParams($idEmpresa, $idPlano)
    {
        $empresa = Empresa::find($idEmpresa);

        return [
            'customerCode' => $idEmpresa,
            'planCode' => $idPlano,
        ];
    }

    public static function getCustomerAddress($idEmpresa)
    {
        $empresa = Empresa::find($idEmpresa);

        return [
            'street' => $empresa->rua,
            'number' => $empresa->numero,
            'zip_code' => $empresa->cep,
            'city' => $empresa->cidade->nome,
            'state' => $empresa->cidade->uf,
            'country' => 'BR'
        ];
    }

    public static function updatePlan($idEmpresa, $idPlano)
    {
        $empresa = Empresa::findOrFail($idEmpresa);
        $plano = Plano::findOrFail($idPlano);

        $intervalo_dias = $plano->id === 1 ? 15 : 30;

        PlanoEmpresa::where('empresa_id', $empresa->id)
            ->update([
                'plano_id' => $plano->id,
                'data_expiracao' => DB::raw("DATE_ADD(NOW(), INTERVAL {$intervalo_dias} DAY)"),
                'forma_pagamento' => 'Cartão de crédito'
            ]);
    }
}
