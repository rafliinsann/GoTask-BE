<?php
namespace Database\Factories;

use App\Models\Board;
use App\Models\Card;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Listt;

class CardFactory extends Factory
{
    public function definition()
    {
        return [
            'cover' => $this->faker->imageUrl(),
            'title' => $this->faker->sentence,
            'assign' => json_encode([$this->faker->userName]),
            'label' => $this->faker->randomElement(['Urgent', 'Medium', 'Low']),
            'dates' => $this->faker->date(),
            'deskripsi' => $this->faker->paragraph(1),
            'list_id' => Listt::inRandomOrder()->first()->id,
            'board_id' => Board::inRandomOrder()->first()->id,
            'created_at' => now(),
        ];
    }
}
