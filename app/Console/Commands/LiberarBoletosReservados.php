<?php

namespace App\Console\Commands;

use App\Models\Boleto;
use Carbon\Carbon;
use Illuminate\Console\Command;

class LiberarBoletosReservados extends Command
{
    /**
     * El nombre y la firma del comando en la consola.
     *
     * @var string
     */
    protected $signature = 'boletos:liberar';

    /**
     * La descripción del comando en la consola.
     *
     * @var string
     */
    protected $description = 'Libera los boletos reservados cuyo tiempo de reserva ha expirado';

    /**
     * Ejecuta el comando.
     */
    public function handle()
    {
        $ahora = Carbon::now();

        // Buscar todos los boletos reservados cuya fecha de reserva ha expirado
        $boletosExpirados = Boleto::where('estado', 'reservado')
            ->where('reservado_hasta', '<', $ahora)
            ->get();

        $total = $boletosExpirados->count();

        if ($total > 0) {
            foreach ($boletosExpirados as $boleto) {
                $boleto->estado = 'disponible';
                $boleto->reservado_hasta = null;
                $boleto->save();
            }

            $this->info("Se han liberado {$total} boletos reservados expirados.");
        } else {
            $this->info('No hay boletos reservados expirados para liberar.');
        }

        return 0;
    }
}
