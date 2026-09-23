<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('seeded admin user can log in', function () {
    User::factory()->create([
        'name' => 'Farbib',
        'email' => 'farbib@gmail.com',
        'password' => Hash::make('password'),
    ]);

    $response = $this->post('/login', [
        'email' => 'farbib@gmail.com',
        'password' => 'password',
    ]);

    $response->assertRedirect('/dashboard');
    $this->assertAuthenticated();
});
