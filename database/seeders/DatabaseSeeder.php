<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Roles requeridos por el sistema (siempre deben existir)
        foreach (['administrador', 'auxiliar', 'docente', 'estudiante'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // Usuario administrador principal
        // Credenciales configurables via .env: ADMIN_EMAIL, ADMIN_PASSWORD, ADMIN_NAME, ADMIN_DNI
        $admin = User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@jedson.edu.pe')],
            [
                'name'      => env('ADMIN_NAME', 'Administrador JEDSON'),
                'dni'       => env('ADMIN_DNI', '00000001'),
                'password'  => Hash::make(env('ADMIN_PASSWORD', 'jedson2026')),
                'user_type' => 'administrador',
                'activo'    => true,
            ]
        );
        $admin->assignRole('administrador');

        $this->command->info('✓ Roles creados.');
        $this->command->info('✓ Admin: ' . $admin->email);

        if ($this->command->confirm('¿Desea cargar datos de ejemplo (sedes, docentes, alumnos demo)?', false)) {
            $this->call(DemoSeeder::class);
        }
    }
}
