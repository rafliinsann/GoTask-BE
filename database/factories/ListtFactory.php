<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Board;
use App\Models\Listt;

class ListtFactory extends Factory
{
    public function definition()
    {
        return [
            'card' => json_encode([$this->faker->sentence, $this->faker->sentence]),
            'board_id' => Board::factory()->create()->id,
            'created_at' => now(),
        ];
    }
}
