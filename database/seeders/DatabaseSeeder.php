<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Creates 1 admin account and 1 customer account for testing
     */
    public function run(): void
    {
        // Create Admin Account
        User::create([
            'name' => 'Admin Griya Deyalina',
            'email' => 'admin@griya-deyalina.com',
            'password' => Hash::make('admin123'), // Default password: admin123
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Surabaya, Jawa Timur',
            'email_verified_at' => now(),
        ]);

        // Create Customer Account
        User::create([
            'name' => 'Pelanggan Test',
            'email' => 'customer@example.com',
            'password' => Hash::make('customer123'), // Default password: customer123
            'role' => 'customer',
            'phone' => '082345678901',
            'address' => 'Jl. Contoh No. 123, Surabaya',
            'email_verified_at' => now(),
        ]);

        // Call CategorySeeder to populate categories
        $this->call([
            CategorySeeder::class,
        ]);
    }
}