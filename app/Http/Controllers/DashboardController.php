<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(){
        $top = DB::table('prepedido_detalles')
            ->select('articulo_id', DB::raw('SUM(cantidad) as total'))
            ->groupBy('articulo_id')
            ->orderByDesc('total')
            ->take(3)
            ->get();

        // Obtener los nombres de los productos
        $datos = $top->map(function ($item) {
            $nombre = DB::table('productos')->where('id', $item->articulo_id)->value('nombre');
            return [
                'nombre' => $nombre,
                'total' => $item->total
            ];
        });


        return view('dashboard', ['datos' => $datos]);

    }

}
