<?php
namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'username' => $this->faker->unique()->userName,
            'name' => $this->faker->name,
            'class' => $this->faker->randomElement(['Admin', 'User']),
            'division' => $this->faker->randomElement(['IT', 'HR', 'Finance']),
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'), // Default password
            'created_at' => now(),
        ];
    }
}
