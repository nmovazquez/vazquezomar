<?php

namespace App\Http\Controllers;

use App\Models\Folio;
use App\Models\Producto;
use App\Models\Prepedido;
use App\Models\PrepedidoDetalle;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PrepedidoController extends Controller
{
    public function index()
    {   
     
        $data['productos'] = Producto::where('activo',1)
                            ->whereDate('fecha_vigencia', '>', Carbon::today())
                            ->where('stock','>',0)
                            ->get();

        return view('prepedidos.prepedidos')->with('data', $data);
    }
    public function store(Request $request){

        try {

            date_default_timezone_set('America/Mexico_City');
            $fechaactual = date('Y-m-d');

            DB::beginTransaction();

            $folio = Folio::first();
            $folPrepe = $folio->serie.($folio->folio + 1);

            $prepedido = new Prepedido;
            $prepedido->folio = $folPrepe;
            $prepedido->correo = $request->correo;
            $prepedido->subtotal = $request->subtotal;
            $prepedido->impuestos = $request->impuestos;
            $prepedido->total = $request->total;
            $prepedido->fecha = $fechaactual;
            $prepedido->save();

            $sinStock = [];
            foreach ($request->items as $key => $value) {
               $producto = Producto::find($value['articulo_id']);
               if((int) $value['cantidad'] > $producto->stock ){
                    array_push($sinStock, $producto->nombre);
               }else{
                    $producto->stock = $producto->stock - $value['cantidad'];
                    $producto->save();
               }
               $detalle = new PrepedidoDetalle($value);
               $idserRelation = $prepedido->detalles()->save($detalle);
            }
      
            if(count($sinStock) > 0){
                DB::rollBack();
                return response()->json(['estatus' => 0, 'message' => 'Existen productos sin stock', 'items' => $sinStock ],422);
            }
            $folio->folio = ($folio->folio + 1); 
            $folio->save();
            DB::commit();
            return response()->json(['estatus' => $prepedido->id, 'message' => 'Prepedido generado con exito.'],200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['estatus' => 0, 'message' => $e->getMessage() . $e->getLine(),'items' => ''],422);
        }
    }
    public function get(Request $r){
        $prepedido = Prepedido::with('detalles.articulo')->find($r->id);
        return response()->json(['data' => $prepedido]);
    }
    public function lista(Request $r){
      
        return view('prepedidos.lista');
    }
    public function get_lista(Request $r){
    
        $fechas = explode(' - ', $r->fecha);
        // Convertir a objeto DateTime desde el formato d/m/Y
        $desde = \DateTime::createFromFormat('d/m/Y', $fechas[0]);
        // Convertir a formato Y-m-d (tipo DATE en SQL)
        $fechaDesdeFormateada = $desde->format('Y-m-d');

        $hasta = \DateTime::createFromFormat('d/m/Y', $fechas[1]);
        $fechaHastaFormateada = $hasta->format('Y-m-d');

        $prepedidos = Prepedido::whereBetween('fecha', [$fechaDesdeFormateada, $fechaHastaFormateada])->get();
        return response()->json(['data' => $prepedidos]);
    }
}
