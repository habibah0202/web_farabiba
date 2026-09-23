<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
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
        User::factory()->create([
            'name' => 'Farbib',
            'email' => 'farbib@gmail.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $buyers = [
            ['name' => 'Pembeli Satu', 'email' => 'pembeli1@example.com', 'password' => 'pembeli1'],
            ['name' => 'Pembeli Dua', 'email' => 'pembeli2@example.com', 'password' => 'pembeli2'],
            ['name' => 'Pembeli Tiga', 'email' => 'pembeli3@example.com', 'password' => 'pembeli3'],
        ];

        foreach ($buyers as $buyer) {
            User::factory()->create([
                'name' => $buyer['name'],
                'email' => $buyer['email'],
                'password' => bcrypt($buyer['password']),
                'email_verified_at' => now(),
            ]);
        }

        $categories = [
            ['name' => 'Cleanser', 'slug' => 'cleanser'],
            ['name' => 'Toner', 'slug' => 'toner'],
            ['name' => 'Serum', 'slug' => 'serum'],
            ['name' => 'Moisturizer', 'slug' => 'moisturizer'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                ['name' => $category['name']]
            );
        }

        $cleanserId = Category::where('slug', 'cleanser')->value('id');
        $tonerId = Category::where('slug', 'toner')->value('id');
        $serumId = Category::where('slug', 'serum')->value('id');
        $moisturizerId = Category::where('slug', 'moisturizer')->value('id');

        $products = [
            ['name' => 'Hydrating Essence', 'category_id' => $tonerId, 'price' => 245000, 'stock' => 40, 'description' => 'Toner ringan yang menutrisi kulit dan membuatnya lembap sepanjang hari.', 'status' => 'active'],
            ['name' => 'Barrier Repair Cream', 'category_id' => $moisturizerId, 'price' => 320000, 'stock' => 30, 'description' => 'Krim pelembap untuk menjaga skin barrier dan mencegah kekeringan.', 'status' => 'active'],
            ['name' => 'Vitamin C Serum', 'category_id' => $serumId, 'price' => 290000, 'stock' => 25, 'description' => 'Serum brightening dengan vitamin C untuk kulit cerah dan glowing.', 'status' => 'active'],
            ['name' => 'Gentle Cleanser', 'category_id' => $cleanserId, 'price' => 180000, 'stock' => 60, 'description' => 'Pembersih lembut yang membersihkan kotoran tanpa membuat kulit terasa kering.', 'status' => 'active'],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['name' => $product['name']],
                $product
            );
        }
    }
}
