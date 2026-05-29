<?php

namespace Database\Seeders;

use App\Models\Portfolio;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PortfolioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $portfolios = [
            [
                'title' => 'Argnest CMS',
                'client_name' => 'Argnest',
            ],
            [
                'title' => 'Kurumsal Web Çözümü',
                'client_name' => 'Demo Firma',
            ],
        ];

        foreach ($portfolios as $index => $portfolio) {
            Portfolio::query()->updateOrCreate(
                ['slug' => Str::slug($portfolio['title'])],
                [
                    'title' => $portfolio['title'],
                    'client_name' => $portfolio['client_name'],
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ],
            );
        }
    }
}
