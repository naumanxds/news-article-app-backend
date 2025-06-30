<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'User 1',
            'email' => 'user1@app.com',
            'password' => Hash::make(123123123),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'User 2',
            'email' => 'user2@app.com',
            'password' => Hash::make(123123123),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'User 3',
            'email' => 'user3@app.com',
            'password' => Hash::make(123123123),
            'email_verified_at' => now(),
        ]);

        User::factory()->count(10)->create();
    }
}
