<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RifaResource\Pages;
use App\Models\Boleto;
use App\Models\Rifa;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RifaResource extends Resource
{
    protected static ?string $model = Rifa::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationGroup = 'Gestión de Rifas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('descripcion')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\DateTimePicker::make('fecha_sorteo')
                    ->required(),

                Forms\Components\TextInput::make('precio_boleto')
                    ->required()
                    ->numeric()
                    ->prefix('$'),

                Forms\Components\TextInput::make('total_boletos')
                    ->required()
                    ->numeric()
                    ->integer(),

                Forms\Components\TextInput::make('premio')
                    ->required()
                    ->maxLength(255),

                SpatieMediaLibraryFileUpload::make('fotos')
                    ->multiple(true)
                    ->conversion('thumb')
                    ->reorderable()
                    ->collection('fotos')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp']),

                Forms\Components\Select::make('estado')
                    ->options([
                        'activa' => 'Activa',
                        'finalizada' => 'Finalizada',
                        'cancelada' => 'Cancelada',
                    ])
                    ->default('activa')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),

                Tables\Columns\TextColumn::make('fecha_sorteo')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('precio_boleto')
                    ->money('MXN')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_boletos')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('estado')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'activa' => 'success',
                        'finalizada' => 'info',
                        'cancelada' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        'activa' => 'Activa',
                        'finalizada' => 'Finalizada',
                        'cancelada' => 'Cancelada',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRifas::route('/'),
            'create' => Pages\CreateRifa::route('/create'),
            'edit' => Pages\EditRifa::route('/{record}/edit'),
        ];
    }
}
