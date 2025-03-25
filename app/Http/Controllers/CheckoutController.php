<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rifa;

class CheckoutController extends Controller
{
    /**
     * Muestra la página de checkout para finalizar la compra
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Verificar si hay datos de compra en la sesión
        if (!session()->has('compra_boletos')) {
            return redirect()->route('rifas.index')->with('error', 'No hay una compra en proceso');
        }

        $compra = session('compra_boletos');

        // Verificar que los datos de compra no están expirados (30 minutos)
        if (now()->diffInMinutes($compra['timestamp']) > 30) {
            session()->forget('compra_boletos');
            return redirect()->route('rifas.index')->with('error', 'La sesión de compra ha expirado');
        }

        // Obtener la rifa
        $rifa = Rifa::findOrFail($compra['rifa_id']);

        return view('checkout.index', [
            'rifa' => $rifa,
            'boletos' => $compra['boletos'],
            'total' => $compra['total']
        ]);
    }
}
