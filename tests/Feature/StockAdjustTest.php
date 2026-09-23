<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;

test('admin can add and reduce stock directly from the stock table', function () {
    $admin = User::factory()->create([
        'email' => 'farbib@gmail.com',
        'email_verified_at' => now(),
    ]);

    $category = Category::create([
        'name' => 'Serum',
        'slug' => 'serum',
    ]);

    $product = Product::create([
        'name' => 'Vitamin C Serum',
        'category_id' => $category->id,
        'price' => 290000,
        'stock' => 15,
        'description' => 'Serum vitamin C',
        'status' => 'active',
    ]);

    $this->actingAs($admin)
        ->post(route('stock.adjust', $product), [
            'type' => 'in',
            'quantity' => 5,
        ])
        ->assertRedirect(route('stock.index'));

    $product->refresh();
    expect($product->stock)->toBe(20);

    $this->actingAs($admin)
        ->post(route('stock.adjust', $product), [
            'type' => 'out',
            'quantity' => 3,
        ])
        ->assertRedirect(route('stock.index'));

    $product->refresh();
    expect($product->stock)->toBe(17);
});
