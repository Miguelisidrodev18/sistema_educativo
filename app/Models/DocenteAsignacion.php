<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocenteAsignacion extends Model
{
    protected $table = 'docente_asignaciones';

    protected $fillable = [
        'user_id', 'sede_id', 'nivel', 'grado_seccion', 'materia',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }

    public function getNivelLabelAttribute(): string
    {
        return match($this->nivel) {
            'inicial'     => 'Inicial',
            'primaria'    => 'Primaria',
            'secundaria'  => 'Secundaria',
            default       => ucfirst($this->nivel),
        };
    }
}
