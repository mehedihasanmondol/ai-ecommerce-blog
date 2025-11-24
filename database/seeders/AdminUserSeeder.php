<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/**
 * ModuleName: Database Seeder
 * Purpose: Create default admin user for the system
 * 
 * Key Methods:
 * - run(): Create admin user
 * 
 * Dependencies:
 * - User Model
 * 
 * @category Database
 * @package  Database\Seeders
 * @author   Admin
 * @created  2025-01-03
 * @updated  2025-01-03
 */
class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Check if admin user already exists
        $adminExists = User::where('email', 'admin@iherb.com')->exists();

        if (!$adminExists) {
            User::create([
                'name' => 'Admin User',
                'email' => 'admin@demo.com',
                'mobile' => '01700000000',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);

            $this->command->info('Admin user created successfully!');
            $this->command->info('Email: admin@demo.com');
            $this->command->info('Password: admin123');
        } else {
            $this->command->warn('Admin user already exists!');
        }
    }
}
