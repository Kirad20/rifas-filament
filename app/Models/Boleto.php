<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Boleto extends Model
{
    protected $fillable = [
        'rifa_id',
        'numero',
        'estado',
        'cliente_id',
        'reservado_hasta'
    ];

    protected $casts = [
        'reservado_hasta' => 'datetime',
    ];

    public function rifa(): BelongsTo
    {
        return $this->belongsTo(Rifa::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function detalleCompra(): HasOne
    {
        return $this->hasOne(DetalleCompra::class);
    }

    // Método para obtener la venta asociada a través del detalle
    public function venta()
    {
        return $this->detalleCompra ? $this->detalleCompra->compra : null;
    }
}
