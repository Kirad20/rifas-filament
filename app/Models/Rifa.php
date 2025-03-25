<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Rifa extends Model implements HasMedia
{
    use InteractsWithMedia, HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio_boleto',
        'imagen_url',
        'fecha_sorteo',
        'boletos_totales',
        'boletos_vendidos',
        'categoria',
        'activa',
        'premio'
    ];

    protected $casts = [
        'fecha_sorteo' => 'datetime',
    ];

    protected $dates = ['fecha_sorteo', 'created_at', 'updated_at'];

    protected $appends = ['boletos_disponibles', 'porcentaje_vendido', 'dias_restantes', 'es_popular'];

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
        $this->addMediaCollection('portada')
            ->singleFile();

        $this->addMediaCollection('fotos');
    }

    public function getBoletosDisponiblesAttribute()
    {
        return $this->boletos()->where('estado', 'disponible')->count();
    }

    public function getPorcentajeVendidoAttribute()
    {
        if ($this->boletos_totales == 0) return 0;
        return round(($this->boletos_vendidos / $this->boletos_totales) * 100);
    }

    public function getDiasRestantesAttribute()
    {
        return Carbon::now()->diffInDays($this->fecha_sorteo, false);
    }

    public function getEsPopularAttribute()
    {
        return $this->porcentaje_vendido > 70;
    }

    /**
     * Obtiene el total de boletos para esta rifa
     *
     * @return int
     */
    public function getTotalBoletosAttribute($value)
    {
        // Si el valor está establecido en la base de datos, úsalo
        if (!empty($value) && is_numeric($value) && $value > 0) {
            return $value;
        }

        // Contar los boletos existentes para esta rifa
        $totalBoletos = Boleto::where('rifa_id', $this->id)->count();

        // Si hay boletos, devolver ese número
        if ($totalBoletos > 0) {
            return $totalBoletos;
        }

        // Valor predeterminado si no hay boletos registrados
        return 100;
    }
}
