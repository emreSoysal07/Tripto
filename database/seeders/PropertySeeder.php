<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\Property;
use App\Models\PropertyType;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $hotelType = PropertyType::where('slug', 'hotel')->first();
        $amenityIds = Amenity::pluck('id');

        // 10 tane rastgele mülk oluştur (Hotel, House, Cabin karışık)
        Property::factory()
            ->count(10)
            ->create()
            ->each(function (Property $property) use ($hotelType, $amenityIds) {

                // Her mülke rastgele 3-5 arası özellik (amenity) bağla
                $property->amenities()->attach(
                    $amenityIds->random(rand(3, 5))
                );

                // Eğer bu mülk Hotel tipindeyse, 2-4 arası oda ekle
                if ($property->property_type_id === $hotelType->id) {
                    $property->rooms()->createMany(
                        \App\Models\Room::factory()->count(rand(2, 4))->make()->toArray()
                    );
                }

                // Her mülke yeni dinamik politika (policies) kayıtları ekle
                $property->policies()->createMany([
                    [
                        'icon'        => 'bx-time',
                        'title'       => 'Giriş ve Çıkış Saatleri',
                        'description' => 'Giriş saati 14:00, çıkış saati 12:00 olarak belirlenmiştir.',
                    ],
                    [
                        'icon'        => 'bx-x-circle',
                        'title'       => 'İptal Politikası',
                        'description' => 'Giriş tarihinden 48 saat öncesine kadar ücretsiz iptal edilebilir.',
                    ],
                    [
                        'icon'        => 'bx-no-entry',
                        'title'       => 'Ev Kuralları',
                        'description' => 'Kapalı alanlarda sigara içilmez. Evcil hayvan kabul edilmez.',
                    ],
                ]);
            });
    }
}