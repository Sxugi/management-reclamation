<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'username' => 'admin',
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'phone' => '081234567890',
            'password' => Hash:: make('password'),
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $manager = User::create([
            'username' => 'manager',
            'name' => 'Manager Siti',
            'email' => 'manager@example.com',
            'phone' => '081234567891',
            'password' => Hash::make('password'),
            'role' => 'user',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $fieldOfficer1 = User::create([
            'username' => 'ahmad',
            'name' => 'Ahmad Hidayat',
            'email' => 'ahmad@example.com',
            'phone' => '081234567892',
            'password' => Hash::make('password'),
            'role' => 'user',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $fieldOfficer2 = User::create([
            'username' => 'budi',
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'phone' => '081234567893',
            'password' => Hash::make('password'),
            'role' => 'user',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $viewer = User::create([
            'username' => 'viewer',
            'name' => 'Viewer User',
            'email' => 'viewer@example. com',
            'phone' => '081234567894',
            'password' => Hash::make('password'),
            'role' => 'user',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}