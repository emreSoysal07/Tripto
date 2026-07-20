<?php

namespace Database\Seeders;

use App\Models\PropertyType;
use Illuminate\Database\Seeder;

class PropertyTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Hotel', 'slug' => 'hotel', 'icon' => 'bx-buildings', 'has_rooms' => true],
            ['name' => 'House', 'slug' => 'house', 'icon' => 'bx-home', 'has_rooms' => false],
            ['name' => 'Guest House', 'slug' => 'guest-house', 'icon' => 'bx-building-house', 'has_rooms' => false],
            ['name' => 'Cabin', 'slug' => 'cabin', 'icon' => 'bx-tree', 'has_rooms' => false],
            ['name' => 'Glamping', 'slug' => 'glamping', 'icon' => 'bx-tent', 'has_rooms' => false],
            ['name' => 'Dorm', 'slug' => 'dorm', 'icon' => 'bx-group', 'has_rooms' => true],
        ];

        foreach ($types as $type) {
            PropertyType::create($type);
        }
    }
}