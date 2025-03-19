<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarritoItem extends Model
{
    use HasFactory;

    protected $table = 'carrito_items';

    protected $fillable = [
        'carrito_id',
        'rifa_id',
        'precio_unitario',
    ];

    /**
     * Relación con el carrito
     */
    public function carrito(): BelongsTo
    {
        return $this->belongsTo(Carrito::class);
    }

    /**
     * Relación con la rifa
     */
    public function rifa(): BelongsTo
    {
        return $this->belongsTo(Rifa::class);
    }

    /**
     * Relación con los boletos seleccionados
     */
    public function boletos(): HasMany
    {
        return $this->hasMany(CarritoItemBoleto::class);
    }
}
