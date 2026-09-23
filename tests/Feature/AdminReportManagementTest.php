<?php

use App\Models\User;

test('admin report page is available with daily weekly monthly filter and pdf export', function () {
    $admin = User::factory()->create();

    $response = $this
        ->actingAs($admin)
        ->get('/admin/reports');

    $response
        ->assertOk()
        ->assertSee('Laporan Penjualan')
        ->assertSee('Harian')
        ->assertSee('Mingguan')
        ->assertSee('Bulanan')
        ->assertSee('Tanggal Mulai')
        ->assertSee('Tanggal Akhir')
        ->assertSee('Cetak PDF');
});
