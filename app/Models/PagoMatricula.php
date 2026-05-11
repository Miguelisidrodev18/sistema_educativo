<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PagoMatricula extends Model
{
    protected $table = 'pagos_matricula';

    protected $fillable = [
        'matricula_id', 'monto_matricula', 'pension_mensual',
        'numero_pensiones', 'estado_pago', 'metodo_pago',
        'numero_operacion', 'comprobante_url', 'fecha_pago',
    ];

    protected $casts = [
        'fecha_pago'       => 'date',
        'monto_matricula'  => 'decimal:2',
        'pension_mensual'  => 'decimal:2',
    ];

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(Matricula::class);
    }
}
