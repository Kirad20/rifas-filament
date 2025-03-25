<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\User;
use App\Models\Venta;
use App\Models\Boleto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CartController extends Controller
{
    /**
     * Muestra el contenido del carrito
     */
    public function mostrar(Request $request, $token)
    {
        $carrito = Carrito::where('token', $token)->firstOrFail();

        return view('carrito.mostrar', compact('carrito'));
    }

    /**
     * Elimina un boleto del carrito
     */
    public function eliminarBoleto(Request $request, $token, $itemId, $numeroBoleto)
    {
        $carrito = Carrito::where('token', $token)->firstOrFail();

        $item = $carrito->items()->findOrFail($itemId);

        // Eliminar el boleto
        $item->boletos()->where('numero_boleto', $numeroBoleto)->delete();

        // Si no quedan boletos en este item, eliminar el item
        if ($item->boletos()->count() == 0) {
            $item->delete();
        }

        // Liberar el boleto reservado temporalmente
        Boleto::where('rifa_id', $item->rifa_id)
            ->where('numero', $numeroBoleto)
            ->update(['estado' => 'disponible', 'reservado_hasta' => null]);

        return redirect()->route('carrito.mostrar', ['token' => $token])
            ->with('success', 'Boleto eliminado del carrito');
    }

    /**
     * Formulario para finalizar la compra
     */
    public function finalizarForm(Request $request, $token)
    {
        $carrito = Carrito::where('token', $token)
                        ->with(['items.rifa', 'items.boletos'])
                        ->firstOrFail();

        if ($carrito->items->isEmpty()) {
            return redirect()->route('carrito.mostrar', ['token' => $token])
                ->with('error', 'Tu carrito está vacío');
        }

        return view('carrito.finalizar', compact('carrito'));
    }

    /**
     * Procesa la finalización de la compra
     */
    public function finalizar(Request $request, $token)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefono' => 'required|string|min:10|max:15',
        ]);

        $carrito = Carrito::where('token', $token)
                        ->with(['items.rifa', 'items.boletos'])
                        ->firstOrFail();

        if ($carrito->items->isEmpty()) {
            return redirect()->route('carrito.mostrar', ['token' => $token])
                ->with('error', 'Tu carrito está vacío');
        }

        try {
            DB::beginTransaction();

            // Buscar si ya existe un usuario con este email o teléfono
            $user = User::where('email', $request->email)
                        ->orWhere('telefono', $request->telefono)
                        ->first();

            // Si no existe, crear un nuevo usuario
            if (!$user) {
                $password = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 0, 8);

                $user = User::create([
                    'name' => $request->nombre,
                    'email' => $request->email,
                    'telefono' => $request->telefono,
                    'password' => Hash::make($password),
                ]);

                // TODO: Enviar email con la contraseña generada
            }

            // Vincular el carrito al usuario si no está ya vinculado
            if (!$carrito->user_id) {
                $carrito->user_id = $user->id;
                $carrito->save();
            }

            // Crear una venta
            $fechaExpiracion = Carbon::now()->addHours(48);

            $venta = Venta::create([
                'user_id' => $user->id,
                'total' => $carrito->getTotal(),
                'estado' => 'pendiente',
                'fecha_expiracion' => $fechaExpiracion,
                'nombre_cliente' => $request->nombre,
                'email_cliente' => $request->email,
                'telefono_cliente' => $request->telefono,
            ]);

            // Procesar cada item del carrito
            foreach ($carrito->items as $item) {
                foreach ($item->boletos as $boletoBolsa) {
                    // Actualizar el boleto real
                    $boleto = Boleto::where('rifa_id', $item->rifa_id)
                                  ->where('numero', $boletoBolsa->numero_boleto)
                                  ->first();

                    if ($boleto && ($boleto->estado == 'disponible' || $boleto->estado == 'reservado_temp')) {
                        $boleto->update([
                            'estado' => 'reservado',
                            'venta_id' => $venta->id,
                            'reservado_hasta' => $fechaExpiracion
                        ]);

                        // Incrementar boletos vendidos en la rifa
                        $item->rifa->increment('boletos_vendidos');
                    }
                }
            }

            // Vaciar el carrito después de procesar la venta
            $carrito->items()->delete();

            // Iniciar sesión automáticamente
            Auth::login($user);

            DB::commit();

            return redirect()->route('rifas.confirmar-compra', $venta->id)
                ->with('success', 'Tu compra ha sido procesada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al procesar tu compra: ' . $e->getMessage());
        }
    }
}
