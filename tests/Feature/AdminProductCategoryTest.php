<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;

test('admin can view product list page', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/products');

    $response
        ->assertOk()
        ->assertSee('Daftar Produk');
});

test('admin can create a product', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post('/products', [
            'name' => 'Hydrating Serum',
            'category_id' => $category->id,
            'price' => 250000,
            'stock' => 25,
            'description' => 'Serum pelembab dengan vitamin C.',
            'status' => 'active',
        ]);

    $response->assertRedirect('/products');
    $this->assertDatabaseHas('products', ['name' => 'Hydrating Serum']);
});

test('admin can view category list page', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/categories');

    $response
        ->assertOk()
        ->assertSee('Daftar Kategori');
});

test('product categories cannot be deleted once used by products', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    Product::factory()->create([
        'category_id' => $category->id,
        'name' => 'Hydrating Serum',
        'price' => 250000,
        'stock' => 10,
        'description' => 'Serum',
        'status' => 'active',
    ]);

    $response = $this
        ->actingAs($user)
        ->from('/categories')
        ->delete('/categories/'.$category->id);

    $response->assertRedirect('/categories');
    $response->assertSessionHas('error', 'Kategori ini masih digunakan oleh produk dan tidak dapat dihapus.');
    $this->assertDatabaseHas('categories', ['id' => $category->id]);
});
