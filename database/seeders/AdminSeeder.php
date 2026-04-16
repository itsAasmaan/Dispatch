<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@dispatch.com'],
            [
                'name'     => 'Dispatch Admin',
                'username' => 'dispatch_admin',
                'email'    => 'admin@dispatch.com',
                'password' => Hash::make('password123'),
            ]
        );

        $admin->assignRole('admin');

        $this->command->info('Admin user seeded: admin@dispatch.com');
    }
}
