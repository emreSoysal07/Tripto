<?php

namespace Database\Factories;

use App\Models\PropertyType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PropertyFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->words(3, true) . ' ' . fake()->city();

        return [
            'property_type_id' => PropertyType::inRandomOrder()->first()->id,
            'created_by' => User::where('role', 'admin')->first()->id,
            'title' => ucfirst($title),
            'slug' => Str::slug($title) . '-' . fake()->unique()->numberBetween(1, 100000),
            'description' => fake()->paragraphs(3, true),
            'price_per_night' => fake()->numberBetween(500, 5000),
            'capacity' => fake()->numberBetween(1, 8),
            'bedrooms' => fake()->numberBetween(1, 4),
            'bathrooms' => fake()->numberBetween(1, 3),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'country' => 'Türkiye',
            'latitude' => fake()->latitude(36, 42),
            'longitude' => fake()->longitude(26, 45),
            'status' => 'published',
        ];
    }
}