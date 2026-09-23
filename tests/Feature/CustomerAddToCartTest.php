<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;

test('customer can add a chosen quantity directly from product cards without leaving the page', function () {
    $user = User::factory()->create();

    $category = Category::create([
        'name' => 'Toner',
        'slug' => 'toner',
    ]);

    $product = Product::create([
        'name' => 'Hydrating Essence',
        'category_id' => $category->id,
        'price' => 245000,
        'stock' => 20,
        'description' => 'Toner lembap',
        'status' => 'active',
    ]);

    $this->actingAs($user)
        ->from(route('customer.index'))
        ->post(route('customer.products.addToCart', $product), [
            'quantity' => 3,
        ])
        ->assertRedirect(route('customer.index'));

    $this->assertDatabaseHas('carts', [
        'user_id' => $user->id,
        'product_id' => $product->id,
        'quantity' => 3,
    ]);
});

test('customer checkout records the buyer and purchased items for admin access', function () {
    $user = User::factory()->create([
        'name' => 'Budi Pelanggan',
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

    \App\Models\Cart::create([
        'user_id' => $user->id,
        'product_id' => $product->id,
        'quantity' => 2,
    ]);

    $this->actingAs($user)
        ->post(route('customer.checkout.store'))
        ->assertRedirect(route('customer.transactions'));

    $transaction = \App\Models\Transaction::where('user_id', $user->id)->first();

    expect($transaction->customer)->toBe('Budi Pelanggan')
        ->and($transaction->items[$product->id]['quantity'])->toBe(2)
        ->and((float) $transaction->total)->toBe(580000.0);
});

test('customer cart and checkout are stored per user in the database', function () {
    $user = User::factory()->create([
        'name' => 'Budi Pelanggan',
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

    $this->actingAs($user)
        ->from(route('customer.index'))
        ->post(route('customer.products.addToCart', $product), [
            'quantity' => 2,
        ])
        ->assertRedirect(route('customer.index'));

    $this->assertDatabaseHas('carts', [
        'user_id' => $user->id,
        'product_id' => $product->id,
        'quantity' => 2,
    ]);

    $this->actingAs($user)
        ->post(route('customer.checkout.store'))
        ->assertRedirect(route('customer.transactions'));

    $this->assertDatabaseHas('transactions', [
        'user_id' => $user->id,
        'customer' => 'Budi Pelanggan',
        'total' => 580000,
    ])
        ->assertDatabaseMissing('carts', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
});

test('customer checkout reduces product stock after a successful purchase', function () {
    $user = User::factory()->create([
        'name' => 'Budi Pelanggan',
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

    \App\Models\Cart::create([
        'user_id' => $user->id,
        'product_id' => $product->id,
        'quantity' => 2,
    ]);

    $this->actingAs($user)
        ->post(route('customer.checkout.store'))
        ->assertRedirect(route('customer.transactions'));

    $product->refresh();

    expect($product->stock)->toBe(13);
});
