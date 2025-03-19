<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Boleto;
use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LiberarBoletosExpirados extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'boletos:liberar-expirados';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Libera boletos cuya reserva ha expirado';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando proceso de liberación de boletos expirados...');

        // Obtener la fecha y hora actual
        $ahora = Carbon::now();

        try {
            DB::beginTransaction();

            // Primero, identificamos los boletos con reservación expirada
            $boletosExpirados = Boleto::where('estado', 'reservado')
                ->where('reservado_hasta', '<', $ahora)
                ->get();

            if ($boletosExpirados->count() > 0) {
                // Agrupar los boletos por venta_id para actualizar el estado de las ventas correspondientes
                $ventasIds = $boletosExpirados->pluck('venta_id')->unique()->filter()->values();

                // Actualizar estado de ventas a expiradas
                if ($ventasIds->count() > 0) {
                    Venta::whereIn('id', $ventasIds)
                        ->where('estado', 'pendiente')
                        ->update(['estado' => 'expirada']);
                }

                // Procesar los boletos expirados
                foreach ($boletosExpirados as $boleto) {
                    // Decrementar contador de boletos vendidos en la rifa
                    if ($boleto->rifa) {
                        $boleto->rifa->decrement('boletos_vendidos');
                    }

                    // Actualizar estado del boleto
                    $boleto->update([
                        'estado' => 'disponible',
                        'venta_id' => null,
                        'reservado_hasta' => null
                    ]);
                }

                DB::commit();

                $this->info("Se liberaron {$boletosExpirados->count()} boletos de " . $ventasIds->count() . " ventas expiradas.");
            } else {
                $this->info('No hay boletos por liberar en este momento.');
                DB::commit();
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Error al liberar boletos: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
