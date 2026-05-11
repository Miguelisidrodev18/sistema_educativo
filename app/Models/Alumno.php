<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alumno extends Model
{
    protected $fillable = [
        'dni', 'nombres', 'apellidos', 'fecha_nacimiento', 'sexo',
        'ciudad', 'direccion', 'nivel_academico', 'grado_seccion',
        'repitencia', 'foto_path', 'qr_code_path',
        'apoderado_id', 'sede_id',
        'tipo_descuento', 'monto_descuento', 'descripcion_descuento',
        'activo',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'repitencia'       => 'boolean',
        'activo'           => 'boolean',
        'monto_descuento'  => 'decimal:2',
    ];

    public function apoderado(): BelongsTo
    {
        return $this->belongsTo(Apoderado::class);
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class);
    }

    public function pagosPension(): HasMany
    {
        return $this->hasMany(PagoPension::class);
    }

    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class);
    }

    public function getNombreCompletoAttribute(): string
    {
        return $this->apellidos . ', ' . $this->nombres;
    }

    public function getEdadAttribute(): ?int
    {
        return $this->fecha_nacimiento ? $this->fecha_nacimiento->age : null;
    }
}
