<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Boleto extends Model
{
    protected $fillable = [
        'rifa_id',
        'cliente_id',
        'numero',
        'estado',
    ];

    public function rifa()
    {
        return $this->belongsTo(Rifa::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
