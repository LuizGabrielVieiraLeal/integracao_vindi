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
                    "number" => trim($empresa->celular)
                ]
            ]
        ];
    }

    public static function makeCheckoutParams($idEmpresa, $idPlano, $metodo_pgto)
    {
        $empresa = Empresa::find($idEmpresa);

        return [
            'customerCode' => $empresa->id,
            'planCode' => $idPlano,
            'paymentMethod' => $metodo_pgto,
        ];
    }

    public static function getPlanDiscounts($planoId, $empresaId)
    {
        /* switch ((int) $planoId) {
            case 12:
                $planoEmpresa = PlanoEmpresa::where('empresa_id', $empresaId)->first();
                $ciclos = $planoEmpresa->ciclos ?? null;
                if (!$ciclos || $ciclos <= 6) {
                    return [[
                        'amount' => 70, // R$ 70,00 de desconto
                        'cycles' => 6,     // Aplica por 6 meses
                        'discount_type' => 'amount',
                    ]];
                } else return [];
            case 14:
                $planoEmpresa = PlanoEmpresa::where('empresa_id', $empresaId)->first();
                $ciclos = $planoEmpresa->ciclos ?? null;
                if (!$ciclos || $ciclos <= 6) {
                    return [[
                        'amount' => 70, // R$ 70,00 de desconto
                        'cycles' => 3,     // Aplica por 6 meses
                        'discount_type' => 'amount',
                    ]];
                } else return [];
                break;
            default:
                return [];
                break;
        }*/
        return [];
    }

    public static function updatePlan($idEmpresa, $idPlano, $metodo_pgto)
    {
        $empresa = Empresa::findOrFail($idEmpresa);
        $plano = Plano::findOrFail($idPlano);
        $planoEmpresa = PlanoEmpresa::where('empresa_id', $empresa->id)->first();
        $ciclos = null;
        $intervaloDias = null;

        switch ($plano->id) {
            case 1:
                $intervaloDias = 15;
                break;
            case 2:
            case 3:
            case 4:
            case 14:
            case 16:
            case 18:
            case 22:
                $intervaloDias = 30;
                break;
            case 5:
            case 6:
            case 7:
            case 15:
            case 17:
            case 19:
            case 23:
                $intervaloDias = 90;
                break;
            case 8:
            case 9:
            case 10:
            case 12:
            case 13:
            case 20:
            case 21:
                $intervaloDias = 365;
                break;
            default:
                $intervaloDias = 30;
                break;
        }

        if ($plano->id == $planoEmpresa->plano_id) $ciclos = $planoEmpresa->ciclos ? $planoEmpresa->ciclos + 1 : 1;
        else $ciclos = 1;

        $planoEmpresa->update([
            'plano_id' => $plano->id,
            'ciclos' => $ciclos,
            'valor' => $plano->valor,
            'forma_pagamento' => $metodo_pgto,
            'data_expiracao' => DB::raw("DATE_ADD(data_expiracao, INTERVAL {$intervaloDias} DAY)"),
        ]);
    }
}
