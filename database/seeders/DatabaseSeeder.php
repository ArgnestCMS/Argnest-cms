<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(ServiceSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(PortfolioSeeder::class);
        $this->call(BlogSeeder::class);
        $this->call(HeroButtonSeeder::class);

        User::query()->firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'role' => User::ROLE_ADMIN,
                'is_active' => true,
            ],
        );
    }
}
