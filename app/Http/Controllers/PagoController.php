<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Boleto;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmacionCompra;
use Illuminate\Support\Str;
use App\Models\DetalleCompra;

class PagoController extends Controller
{
    public function mostrarFormularioTarjeta()
    {
        return view('pagos.tarjeta');
    }

    public function redirigirPaypal()
    {
        // Implementar lógica de redirección a PayPal
        return view('pagos.paypal');
    }

    public function generarReferenciaOxxo()
    {
        // Implementar generación de referencia para OXXO
        $referencia = 'OXXO-' . Str::random(8);
        return view('pagos.oxxo', compact('referencia'));
    }

    public function mostrarDatosBancarios()
    {
        // Mostrar datos para transferencia
        return view('pagos.transferencia');
    }

    public function completarPago(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'email' => 'required|email',
            'telefono' => 'required|string|max:20',
            'metodo_pago' => 'required|in:tarjeta,paypal,oxxo,transferencia',
        ]);

        // Obtener el carrito de compras
        $carrito = Session::get('carrito', []);

        if (empty($carrito)) {
            return redirect()->route('carrito.mostrar')->with('error', 'No hay boletos en el carrito');
        }

        // Buscar o crear cliente
        $cliente = Cliente::firstOrCreate(
            ['email' => $request->email],
            [
                'nombre' => $request->nombre_completo,
                'telefono' => $request->telefono
            ]
        );

        // Calcular total
        $total = 0;
        foreach ($carrito as $item) {
            $total += $item['precio'];
        }

        // Crear venta
        $venta = new Venta();
        $venta->cliente_id = $cliente->id;
        $venta->total = $total;
        $venta->metodo_pago = $request->metodo_pago;
        $venta->estado = 'completada';  // o 'pendiente' dependiendo del método de pago
        $venta->referencia_pago = Str::uuid();
        $venta->save();

        // Actualizar boletos comprados
        foreach ($carrito as $item) {
            $boleto = Boleto::find($item['id']);
            if ($boleto) {
                $boleto->estado = 'vendido';
                $boleto->cliente_id = $cliente->id;
                $boleto->venta_id = $venta->id;
                $boleto->save();
            }
        }

        // Enviar correo de confirmación
        try {
            Mail::to($cliente->email)->send(new ConfirmacionCompra($venta));
        } catch (\Exception $e) {
            // Manejar el error pero continuar
            \Log::error("Error enviando correo: " . $e->getMessage());
        }

        // Limpiar carrito
        Session::forget('carrito');

        return redirect()->route('pagos.confirmacion', $venta->id)->with('success', '¡Tu compra se ha realizado con éxito!');
    }

    public function confirmacionCompra(Venta $venta)
    {
        // Verificar que la venta pertenezca al cliente actual
        return view('pagos.confirmacion', compact('venta'));
    }

    public function mostrarPagoOxxo()
    {
        $datosPago = Session::get('datos_pago');
        $carrito = Session::get('carrito', []);

        if (empty($datosPago) || empty($carrito)) {
            return redirect()->route('home');
        }

        $referencia = strtoupper(Str::random(12));
        $cliente = Cliente::find($datosPago['cliente_id']);
        $total = $datosPago['total'];

        return view('pagos.oxxo', compact('referencia', 'cliente', 'total', 'carrito'));
    }

    public function mostrarPagoTransferencia()
    {
        $datosPago = Session::get('datos_pago');
        $carrito = Session::get('carrito', []);

        if (empty($datosPago) || empty($carrito)) {
            return redirect()->route('home');
        }

        $referencia = strtoupper(Str::random(12));
        $cliente = Cliente::find($datosPago['cliente_id']);
        $total = $datosPago['total'];

        return view('pagos.transferencia', compact('referencia', 'cliente', 'total', 'carrito'));
    }

    public function procesarPagoTarjeta()
    {
        // Aquí integrarías con tu pasarela de pago
        // Para este ejemplo, simularemos un pago exitoso

        return $this->finalizarCompra('tarjeta', true);
    }

    public function procesarPagoPaypal()
    {
        // Aquí integrarías con PayPal
        // Para este ejemplo, simularemos un pago exitoso

        return $this->finalizarCompra('paypal', true);
    }

    protected function finalizarCompra($metodoPago, $exitoso = true)
    {
        if (!$exitoso) {
            return redirect()->route('carrito.mostrar')->with('error', 'El pago no pudo procesarse. Intente nuevamente.');
        }

        $datosPago = Session::get('datos_pago');
        $carrito = Session::get('carrito', []);

        if (empty($datosPago) || empty($carrito)) {
            return redirect()->route('home');
        }

        $cliente = Cliente::find($datosPago['cliente_id']);

        // Crear compra
        $compra = new Venta();
        $compra->cliente_id = $cliente->id;
        $compra->total = $datosPago['total'];
        $compra->metodo_pago = $metodoPago;
        $compra->estado = ($metodoPago == 'oxxo' || $metodoPago == 'transferencia') ? 'pendiente' : 'pagado';
        $compra->referencia = strtoupper(Str::random(12));
        $compra->save();

        // Crear detalles y actualizar boletos
        foreach ($carrito as $item) {
            $boleto = Boleto::find($item['id']);

            if ($boleto) {
                // Crear detalle
                $detalle = new DetalleCompra();
                $detalle->venta_id = $compra->id;
                $detalle->boleto_id = $boleto->id;
                $detalle->precio = $item['precio'];
                $detalle->save();

                // Actualizar boleto
                $boleto->estado = ($metodoPago == 'oxxo' || $metodoPago == 'transferencia') ? 'reservado' : 'vendido';
                $boleto->cliente_id = $cliente->id;
                $boleto->save();
            }
        }

        // Limpiar carrito y datos de pago
        Session::forget('carrito');
        Session::forget('datos_pago');

        // Guardar referencia de compra para la página de confirmación
        Session::put('compra_finalizada', $compra->id);

        return redirect()->route('pago.confirmacion');
    }

    public function confirmacionPago()
    {
        $compraId = Session::get('compra_finalizada');

        if (!$compraId) {
            return redirect()->route('home');
        }

        $compra = Venta::with(['detalles.boleto.rifa', 'cliente'])->find($compraId);
        Session::forget('compra_finalizada');

        return view('pagos.confirmacion', compact('compra'));
    }
}
