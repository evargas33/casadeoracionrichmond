<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@casadeoracion.org'],
            [
                'name'              => 'Administrator',
                'password'          => Hash::make('CasaDeOracion2025!'),
                'email_verified_at' => now(),
                'is_active'         => true,
            ]
        );

        $role = Role::where('name', 'superadmin')->first();

        if ($role && ! $admin->roles()->where('role_id', $role->id)->exists()) {
            $admin->roles()->attach($role->id);
        }
    }
}
