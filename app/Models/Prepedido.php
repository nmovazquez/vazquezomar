<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prepedido extends Model
{   
    protected $table = 'prepedidos';

    protected $fillable = [
        'folio', 'correo', 'subtotal', 'impuestos', 'total', 'fecha',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function detalles()
    {
        return $this->hasMany(PrepedidoDetalle::class);
    }
}
