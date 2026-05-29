<?php

namespace Database\Seeders;

use App\Models\HeroButton;
use Illuminate\Database\Seeder;

class HeroButtonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HeroButton::query()->updateOrCreate(
            ['title' => 'Teklif Al'],
            [
                'url' => '#teklif',
                'style' => HeroButton::STYLE_PRIMARY,
                'target' => HeroButton::TARGET_SELF,
                'sort_order' => 1,
                'is_active' => true,
            ],
        );

        HeroButton::query()->updateOrCreate(
            ['title' => 'Hizmetleri İncele'],
            [
                'url' => '#hizmetler',
                'style' => HeroButton::STYLE_OUTLINE,
                'target' => HeroButton::TARGET_SELF,
                'sort_order' => 2,
                'is_active' => true,
            ],
        );
    }
}
