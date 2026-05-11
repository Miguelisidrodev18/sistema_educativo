<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PagoPension extends Model
{
    protected $table = 'pagos_pension';

    protected $fillable = [
        'alumno_id', 'mes_pagado', 'anio', 'monto',
        'metodo_pago', 'numero_recibo', 'fecha_pago', 'sede_id',
    ];

    protected $casts = [
        'fecha_pago' => 'datetime',
        'monto'      => 'decimal:2',
    ];

    const MESES = [
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre',
    ];

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class);
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }
}
