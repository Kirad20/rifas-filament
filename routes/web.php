<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\BoletoController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\RifaController;
use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/rifas', [HomeController::class, 'listarRifas'])->name('rifas');
Route::get('/rifas/{rifa}', [HomeController::class, 'mostrarRifa'])->name('rifas.show');
Route::get('/contacto', [ContactController::class, 'index'])->name('contacto');
Route::get('/rifas/filtro', [RifaController::class, 'filter'])->name('rifas.filter');

// Rutas para boletos
Route::get('/boletos/seleccionar/{rifa}', [BoletoController::class, 'seleccionarBoletos'])->name('boletos.seleccionar');
Route::post('/boletos/agregar-carrito', [BoletoController::class, 'agregarAlCarrito'])->name('boletos.agregar-carrito');

// Rutas para carrito
Route::get('/carrito', [BoletoController::class, 'verCarrito'])->name('carrito.mostrar');
Route::delete('/carrito/eliminar/{index}', [BoletoController::class, 'eliminarDelCarrito'])->name('carrito.eliminar');
Route::post('/carrito/procesar-pago', [BoletoController::class, 'procesarPago'])->name('carrito.procesar-pago');

// Rutas para pagos
Route::get('/pago/oxxo', [PagoController::class, 'mostrarPagoOxxo'])->name('pago.oxxo');
Route::get('/pago/transferencia', [PagoController::class, 'mostrarPagoTransferencia'])->name('pago.transferencia');
Route::get('/pago/tarjeta', [PagoController::class, 'procesarPagoTarjeta'])->name('pago.tarjeta');
Route::get('/pago/paypal', [PagoController::class, 'procesarPagoPaypal'])->name('pago.paypal');
Route::get('/pago/confirmacion', [PagoController::class, 'confirmacionPago'])->name('pago.confirmacion');
