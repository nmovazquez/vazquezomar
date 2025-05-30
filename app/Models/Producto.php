<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use SoftDeletes;

    protected $table = 'productos';

    protected $fillable = [
        'sku',
        'nombre',
        'descripcion_corta',
        'descripcion_larga',
        'precioDolares',
        'precioPesos',
        'imagen',
        'stock',
        'fecha_vigencia',
        'activo',
    ];

    protected $casts = [
        'fecha_vigencia' => 'date',
        'activo' => 'boolean',
    ];

    public function getFechaVigenciaAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }
}
