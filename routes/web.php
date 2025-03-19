<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RifaController;
use App\Http\Controllers\ContactoController;

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

// Rutas de contacto
Route::get('/contacto', [ContactoController::class, 'index'])->name('contacto');
Route::post('/contacto', [ContactoController::class, 'enviar'])->name('contacto.enviar');

// Rutas de páginas estáticas
Route::view('/terminos', 'static.terminos')->name('terminos');
Route::view('/privacidad', 'static.privacidad')->name('privacidad');
Route::view('/preguntas-frecuentes', 'static.faq')->name('faq');
Route::view('/ganadores', 'static.ganadores')->name('ganadores');

