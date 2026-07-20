<?php

namespace Database\Seeders;

use App\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Standart Oda', 'slug' => 'standart-oda', 'icon' => 'bx-bed'],
            ['name' => 'Deluxe Oda', 'slug' => 'deluxe-oda', 'icon' => 'bx-crown'],
            ['name' => 'Suit Oda', 'slug' => 'suit-oda', 'icon' => 'bx-star'],
            ['name' => 'Aile Odası', 'slug' => 'aile-odasi', 'icon' => 'bx-group'],
        ];

        foreach ($types as $type) {
            RoomType::create($type);
        }
    }
}