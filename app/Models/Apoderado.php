<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Apoderado extends Model
{
    protected $fillable = [
        'dni', 'nombres', 'apellidos', 'telefono',
        'email', 'parentesco', 'direccion',
    ];

    public function alumnos(): HasMany
    {
        return $this->hasMany(Alumno::class);
    }

    public function getNombreCompletoAttribute(): string
    {
        return $this->nombres . ' ' . $this->apellidos;
    }
}
