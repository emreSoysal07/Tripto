<?php

namespace Database\Seeders;

use App\Models\TrustFeature;
use Illuminate\Database\Seeder;

class TrustFeatureSeeder extends Seeder
{
    public function run(): void
    {
        $features = [
            [
                'icon' => 'bx-shield-check',
                'title' => 'Güvenli Rezervasyon',
                'description' => 'Tüm ödemeleriniz şifrelenmiş altyapı ile korunur.',
                'order' => 1,
            ],
            [
                'icon' => 'bx-support',
                'title' => '7/24 Destek',
                'description' => 'Seyahatiniz boyunca destek ekibimiz yanınızda.',
                'order' => 2,
            ],
            [
                'icon' => 'bx-badge-check',
                'title' => 'Doğrulanmış İlanlar',
                'description' => 'Her ilan, yayına alınmadan önce ekibimiz tarafından kontrol edilir.',
                'order' => 3,
            ],
        ];

        foreach ($features as $feature) {
            TrustFeature::create($feature);
        }
    }
}