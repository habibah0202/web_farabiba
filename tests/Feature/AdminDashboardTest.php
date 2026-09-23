<?php

use App\Models\User;

test('admin dashboard displays key modules', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/dashboard');

    $response
        ->assertOk()
        ->assertSee('Dashboard Admin')
        ->assertSee('Manajemen Produk Skincare')
        ->assertSee('Transaksi Penjualan')
        ->assertSee('Laporan Penjualan');
});
