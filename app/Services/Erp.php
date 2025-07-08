<?php

namespace App\Services;

use App\Models\Empresa;

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
}
