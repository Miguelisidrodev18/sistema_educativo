<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Matricula extends Model
{
    protected $fillable = [
        'codigo_matricula', 'alumno_id', 'periodo',
        'nivel_academico', 'grado_seccion', 'situacion',
        'modalidad_pago', 'sede_id', 'estado',
    ];

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class);
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }

    public function pagoMatricula(): HasOne
    {
        return $this->hasOne(PagoMatricula::class);
    }
}
