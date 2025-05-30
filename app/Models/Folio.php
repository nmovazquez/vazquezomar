<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Folio extends Model
{
   
    protected $table = 'folio';
    protected $fillable = [
        'serie',
        'folio',
    ];
}
