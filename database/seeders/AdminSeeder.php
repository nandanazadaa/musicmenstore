<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::updateOrCreate(
            ['email' => 'admin@musicmen.com'],
            [
                'name' => 'Admin Musicmen',
                'email' => 'admin@musicmen.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@musicmen.com');
        $this->command->info('Password: admin123');
    }
}
