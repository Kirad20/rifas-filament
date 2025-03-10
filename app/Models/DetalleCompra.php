<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleCompra extends Model
{
    protected $table = 'detalle_compras';

    protected $fillable = [
        'venta_id',
        'boleto_id',
        'precio',
    ];

    public function compra(): BelongsTo
    {
        return $this->belongsTo(Venta::class, 'compra_id');
    }

    public function boleto(): BelongsTo
    {
        return $this->belongsTo(Boleto::class);
    }
}
