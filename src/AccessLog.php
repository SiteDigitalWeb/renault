<?php

namespace Sitedigitalweb\Renault;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cedula',
        'ip_address',
        'user_agent',
        'tipo',
        'detalles'
    ];

    protected $casts = [
        'detalles' => 'array'
    ];

    /**
     * Relación con el usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para búsqueda por cédula
     */
    public function scopePorCedula($query, $cedula)
    {
        return $query->where('cedula', $cedula);
    }

    /**
     * Scope para intentos fallidos recientes
     */
    public function scopeIntentosFallidosRecientes($query, $cedula, $minutes = 15)
    {
        return $query->porCedula($cedula)
                     ->where('tipo', 'intento_fallido')
                     ->where('created_at', '>=', now()->subMinutes($minutes));
    }
}




