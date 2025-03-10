<?php

namespace App\Filament\Resources\BoletoResource\Pages;

use App\Filament\Resources\BoletoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBoletos extends ListRecords
{
    protected static string $resource = BoletoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
