<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => fake()->randomElement([
                'Hydrating Essence',
                'Vitamin C Serum',
                'Ceramide Cream',
                'Daily Cleanser',
                'Sunscreen SPF 50',
            ]),
            'description' => fake()->sentence(),
            'stock' => fake()->numberBetween(5, 100),
            'price' => fake()->numberBetween(50000, 500000),
            'status' => fake()->randomElement(['active', 'inactive']),
        ];
    }
}
