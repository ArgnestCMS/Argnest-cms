<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'title' => 'Argnest CMS',
                'product_status' => Product::STATUS_ACTIVE,
                'is_featured' => true,
            ],
            [
                'title' => 'Argnest CRM',
                'product_status' => Product::STATUS_COMING_SOON,
                'is_featured' => false,
            ],
            [
                'title' => 'Argnest Fit',
                'product_status' => Product::STATUS_COMING_SOON,
                'is_featured' => false,
            ],
        ];

        foreach ($products as $index => $product) {
            Product::query()->updateOrCreate(
                ['slug' => Str::slug($product['title'])],
                [
                    'title' => $product['title'],
                    'product_status' => $product['product_status'],
                    'is_featured' => $product['is_featured'],
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ],
            );
        }
    }
}
