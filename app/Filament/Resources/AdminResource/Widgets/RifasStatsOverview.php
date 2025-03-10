<?php

namespace App\Filament\Widgets;

use App\Models\Boleto;
use App\Models\Cliente;
use App\Models\Rifa;
use App\Models\Venta;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RifasStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Rifas', Rifa::count())
                ->description('Rifas registradas')
                ->descriptionIcon('heroicon-m-ticket')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),

            Stat::make('Boletos Vendidos', Boleto::where('estado', 'vendido')->count())
                ->description('Del total de boletos')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('info'),

            Stat::make('Ventas Totales', '$' . number_format(Venta::where('estado_pago', 'completado')->sum('monto'), 2))
                ->description('Ingresos por ventas')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('warning'),
        ];
    }
}
