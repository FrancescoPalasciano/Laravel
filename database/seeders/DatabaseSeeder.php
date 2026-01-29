<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(20)->create();

        User::factory()->create([
            'name' => 'Example',
            'surname' => 'Example',
            'phone' => '1234567890',
            'CF' => 'EXAMPL12345X',
            'address' => '123 Example St, Example City',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
