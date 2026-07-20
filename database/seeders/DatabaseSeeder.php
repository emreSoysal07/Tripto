<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Önce admin kullanıcı oluştur (Property'lerin "created_by" alanı buna ihtiyaç duyuyor)
        User::factory()->admin()->create([
            'name' => 'Admin Kullanıcı',
            'email' => 'admin@tripto.com',
        ]);

        // 2. Birkaç normal kullanıcı oluştur
        User::factory()->count(5)->create();

        // 3. Sabit veri seeder'ları
        $this->call([
            PropertyTypeSeeder::class,
            RoomTypeSeeder::class,
            AmenitySeeder::class,
            TrustFeatureSeeder::class,
        ]);

        // 4. En son, yukarıdakilere bağlı olan Property'leri oluştur
        $this->call(PropertySeeder::class);
    }
}