<?php

namespace App\Http\Controllers;
use App\Services\BanxicoService;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductoController extends Controller
{
    public function index()
    {   
        ////Se creo un servicio con la finalidad de reutilizar ly organizar mejor el codigo
        $tipoCambio = BanxicoService::obtenerTipoCambio();
        $productos = Producto::all();
        $data['tipoCambio'] = ($tipoCambio != null) ? $tipoCambio : 0;  
        return view('productos.productos')->with('data', $data);
    }

    public function show(Request $r)
    {
        $producto = Producto::find($r->id);
        return response()->json($producto);
    }

    public function store(Request $request)
    {   

        $validator = Validator::make($request->all(), [
            'sku' => 'required|unique:productos,sku',
            'nombre' => ['required', 'regex:/^[A-Za-z\s]+$/u'],
            'descripcion_corta' => 'nullable|string|max:255',
            'descripcion_larga' => 'nullable|string',
            'precioDolares' => 'required|numeric|min:0.01',
            'precioPesos' => 'required|numeric|min:0.01',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'stock' => 'required|integer|min:0',
            'fecha_vigencia' => 'nullable|date',
            'activo' => 'required|boolean',
        ], [
            'nombre.regex' => 'El nombre solo puede contener letras sin acentos.',
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        // Procesar datos excepto imagen
        $data = $request->except('imagen');

        // Guardar imagen si fue cargada
        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('productos', $fileName,'public');
            $data['imagen'] = 'productos/' . $fileName;
        }
        $producto = Producto::create($data);

        return response()->json(['message' => 'Producto creado correctamente', 'producto' => $producto]);
    }

    public function update(Request $request)
    {   
   
        $producto = Producto::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'sku' => 'required|unique:productos,sku,' . $request->id,
            'nombre' => ['required', 'regex:/^[A-Za-z\s]+$/u'],
            'descripcion_corta' => 'nullable|string|max:255',
            'descripcion_larga' => 'nullable|string',
            'precioDolares' => 'required|numeric|min:0.01',
            'precioPesos' => 'required|numeric|min:0.01',
            'imagen' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'fecha_vigencia' => 'nullable|date',
            'activo' => 'required|boolean',
        ]
        ,[
            'nombre.regex' => 'El nombre solo puede contener letras sin acentos.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $producto->update($request->all());

        return response()->json(['message' => 'Producto actualizado correctamente', 'producto' => $producto]);
    }

    public function destroy(Request $request)
    {
        $producto = Producto::findOrFail($request->id);
        $producto->delete();

        return response()->json(['message' => 'Producto eliminado correctamente']);
    }

    public function getProductos(Request $r){
        $productos = Producto::when($r->activo != 'x', function ($q) use ($r) {
                return $q->where('activo', $r->activo);
            })->get();
        return response()->json(['data' => $productos]);
    }
    public function activate(Request $request){
        $producto = Producto::findOrFail($request->id);
        $producto->activo = $request->tipo;
        $producto->save();

        return response()->json(['message' => 'Producto procesado correctamente']);
    }
}
