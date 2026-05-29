<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            'Kurumsal Web Siteleri',
            'Özel Yazılım Çözümleri',
            'Müşteri Takip Sistemleri',
            'Hosting ve Sunucu Hizmetleri',
            'SEO ve Google Çözümleri',
            'Oto Galeri Yazılımları',
        ];

        foreach ($services as $index => $title) {
            Service::query()->updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title' => $title,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ],
            );
        }
    }
}
