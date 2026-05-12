<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $fillable = [
        'name', 'dni', 'email', 'password',
        'user_type', 'sede_id', 'activo', 'qr_code_path',
        'alumno_id', 'must_change_password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'    => 'datetime',
            'password'             => 'hashed',
            'activo'               => 'boolean',
            'must_change_password' => 'boolean',
        ];
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }

    public function asistenciasDocente(): HasMany
    {
        return $this->hasMany(AsistenciaDocente::class);
    }

    public function asignaciones(): HasMany
    {
        return $this->hasMany(DocenteAsignacion::class)->with('sede')->orderBy('nivel')->orderBy('grado_seccion');
    }

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class);
    }

    public function isEstudiante(): bool
    {
        return $this->user_type === 'estudiante';
    }

    public function isAdmin(): bool
    {
        return $this->user_type === 'administrador';
    }

    public function isDocente(): bool
    {
        return $this->user_type === 'docente';
    }
}
