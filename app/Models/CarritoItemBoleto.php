<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarritoItemBoleto extends Model
{
    use HasFactory;

    protected $table = 'carrito_item_boletos';

    protected $fillable = [
        'carrito_item_id',
        'numero_boleto',
    ];

    /**
     * Relación con el item del carrito
     */
    public function carritoItem(): BelongsTo
    {
        return $this->belongsTo(CarritoItem::class);
    }
}
