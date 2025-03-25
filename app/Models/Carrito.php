<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Carrito extends Model
{
    use HasFactory;

    protected $table = 'carritos';

    protected $fillable = [
        'user_id',
        'telefono',
        'token',
        'expira_en',
    ];

    protected $casts = [
        'expira_en' => 'datetime',
    ];

    /**
     * Relación con el usuario (opcional)
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación con los items del carrito
     */
    public function items(): HasMany
    {
        return $this->hasMany(CarritoItem::class);
    }

    /**
     * Generar un token único para acceder al carrito sin estar logueado
     */
    public static function generarToken(): string
    {
        return bin2hex(random_bytes(20));
    }

    /**
     * Obtener o crear un carrito para un teléfono
     */
    public static function obtenerCarritoParaTelefono(string $telefono)
    {
        // Eliminar cualquier formato o espacio en el teléfono
        $telefono = preg_replace('/[^0-9]/', '', $telefono);

        // Buscar carrito existente por teléfono
        $carrito = self::where('telefono', $telefono)->first();

        // Si no existe, crear uno nuevo
        if (!$carrito) {
            $carrito = self::create([
                'telefono' => $telefono,
                'token' => self::generarToken(),
                'expira_en' => now()->addDays(5), // Expira en 5 días
            ]);
        }

        return $carrito;
    }

    /**
     * Obtener el total del carrito
     */
    public function getTotal()
    {
        return $this->items->sum(function ($item) {
            return $item->boletos->count() * $item->precio_unitario;
        });
    }
}
