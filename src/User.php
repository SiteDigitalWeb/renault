<?php

namespace Sitedigitalweb\Renault;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class User extends Model
{
    use HasFactory;
    use UsesTenantConnection;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cedula',
        'name',
        'email',
        'phone',
        'activo',
        'ultimo_acceso',
        'contador_accesos'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'activo' => 'boolean',
        'ultimo_acceso' => 'datetime'
    ];

    /**
     * Incrementar el contador de accesos
     */
    public function incrementAccessCount()
    {
        $this->contador_accesos++;
        $this->ultimo_acceso = now();
        $this->save();
    }

    /**
     * Buscar usuario por cédula
     */
    public static function findByCedula($cedula)
    {
        return self::where('cedula', $cedula)
                   ->where('activo', true)
                   ->first();
    }
}

