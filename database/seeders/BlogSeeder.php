<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Web Tasarım',
            'SEO',
            'Yazılım',
            'Kurumsal Çözümler',
        ];

        foreach ($categories as $index => $name) {
            BlogCategory::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ],
            );
        }

        $posts = [
            [
                'title' => 'Kurumsal Web Sitesi Neden Önemlidir?',
                'category' => 'Web Tasarım',
                'excerpt' => 'Kurumsal web sitesi, markanızın dijital dünyadaki güvenilir vitrini olarak çalışır.',
            ],
            [
                'title' => 'SEO Uyumlu Web Sitesi Nasıl Olmalıdır?',
                'category' => 'SEO',
                'excerpt' => 'SEO uyumlu web sitesi; teknik yapı, içerik kalitesi ve kullanıcı deneyimini birlikte ele alır.',
            ],
        ];

        foreach ($posts as $index => $post) {
            $category = BlogCategory::query()->where('slug', Str::slug($post['category']))->firstOrFail();

            BlogPost::query()->updateOrCreate(
                ['slug' => Str::slug($post['title'])],
                [
                    'blog_category_id' => $category->id,
                    'title' => $post['title'],
                    'excerpt' => $post['excerpt'],
                    'content' => $post['excerpt'],
                    'author' => 'Argnest',
                    'published_at' => now()->subDays($index),
                    'is_featured' => $index === 0,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ],
            );
        }
    }
}
