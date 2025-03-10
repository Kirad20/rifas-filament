<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Rifa extends Model implements HasMedia
{

    use InteractsWithMedia;

    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha_sorteo',
        'precio_boleto',
        'total_boletos',
        'premio',
        'imagen',
        'estado'
    ];

    protected $casts = [
        'fecha_sorteo' => 'datetime',
    ];

    protected $appends = ['boletos_disponibles'];

    public function boletos()
    {
        return $this->hasMany(Boleto::class);
    }

    public function ventas()
    {
        return $this->hasManyThrough(Venta::class, Boleto::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('fotos');
    }

    public function getBoletosDisponiblesAttribute()
    {
        return $this->boletos()->where('estado', 'disponible')->count();
    }
}
