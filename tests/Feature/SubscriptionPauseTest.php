<?php

use App\Models\User;
use App\Models\Subscription;

test('can pause subscription', function () {
    $user = User::factory()->create();
    Subscription::updateOrCreate(
        ['user_id' => $user->id],
        [
            'stripe_customer_id' => 'IDONTCARE',
            'stripe_subscription_id' => 'IDONTCARE',
            'stripe_price_id' => 'IDONTCARE',
            'status' => 'active',
            'plan_name' => 'PLANNAME',
            'amount' => 1000,
            'currency' => 'EUR',
            'interval' => 'month',
            'current_period_start' => 'IDONTCARE',
            'created_at' => now(),
            'updated_at' => now(),
        ] 
    );


    $response = $this
        ->actingAs($user)
        ->get('/subscription');

    $response->assertSee('Subscription Overview');
});
