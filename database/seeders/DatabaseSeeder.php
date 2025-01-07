<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */

    public function run()
    {
        // Create default admin user
        User::create([
            'name' => 'chouameni gille',
            'email' => 'gille@gmail.com',
            'password' => Hash::make('password'), // Change to a secure password
            'id_role' => 1, // Set to Admin role
            'numero' => '123456789',
            'adresse' => 'Admin Address'
        ]);
    }

}
