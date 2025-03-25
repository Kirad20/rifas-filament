<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RifaController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\CartController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Ruta principal (landing page)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rutas de rifas
Route::get('/rifas', [RifaController::class, 'index'])->name('rifas.index');
Route::get('/rifas/{id}', [RifaController::class, 'show'])->name('rifas.show');
Route::get('/rifas/{id}/seleccionar-boletos', [RifaController::class, 'seleccionarBoletos'])->name('rifas.seleccionar-boletos');
Route::get('/rifas/{id}/boletos-disponibles', [RifaController::class, 'boletosDisponibles'])->name('rifas.boletos.disponibles');
Route::post('/rifas/{id}/comprar', [RifaController::class, 'comprarBoletos'])->name('rifas.comprar');
Route::get('/rifas/confirmar-compra/{venta}', [RifaController::class, 'confirmarCompra'])->name('rifas.confirmar-compra');

// Ruta para procesar la compra de boletos
Route::post('/rifas/procesar-compra', [RifaController::class, 'procesarCompra'])->name('rifas.procesar-compra');

// Rutas de carrito
Route::get('/carrito/{token}', [CartController::class, 'mostrar'])->name('carrito.mostrar');
Route::get('/carrito/{token}/eliminar/{itemId}/{numeroBoleto}', [CartController::class, 'eliminarBoleto'])->name('carrito.eliminar-boleto');
Route::get('/carrito/{token}/finalizar', [CartController::class, 'finalizarForm'])->name('carrito.finalizar.form');
Route::post('/carrito/{token}/finalizar', [CartController::class, 'finalizar'])->name('carrito.finalizar');

// Ruta para la página de checkout
Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');

// Rutas de contacto
Route::get('/contacto', [ContactoController::class, 'index'])->name('contacto');
Route::post('/contacto', [ContactoController::class, 'enviar'])->name('contacto.enviar');

// Rutas de páginas estáticas
Route::view('/terminos', 'static.terminos')->name('terminos');
Route::view('/privacidad', 'static.privacidad')->name('privacidad');
Route::view('/preguntas-frecuentes', 'static.faq')->name('faq');
Route::view('/ganadores', 'static.ganadores')->name('ganadores');

