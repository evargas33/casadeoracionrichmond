<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'superadmin',
                'display_name' => 'Super Administrator',
                'description' => 'Acceso total a todas las funciones',
            ],
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Administrador del sitio',
            ],
            [
                'name' => 'pastor',
                'display_name' => 'Pastor',
                'description' => 'Pastor de la iglesia',
            ],
            [
                'name' => 'lider_alabanza',
                'display_name' => 'Líder de Alabanza',
                'description' => 'Líder del equipo de alabanza',
            ],
            [
                'name' => 'lider_ujieres',
                'display_name' => 'Líder de Ujieres',
                'description' => 'Líder del equipo de ujieres',
            ],
            [
                'name' => 'lider_tecnicos',
                'display_name' => 'Líder de Técnicos',
                'description' => 'Líder del equipo técnico',
            ],
            [
                'name' => 'servidor',
                'display_name' => 'Servidor',
                'description' => 'Servidor de la iglesia',
            ],
            [
                'name' => 'editor',
                'display_name' => 'Editor',
                'description' => 'Editor de contenido',
            ],
            [
                'name' => 'member',
                'display_name' => 'Miembro',
                'description' => 'Miembro de la congregación',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                [
                    'display_name' => $role['display_name'],
                    'description' => $role['description'],
                ]
            );
        }
    }
}
