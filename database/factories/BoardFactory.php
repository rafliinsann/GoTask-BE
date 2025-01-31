<?php
namespace Database\Factories;

use App\Models\Board;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Workspace;

class BoardFactory extends Factory
{
    public function definition()
    {
        return [
            'nama' => $this->faker->word,
            'list' => json_encode([$this->faker->word, $this->faker->word]),
            'member' => json_encode([$this->faker->userName, $this->faker->userName]),
            'user_id' => User::inRandomOrder()->first()->id,
            'workspace_id' => Workspace::inRandomOrder()->first()->id,
            'created_at' => now(),
        ];
    }
}
