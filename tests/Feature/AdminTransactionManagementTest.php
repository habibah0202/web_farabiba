<?php

use App\Models\Transaction;
use App\Models\User;

beforeEach(function () {
    $customer = User::factory()->create(['name' => 'Nia Customer']);

    Transaction::create([
        'user_id' => $customer->id,
        'invoice' => 'INV-2026-001',
        'customer' => 'Nia Customer',
        'total' => 245000,
        'status' => 'Lunas',
        'items' => [
            ['name' => 'Hydrating Essence', 'qty' => 2, 'price' => 125000],
        ],
    ]);
});

test('admin sales transaction page is available', function () {
    $admin = User::factory()->create();

    $response = $this
        ->actingAs($admin)
        ->get('/admin/transactions/sales');

    $response
        ->assertOk()
        ->assertSee('Transaksi Penjualan')
        ->assertSee('Export PDF');
});

test('admin receipt detail page is available', function () {
    $admin = User::factory()->create();

    $response = $this
        ->actingAs($admin)
        ->get('/admin/transactions/receipts');

    $response
        ->assertOk()
        ->assertSee('Detail Transaksi & Struk')
        ->assertSee('Struk Cetak');
});
