<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'name' => fake()->firstName(),
        'surname' => fake()->lastName(), // Assicurati di avere anche i tuoi campi custom qui
        'email' => fake()->unique()->safeEmail(),
        'password' => static::$password ??= Hash::make('password'),
        'remember_token' => Str::random(10),
        'status' => fake()->randomElement(['Active', 'Inactive']),
        'CF' => fake()->regexify('/^[A-Za-z]{6}[0-9]{2}[A-Za-z]{1}[0-9]{2}[A-Za-z]{1}[0-9]{3}[A-Za-z]{1}$/'),
        'phone' => fake()->regexify('/^\\+?[1-9][0-9]{7,14}$/'),
        'address' => fake()->address(),
    ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
