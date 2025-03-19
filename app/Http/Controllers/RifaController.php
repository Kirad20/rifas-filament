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
        $limite_boletos = 10;
        // Obtener la rifa
        $rifa = Rifa::findOrFail($id);

        // Verificar que la rifa esté activa
        if ($rifa->estado !== 'activa') {
            return redirect()->route('rifas.show', $id)
                ->with('error', 'Esta rifa no está disponible para la compra de boletos.');
        }

        $boletos = Boleto::where('rifa_id', $id)->get();

        $disponibles = $boletos->where('estado', 'disponible');

        $total = $disponibles->count();

        return view('rifas.partials.seleccion.index', compact('disponibles', 'rifa', 'total', 'boletos', 'limite_boletos'));
    }

    /**
     * Retorna los boletos disponibles de una rifa como JSON
     */
    public function boletosDisponibles($id)
    {
        $rifa = Rifa::findOrFail($id);

        // Obtener los números de boletos disponibles
        $boletosDisponibles = Boleto::where('rifa_id', $id)
            ->where('estado', 'disponible')
            ->pluck('numero')
            ->toArray();

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

        // Verificar que todos los boletos estén disponibles
        $boletosDisponibles = Boleto::where('rifa_id', $id)
            ->where('estado', 'disponible')
            ->whereIn('numero', $boletosSeleccionados)
            ->count();

        if ($boletosDisponibles !== count($boletosSeleccionados)) {
            return redirect()->back()->with('error', 'Algunos boletos seleccionados ya no están disponibles.');
        }

        try {
            DB::beginTransaction();

            // Obtener o crear el carrito para este teléfono
            $carrito = Carrito::obtenerCarritoParaTelefono($telefono);

            // Guardar el token del carrito en la sesión
            Session::put('carrito_token', $carrito->token);

            // Crear o actualizar el item del carrito para esta rifa
            $carritoItem = CarritoItem::firstOrNew([
                'carrito_id' => $carrito->id,
                'rifa_id' => $rifa->id,
            ]);

            $carritoItem->precio_unitario = $rifa->precio;
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
     * Muestra la página de confirmación de compra
     */
    public function confirmarCompra($ventaId)
    {
        $venta = Venta::with(['boletos.rifa'])->findOrFail($ventaId);

        return view('rifas.confirmar-compra', compact('venta'));
    }
}
