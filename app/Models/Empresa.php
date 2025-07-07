<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $connection = 'erp';

    public function cidade()
    {
        return $this->belongsTo(Cidade::class);
    }
}
