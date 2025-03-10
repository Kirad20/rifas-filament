<?php

namespace App\Filament\Resources\RifaResource\Pages;

use App\Filament\Resources\RifaResource;
use App\Models\Boleto;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateRifa extends CreateRecord
{
    protected static string $resource = RifaResource::class;

    protected function afterCreate(): void
    {
        $rifa = $this->record;
        $batchSize = 500;
        $totalBoletos = $rifa->total_boletos;

        for ($batch = 0; $batch < $totalBoletos; $batch += $batchSize) {
            $boletosACrear = collect(range($batch + 1, min($batch + $batchSize, $totalBoletos)))
                ->map(fn($numero) => [
                    'rifa_id' => $rifa->id,
                    'numero' => $numero,
                    'estado' => 'disponible',
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
                ->toArray();

            Boleto::insert($boletosACrear);
        }

        Notification::make()
            ->title('Boletos creados exitosamente')
            ->body("Se han creado {$totalBoletos} boletos para esta rifa.")
            ->success()
            ->send();
    }
}
