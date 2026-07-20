<?php

namespace Database\Factories;

use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    public function definition(): array
    {
        return [
            'room_type_id' => RoomType::inRandomOrder()->first()->id,
            'price_per_night' => fake()->numberBetween(800, 4000),
            'capacity' => fake()->numberBetween(1, 4),
            'total_rooms' => fake()->numberBetween(2, 15),
            'description' => fake()->sentence(10),
        ];
    }
}