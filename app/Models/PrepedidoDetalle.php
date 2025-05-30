<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrepedidoDetalle extends Model
{
    protected $table = 'prepedido_detalles';

    protected $fillable = [
        'prepedido_id', 'articulo_id', 'cantidad', 'precioDolares', 'precioPesos',
    ];

    public function prepedido()
    {
        return $this->belongsTo(Prepedido::class);
    }

    public function articulo()
    {
        return $this->belongsTo(Producto::class, 'articulo_id'); 
    }
}
