<?php

use App\Models\User;

test('admin customer profile management shows registered customers from database', function () {
    $admin = User::factory()->create();
    $customer = User::factory()->create([
        'name' => 'Nia Customer',
        'email' => 'nia.customer@example.com',
    ]);

    $response = $this
        ->actingAs($admin)
        ->get('/admin/customers');

    $response
        ->assertOk()
        ->assertSee('Kelola Profil Pelanggan')
        ->assertSee('Nia Customer')
        ->assertSee('nia.customer@example.com');
});

test('admin customer transaction history shows transaction records', function () {
    $admin = User::factory()->create();
    $customer = User::factory()->create([
        'name' => 'Nia Customer',
        'email' => 'nia.customer@example.com',
    ]);

    \App\Models\Transaction::create([
        'user_id' => $customer->id,
        'customer' => 'Nia Customer',
        'invoice' => 'INV-2026-001',
        'total' => 245000,
        'status' => 'Lunas',
        'items' => [
            ['name' => 'Hydrating Essence', 'quantity' => 2, 'price' => 125000],
        ],
    ]);

    $response = $this
        ->actingAs($admin)
        ->get('/admin/customers/transactions');

    $response
        ->assertOk()
        ->assertSee('Riwayat Transaksi')
        ->assertSee('INV-2026-001')
        ->assertSee('Nia Customer');
});
