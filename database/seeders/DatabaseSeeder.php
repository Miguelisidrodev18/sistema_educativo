<?php

namespace Database\Seeders;

use App\Models\Alumno;
use App\Models\Apoderado;
use App\Models\Sede;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $roles = ['administrador', 'auxiliar', 'docente', 'estudiante'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // Create sedes
        $sedeA = Sede::firstOrCreate(
            ['nombre' => 'Sede Central'],
            ['direccion' => 'Av. Arequipa 123, Arequipa', 'telefono' => '054-123456', 'activo' => true]
        );
        $sedeB = Sede::firstOrCreate(
            ['nombre' => 'Sede Filial'],
            ['direccion' => 'Jr. Lima 456, Arequipa', 'telefono' => '054-654321', 'activo' => true]
        );

        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@jedson.edu.pe'],
            [
                'name'      => 'Administrador JEDSON',
                'dni'       => '00000001',
                'password'  => Hash::make('jedson2026'),
                'user_type' => 'administrador',
                'sede_id'   => $sedeA->id,
                'activo'    => true,
            ]
        );
        $admin->assignRole('administrador');

        // Create sample docente
        $docente = User::firstOrCreate(
            ['email' => 'docente@jedson.edu.pe'],
            [
                'name'      => 'Prof. Carlos Mamani',
                'dni'       => '12345678',
                'password'  => Hash::make('jedson2026'),
                'user_type' => 'docente',
                'sede_id'   => $sedeA->id,
                'activo'    => true,
            ]
        );
        $docente->assignRole('docente');

        // Create sample apoderado
        $apoderado = Apoderado::firstOrCreate(
            ['dni' => '20000001'],
            [
                'nombres'    => 'María Elena',
                'apellidos'  => 'Quispe Flores',
                'telefono'   => '959123456',
                'email'      => 'mapoderado@gmail.com',
                'parentesco' => 'MADRE',
                'direccion'  => 'Urb. Los Jardines B-12, Arequipa',
            ]
        );

        // Create sample alumnos
        $alumnosData = [
            [
                'dni'             => '73001001',
                'nombres'         => 'Juan Pablo',
                'apellidos'       => 'Quispe Flores',
                'fecha_nacimiento' => '2010-03-15',
                'sexo'            => 'MASCULINO',
                'ciudad'          => 'Arequipa',
                'nivel_academico' => 'SECUNDARIA',
                'grado_seccion'   => '3ro A',
                'sede_id'         => $sedeA->id,
                'apoderado_id'    => $apoderado->id,
            ],
            [
                'dni'             => '73001002',
                'nombres'         => 'Ana Lucía',
                'apellidos'       => 'Quispe Flores',
                'fecha_nacimiento' => '2012-07-22',
                'sexo'            => 'FEMENINO',
                'ciudad'          => 'Arequipa',
                'nivel_academico' => 'PRIMARIA',
                'grado_seccion'   => '5to B',
                'sede_id'         => $sedeA->id,
                'apoderado_id'    => $apoderado->id,
                'tipo_descuento'  => 'hermanos',
                'monto_descuento' => 50.00,
            ],
            [
                'dni'             => '73001003',
                'nombres'         => 'Luis Fernando',
                'apellidos'       => 'Mamani Torres',
                'fecha_nacimiento' => '2015-11-08',
                'sexo'            => 'MASCULINO',
                'ciudad'          => 'Arequipa',
                'nivel_academico' => 'INICIAL',
                'grado_seccion'   => '5 Años',
                'sede_id'         => $sedeB->id,
            ],
        ];

        foreach ($alumnosData as $data) {
            Alumno::firstOrCreate(['dni' => $data['dni']], array_merge($data, ['activo' => true]));
        }

        $this->command->info('Base de datos sembrada correctamente para Colegio Pre JEDSON.');
        $this->command->info('Admin: admin@jedson.edu.pe / jedson2026');
    }
}
