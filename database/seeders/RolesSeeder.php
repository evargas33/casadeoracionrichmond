<?php

namespace Database\Seeders;

use App\Models\Role;

use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $roles = [
            ['name' => 'superadmin',     'display_name' => 'Super Administrator',  'description' => 'Full system access.'],
            ['name' => 'admin',          'display_name' => 'Administrator',         'description' => 'Manages content, sermons and events.'],
            ['name' => 'editor',         'display_name' => 'Editor',                'description' => 'Creates and edits content. Cannot publish.'],
            ['name' => 'member',         'display_name' => 'Member',                'description' => 'Read-only access to the panel.'],
            // Service planning roles
            ['name' => 'pastor',         'display_name' => 'Pastor',               'description' => 'Fills sermon topic and Bible passage for each service.'],
            ['name' => 'lider_alabanza', 'display_name' => 'Líder de Alabanza',    'description' => 'Fills songs list and worship team uniform for each service.'],
            ['name' => 'lider_ujieres',  'display_name' => 'Líder de Ujieres',     'description' => 'Fills usher list and uniform for each service.'],
            ['name' => 'lider_tecnicos', 'display_name' => 'Líder de Técnicos',    'description' => 'Fills tech team assignments (mixer, projection, streaming).'],
            ['name' => 'servidor',       'display_name' => 'Servidor',              'description' => 'Can view service planning on the front-end.'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role['name']], $role);
        }
    }
}
