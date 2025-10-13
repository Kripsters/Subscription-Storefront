<?php

use App\Models\User;
use App\Models\Subscription;

test('can get to subscription overview', function () {
    $user = User::factory()->create();
    Subscription::updateOrCreate(
        ['email' => 'tester@test.com'],
        [
            'name' => 'Tester',
            'email_verified_at' => now(),
            'password' => bcrypt('Parole123!'),
            'is_admin' => 'false',
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ] 
    );

    $response = $this
        ->actingAs($user)
        ->get('/subscription');

    $response->assertSee('Subscription Overview');
});
