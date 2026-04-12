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
            ['name' => 'superadmin', 'display_name' => 'Super Administrator', 'description' => 'Full system access.'],
            ['name' => 'admin',      'display_name' => 'Administrator',        'description' => 'Manages content, sermons and events.'],
            ['name' => 'editor',     'display_name' => 'Editor',               'description' => 'Creates and edits content. Cannot publish.'],
            ['name' => 'member',     'display_name' => 'Member',               'description' => 'Read-only access to the panel.'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role['name']], $role);
        }
    }
}
