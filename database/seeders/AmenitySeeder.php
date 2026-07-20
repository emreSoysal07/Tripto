<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    public function run(): void
    {
        $amenities = [
            ['name' => 'WiFi', 'icon' => 'bx-wifi'],
            ['name' => 'Otopark', 'icon' => 'bx-car'],
            ['name' => 'Havuz', 'icon' => 'bx-water'],
            ['name' => 'Klima', 'icon' => 'bx-wind'],
            ['name' => 'Mutfak', 'icon' => 'bx-restaurant'],
            ['name' => 'Çamaşır Makinesi', 'icon' => 'bx-refresh'],
            ['name' => 'Evcil Hayvan Kabul', 'icon' => 'bx-paw'],
            ['name' => 'Kahvaltı Dahil', 'icon' => 'bx-coffee'],
        ];

        foreach ($amenities as $amenity) {
            Amenity::create($amenity);
        }
    }
}