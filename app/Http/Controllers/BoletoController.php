<?php

namespace App\Http\Controllers;

use App\Models\Rifa;
use App\Models\Boleto;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BoletoController extends Controller
{
    public function seleccionarBoletos(Rifa $rifa)
    {
        // Obtenemos todos los boletos disponibles de esta rifa
        $boletosDisponibles = Boleto::where('rifa_id', $rifa->id)
            ->where('estado', 'disponible')
            ->orderBy('numero')
            ->get();

        // Obtener carrito actual para mostrar contador
        $carrito = Session::get('carrito', []);

        // Verificar si hay boletos en el carrito que ya no están disponibles
        if (!empty($carrito)) {
            $boletosDisponiblesIds = $boletosDisponibles->pluck('id')->toArray();
            foreach ($carrito as $index => $item) {
                if (!in_array($item['id'], $boletosDisponiblesIds)) {
                    unset($carrito[$index]);
                }
            }
            $carrito = array_values($carrito); // Reindexar el array
            Session::put('carrito', $carrito);
        }

        $totalEnCarrito = count($carrito);

        return view('boletos.seleccionar', compact('rifa', 'boletosDisponibles', 'totalEnCarrito'));
    }

    public function agregarAlCarrito(Request $request)
    {
        $validated = $request->validate([
            'rifa_id' => 'required|exists:rifas,id',
            'boletos' => 'required|array',
            'boletos.*' => 'required|integer|exists:boletos,id'
        ]);

        $carrito = Session::get('carrito', []);

        foreach ($request->boletos as $boletoId) {
            $boleto = Boleto::find($boletoId);

            if ($boleto && $boleto->estado === 'disponible') {
                $carrito[] = [
                    'id' => $boletoId,
                    'numero' => $boleto->numero,
                    'rifa_id' => $boleto->rifa_id,
                    'rifa_nombre' => $boleto->rifa->nombre,
                    'precio' => $boleto->rifa->precio_boleto
                ];

                // Cambiar estado temporalmente a reservado
                $boleto->estado = 'reservado';
                $boleto->reservado_hasta = now()->addMinutes(15);
                $boleto->save();
            }
        }

        Session::put('carrito', $carrito);

        return redirect()->route('carrito.mostrar')->with('success', 'Boletos agregados al carrito correctamente');
    }

    public function verCarrito()
    {
        $carrito = Session::get('carrito', []);

        // Verificar y eliminar boletos reservados que ya no están disponibles
        foreach ($carrito as $index => $item) {
            $boleto = Boleto::find($item['id']);
            if (!$boleto || $boleto->estado !== 'disponible') {
                unset($carrito[$index]);
            }
        }
        $carrito = array_values($carrito); // Reindexar el array
        Session::put('carrito', $carrito);
        $total = array_sum(array_column($carrito, 'precio'));

        return view('boletos.carrito', compact('carrito', 'total'));
    }

    public function eliminarDelCarrito($index)
    {
        $carrito = Session::get('carrito', []);

        if (isset($carrito[$index])) {
            $boletoId = $carrito[$index]['id'];

            // Devolver el boleto a disponible
            $boleto = Boleto::find($boletoId);
            if ($boleto) {
                $boleto->estado = 'disponible';
                $boleto->reservado_hasta = null;
                $boleto->save();
            }

            // Eliminar del carrito
            unset($carrito[$index]);
            $carrito = array_values($carrito); // Reindexar el array

            Session::put('carrito', $carrito);
        }

        return redirect()->route('carrito.mostrar')->with('success', 'Boleto eliminado del carrito');
    }

    public function procesarPago(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefono' => 'required|string|max:20',
            'metodo_pago' => 'required|in:tarjeta,paypal,oxxo,transferencia'
        ]);

        $carrito = Session::get('carrito', []);

        if (empty($carrito)) {
            return redirect()->route('home')->with('error', 'Tu carrito está vacío');
        }

        // Crear o actualizar cliente
        $cliente = Cliente::updateOrCreate(
            ['correo' => $request->email],
            [
                'nombre' => $request->nombre,
                'telefono' => $request->telefono
            ]
        );

        // Según método de pago, redirigir
        $metodoPago = $request->metodo_pago;

        // Guardar datos del pago en sesión
        Session::put('datos_pago', [
            'cliente_id' => $cliente->id,
            'metodo' => $metodoPago,
            'total' => array_sum(array_column($carrito, 'precio'))
        ]);

        // Redirigir según método de pago
        switch ($metodoPago) {
            case 'oxxo':
                return redirect()->route('pago.oxxo');
            case 'transferencia':
                return redirect()->route('pago.transferencia');
            case 'tarjeta':
                return redirect()->route('pago.tarjeta');
            case 'paypal':
                return redirect()->route('pago.paypal');
            default:
                return redirect()->route('carrito.mostrar')->with('error', 'Método de pago no válido');
        }
    }
}
