<?php

namespace App\Http\Controllers;

use App\Models\Rifa;
use App\Models\Boleto;
use App\Models\Carrito;
use App\Models\CarritoItem;
use App\Models\CarritoItemBoleto;
use Illuminate\Http\Request;
use App\Models\Venta;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class RifaController extends Controller
{
    public function index()
    {
        // Obtener todas las rifas activas paginadas
        $rifas = Rifa::where('estado', 'activa')
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('rifas.index', compact('rifas'));
    }

    public function show($id)
    {
        // Obtener la rifa específica con sus relaciones
        $rifa = Rifa::findOrFail($id);

        // Obtener los boletos disponibles para esta rifa
        $boletosDisponibles = Boleto::where('rifa_id', $id)
            ->where('estado', 'disponible')
            ->count();

        return view('rifas.show', compact('rifa', 'boletosDisponibles'));
    }

    /**
     * Muestra la vista para seleccionar boletos
     */
    public function seleccionarBoletos($id)
    {
        // Obtener la rifa
        $rifa = Rifa::findOrFail($id);

        // Verificar que la rifa esté activa
        if ($rifa->estado !== 'activa') {
            return redirect()->route('rifas.show', $id)
                ->with('error', 'Esta rifa no está disponible para la compra de boletos.');
        }

        // Obtener todos los boletos de esta rifa
        $boletos = $rifa->boletos()->get();

        // Si no hay boletos, crear registro ficticio para generar números
        if ($boletos->isEmpty()) {
            \Log::info("No se encontraron boletos para la rifa ID: {$id}. Se utilizará un valor predeterminado.");

            // Asegurarse de que total_boletos esté definido
            if (empty($rifa->total_boletos) || !is_numeric($rifa->total_boletos) || $rifa->total_boletos <= 0) {
                $rifa->total_boletos = $rifa->boletos_totales ?? 100;
            }
        }

        return view('rifas.seleccionar-boletos', compact('rifa', 'boletos'));
    }

    /**
     * Retorna los boletos disponibles de una rifa como JSON
     */
    public function boletosDisponibles($id)
    {
        $rifa = Rifa::findOrFail($id);

        // Obtener el teléfono del usuario de la sesión si existe
        $telefonoCliente = Session::get('telefono_cliente');

        // Obtener los números de boletos disponibles
        // Si hay teléfono en sesión, incluir también los que están reservados por este teléfono
        $query = Boleto::where('rifa_id', $id)
            ->where(function($query) use ($telefonoCliente) {
                $query->where('estado', 'disponible');

                // Si hay teléfono en la sesión, incluir los boletos reservados por este teléfono
                if ($telefonoCliente) {
                    $query->orWhere(function($q) use ($telefonoCliente) {
                        $q->where('estado', 'reservado_temp')
                          ->where('telefono_reserva', $telefonoCliente)
                          ->where('reservado_hasta', '>', now());
                    });
                }
            });

        $boletosDisponibles = $query->pluck('numero')->toArray();

        return response()->json($boletosDisponibles);
    }

    /**
     * Procesa la selección de boletos y los agrega al carrito
     */
    public function comprarBoletos(Request $request, $id)
    {
        $request->validate([
            'boletos_seleccionados' => 'required|json',
            'telefono' => 'required|string|min:10',
        ]);

        $rifa = Rifa::findOrFail($id);
        $boletosSeleccionados = json_decode($request->boletos_seleccionados);
        $telefono = $request->telefono;

        if (empty($boletosSeleccionados)) {
            return redirect()->back()->with('error', 'No has seleccionado ningún boleto.');
        }

        // Verificar que todos los boletos estén disponibles o ya estén reservados por este teléfono
        $boletosDisponibles = Boleto::where('rifa_id', $id)
            ->where(function($query) use ($telefono) {
                $query->where('estado', 'disponible')
                      ->orWhere(function($q) use ($telefono) {
                          $q->where('estado', 'reservado_temp')
                            ->where('telefono_reserva', $telefono)
                            ->where('reservado_hasta', '>', now());
                      });
            })
            ->whereIn('numero', $boletosSeleccionados)
            ->count();

        // Verificar que todos los boletos seleccionados estén disponibles o sean del mismo teléfono
        if ($boletosDisponibles !== count($boletosSeleccionados)) {
            return redirect()->back()->with('error', 'Algunos boletos seleccionados ya no están disponibles o están reservados por otro usuario.');
        }

        try {
            DB::beginTransaction();

            // Obtener o crear el carrito para este teléfono
            $carrito = Carrito::obtenerCarritoParaTelefono($telefono);

            // Guardar el token del carrito en la sesión
            Session::put('carrito_token', $carrito->token);

            // Guardar el teléfono del usuario en la sesión para futura referencia
            Session::put('telefono_cliente', $telefono);

            // Crear o actualizar el item del carrito para esta rifa
            $carritoItem = CarritoItem::firstOrNew([
                'carrito_id' => $carrito->id,
                'rifa_id' => $rifa->id,
            ]);

            $carritoItem->precio_unitario = $rifa->precio_boleto;
            $carritoItem->save();

            // Agregar los boletos seleccionados al item del carrito
            foreach ($boletosSeleccionados as $numeroBoleto) {
                CarritoItemBoleto::create([
                    'carrito_item_id' => $carritoItem->id,
                    'numero_boleto' => $numeroBoleto,
                ]);
            }

            // Marcar los boletos como reservados temporalmente
            Boleto::where('rifa_id', $id)
                ->whereIn('numero', $boletosSeleccionados)
                ->update([
                    'estado' => 'reservado_temp',
                    'reservado_hasta' => Carbon::now()->addHours(1), // Reserva temporal por 1 hora
                    'telefono_reserva' => $telefono, // Guardar el teléfono que hizo la reserva
                ]);

            DB::commit();

            // Redirigir al carrito
            return redirect()->route('carrito.mostrar', ['token' => $carrito->token])
                ->with('success', 'Boletos agregados al carrito. Tienes 1 hora para completar tu compra.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al procesar tu selección. Por favor, inténtalo de nuevo.');
        }
    }

    /**
     * Procesa la compra de boletos seleccionados
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function procesarCompra(Request $request)
    {
        // Validar la solicitud
        $validated = $request->validate([
            'rifa_id' => 'required|exists:rifas,id',
            'boletos' => 'required|string',
        ]);

        // Decodificar el JSON de boletos
        $boletosSeleccionados = json_decode($validated['boletos']);

        // Obtener la rifa
        $rifa = Rifa::findOrFail($validated['rifa_id']);

        // Verificar que los boletos están disponibles
        $boletosVendidos = $rifa->boletos_vendidos_array ?? [];
        $boletosNoDisponibles = array_intersect($boletosSeleccionados, $boletosVendidos);

        if (count($boletosNoDisponibles) > 0) {
            return redirect()->back()->with('error', 'Algunos boletos ya no están disponibles. Por favor, seleccione otros.');
        }

        // Calcular el total
        $total = count($boletosSeleccionados) * $rifa->precio;

        // Guardar los datos en la sesión para el proceso de pago
        session([
            'compra_boletos' => [
                'rifa_id' => $rifa->id,
                'boletos' => $boletosSeleccionados,
                'total' => $total,
                'timestamp' => now(),
            ]
        ]);

        // Redireccionar a la página de pago
        return redirect()->route('checkout.index');
    }

    /**
     * Muestra la página de confirmación de compra
     */
    public function confirmarCompra($ventaId)
    {
        $venta = Venta::with(['boletos.rifa'])->findOrFail($ventaId);

        return view('rifas.confirmar-compra', compact('venta'));
    }
}
