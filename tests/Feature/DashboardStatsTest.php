<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;

test('dashboard shows live product and customer counts from the database', function () {
    $category = Category::create([
        'name' => 'Cleanser',
        'slug' => 'cleanser',
    ]);

    Product::create([
        'name' => 'Produk Satu',
        'category_id' => $category->id,
        'price' => 100000,
        'stock' => 10,
        'description' => 'Deskripsi 1',
        'status' => 'active',
    ]);

    Product::create([
        'name' => 'Produk Dua',
        'category_id' => $category->id,
        'price' => 150000,
        'stock' => 20,
        'description' => 'Deskripsi 2',
        'status' => 'active',
    ]);

    Product::create([
        'name' => 'Produk Tiga',
        'category_id' => $category->id,
        'price' => 200000,
        'stock' => 30,
        'description' => 'Deskripsi 3',
        'status' => 'active',
    ]);

    Product::create([
        'name' => 'Produk Empat',
        'category_id' => $category->id,
        'price' => 250000,
        'stock' => 40,
        'description' => 'Deskripsi 4',
        'status' => 'active',
    ]);

    User::factory()->create([
        'email' => 'farbib@gmail.com',
        'email_verified_at' => now(),
    ]);

    User::factory()->count(3)->create();

    $response = $this
        ->actingAs(User::where('email', 'farbib@gmail.com')->first())
        ->get('/dashboard');

    $response
        ->assertOk()
        ->assertSeeText('4')
        ->assertSeeText('3');
});

test('dashboard sales report uses real transaction totals from the database', function () {
    $customer = User::factory()->create();

    \App\Models\Transaction::create([
        'user_id' => $customer->id,
        'customer' => 'Customer A',
        'invoice' => 'INV-1',
        'total' => 150000,
        'status' => 'Lunas',
        'items' => [],
        'created_at' => now()->subDay(),
    ]);

    \App\Models\Transaction::create([
        'user_id' => $customer->id,
        'customer' => 'Customer B',
        'invoice' => 'INV-2',
        'total' => 300000,
        'status' => 'Lunas',
        'items' => [],
        'created_at' => now(),
    ]);

    \App\Models\Transaction::create([
        'user_id' => $customer->id,
        'customer' => 'Customer C',
        'invoice' => 'INV-3',
        'total' => 450000,
        'status' => 'Lunas',
        'items' => [],
        'created_at' => now()->subDays(10),
    ]);

    $user = User::factory()->create([
        'email' => 'farbib@gmail.com',
        'email_verified_at' => now(),
    ]);

    $response = $this
        ->actingAs($user)
        ->get('/dashboard');

    $response
        ->assertOk()
        ->assertSeeText('Rp 450.000')
        ->assertSeeText('3');
});

test('dashboard overview growth and today activity reflect live database data', function () {
    $customer = User::factory()->create();

    \App\Models\Transaction::create([
        'user_id' => $customer->id,
        'customer' => 'Customer A',
        'invoice' => 'INV-1',
        'total' => 150000,
        'status' => 'Lunas',
        'items' => [],
        'created_at' => now()->subDay(),
    ]);

    \App\Models\Transaction::create([
        'user_id' => $customer->id,
        'customer' => 'Customer B',
        'invoice' => 'INV-2',
        'total' => 300000,
        'status' => 'Lunas',
        'items' => [],
        'created_at' => now(),
    ]);

    $category = Category::create([
        'name' => 'Serum',
        'slug' => 'serum',
    ]);

    Product::create([
        'name' => 'Serum A',
        'category_id' => $category->id,
        'price' => 120000,
        'stock' => 8,
        'description' => 'Low stock serum',
        'status' => 'active',
    ]);

    Product::create([
        'name' => 'Serum B',
        'category_id' => $category->id,
        'price' => 150000,
        'stock' => 7,
        'description' => 'Low stock serum second',
        'status' => 'active',
    ]);

    $user = User::factory()->create([
        'email' => 'farbib@gmail.com',
        'email_verified_at' => now(),
    ]);

    $response = $this
        ->actingAs($user)
        ->get('/dashboard');

    $response
        ->assertOk()
        ->assertSeeText('+100.0%')
        ->assertSeeText('2 transaksi baru')
        ->assertSeeText('2 produk menipis stok');
});

test('dashboard product table reads from the database instead of static demo values', function () {
    $category = Category::create([
        'name' => 'Serum',
        'slug' => 'serum',
    ]);

    Product::create([
        'name' => 'Serum A',
        'category_id' => $category->id,
        'price' => 120000,
        'stock' => 8,
        'description' => 'Low stock serum',
        'status' => 'active',
    ]);

    Product::create([
        'name' => 'Serum B',
        'category_id' => $category->id,
        'price' => 150000,
        'stock' => 7,
        'description' => 'Low stock serum second',
        'status' => 'active',
    ]);

    $user = User::factory()->create([
        'email' => 'farbib@gmail.com',
        'email_verified_at' => now(),
    ]);

    $response = $this
        ->actingAs($user)
        ->get('/dashboard');

    $response
        ->assertOk()
        ->assertSeeText('Serum A')
        ->assertSeeText('7')
        ->assertDontSeeText('Hydrating Essence');
});

test('dashboard orders shows total product units ordered by customers', function () {
    $user = User::factory()->create([
        'email' => 'farbib@gmail.com',
        'email_verified_at' => now(),
    ]);

    \App\Models\Transaction::create([
        'user_id' => $user->id,
        'customer' => 'Customer A',
        'invoice' => 'INV-ORDER-1',
        'total' => 150000,
        'status' => 'Lunas',
        'items' => [
            ['name' => 'Serum A', 'quantity' => 2, 'price' => 50000],
            ['name' => 'Cleanser A', 'quantity' => 3, 'price' => 20000],
        ],
        'created_at' => now(),
    ]);

    \App\Models\Transaction::create([
        'user_id' => $user->id,
        'customer' => 'Customer B',
        'invoice' => 'INV-ORDER-2',
        'total' => 120000,
        'status' => 'Lunas',
        'items' => [
            ['name' => 'Toner A', 'quantity' => 4, 'price' => 30000],
        ],
        'created_at' => now(),
    ]);

    $response = $this
        ->actingAs($user)
        ->get('/dashboard');

    $response
        ->assertOk()
        ->assertSeeText('9')
        ->assertSeeText('2');
});

test('admin stock summary shows total sold units from customer transactions', function () {
    $user = User::factory()->create([
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

    \App\Models\Transaction::create([
        'user_id' => $user->id,
        'customer' => 'Budi Pelanggan',
        'invoice' => 'INV-001',
        'total' => 580000,
        'status' => 'Lunas',
        'items' => [
            $product->id => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => 290000,
                'quantity' => 2,
            ],
        ],
    ]);

    $response = $this
        ->actingAs($user)
        ->get('/stock');

    $response
        ->assertOk()
        ->assertSeeText('2');
});
