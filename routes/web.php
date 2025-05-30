<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\PrepedidoController;
use App\Http\Controllers\DashboardController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/pedidos');
});
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('google.callback');



 Route::get('/pedidos', [PrepedidoController::class, 'index'])->name('prepedido.index');
 Route::post('/show', [ProductoController::class, 'show'])->name('productos.show');
 Route::post('/crear_pedido', [PrepedidoController::class, 'store'])->name('prepedido.store');
 
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
    Route::post('/get_productos', [ProductoController::class, 'getProductos'])->name('productos.get');
    Route::post('/store', [ProductoController::class, 'store'])->name('productos.store');
    Route::post('/update', [ProductoController::class, 'update'])->name('productos.update');
    Route::post('/delete', [ProductoController::class, 'destroy'])->name('productos.destroy');
    
    Route::post('/activar', [ProductoController::class, 'activate'])->name('productos.activar');
    Route::get('/lista-prepedidos', [PrepedidoController::class, 'lista'])->name('prepedido.lista');
    Route::post('/get_prepedidos', [PrepedidoController::class, 'get_lista'])->name('prepedido.store');
    Route::post('/get_prepedido', [PrepedidoController::class, 'get'])->name('prepedido.get');
    
});

require __DIR__.'/auth.php';
