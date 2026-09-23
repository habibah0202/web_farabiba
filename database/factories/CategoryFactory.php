<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Cleanser', 'Toner', 'Serum', 'Moisturizer', 'Sunscreen']),
            'slug' => fake()->unique()->slug(),
        ];
    }
}
