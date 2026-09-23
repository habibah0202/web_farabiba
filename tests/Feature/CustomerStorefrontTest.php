<?php

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

test('buyer accounts are seeded and storefront page is accessible to logged in customer', function () {
    Artisan::call('db:seed');

    $this->assertDatabaseHas('users', ['email' => 'pembeli1@example.com']);
    $this->assertDatabaseHas('users', ['email' => 'pembeli2@example.com']);
    $this->assertDatabaseHas('users', ['email' => 'pembeli3@example.com']);

    $user = User::where('email', 'pembeli1@example.com')->first();

    $this->actingAs($user)
        ->get('/customer')
        ->assertOk()
        ->assertSee('GlowCare')
        ->assertSee('resources/css/app.css')
        ->assertSee(route('customer.profile'))
        ->assertSee(route('profile.edit'))
        ->assertSee('Logout');

    Auth::logout();

    $this->post('/login', [
        'email' => 'pembeli1@example.com',
        'password' => 'pembeli1',
    ])->assertRedirect('/customer');
});
