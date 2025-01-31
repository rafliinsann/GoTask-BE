<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkspaceFactory extends Factory
{
    public function definition()
    {
        return [
            'username' => User::inRandomOrder()->first()->username ?? 'default_user',
            'board' => $this->faker->word,
            'member' => json_encode([$this->faker->userName, $this->faker->userName]),
            'created_at' => now(),
        ];
    }

}
