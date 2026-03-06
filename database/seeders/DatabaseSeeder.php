<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = ['Admin', 'Chairman', 'Teacher', 'Student'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
            ]
        );
        $admin->syncRoles(['Admin']);

        $chairman = User::firstOrCreate(
            ['email' => 'chairman@example.com'],
            [
                'name' => 'Chairman User',
                'password' => Hash::make('password123'),
            ]
        );
        $chairman->syncRoles(['Chairman']);

        $teacher = User::firstOrCreate(
            ['email' => 'teacher@example.com'],
            [
                'name' => 'Teacher User',
                'password' => Hash::make('password123'),
            ]
        );
        $teacher->syncRoles(['Teacher']);

        $student = User::firstOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Student User',
                'password' => Hash::make('password123'),
            ]
        );
        $student->syncRoles(['Student']);
    }
}
