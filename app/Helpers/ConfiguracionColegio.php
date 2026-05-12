<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ConfiguracionColegio
{
    private static string $file = 'configuracion_colegio.json';

    private static array $defaults = [
        'mes_inicio'  => 1,    // Enero
        'mes_fin'     => 12,   // Diciembre
        'firma_path'  => null,
    ];

    public static function get(): array
    {
        if (!Storage::disk('local')->exists(self::$file)) {
            return self::$defaults;
        }
        $data = json_decode(Storage::disk('local')->get(self::$file), true) ?? [];
        return array_merge(self::$defaults, $data);
    }

    public static function set(array $data): void
    {
        $actual = self::get();
        Storage::disk('local')->put(self::$file, json_encode(array_merge($actual, $data), JSON_PRETTY_PRINT));
    }

    public static function mesesActivos(): array
    {
        $cfg    = self::get();
        $todos  = ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
                   'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
        $inicio = max(1, min(12, (int)$cfg['mes_inicio'])) - 1;
        $fin    = max(1, min(12, (int)$cfg['mes_fin'])) - 1;
        return array_slice($todos, $inicio, $fin - $inicio + 1);
    }

    public static function firmaUrl(): ?string
    {
        $cfg = self::get();
        if (!empty($cfg['firma_path']) && Storage::disk('public')->exists($cfg['firma_path'])) {
            return Storage::url($cfg['firma_path']);
        }
        return null;
    }

    public static function firmaDiskPath(): ?string
    {
        $cfg = self::get();
        if (!empty($cfg['firma_path'])) {
            return Storage::disk('public')->path($cfg['firma_path']);
        }
        return null;
    }
}
