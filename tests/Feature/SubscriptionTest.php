<?php

use App\Models\User;
use App\Models\Subscription;

test('can get to subscription overview', function () {
    $user = User::factory()->create();
    Subscription::updateOrCreate(
        ['user_id' => $user->id],
        [
            'stripe_customer_id' => 'cus_xxxxx',
            'stripe_subscription_id' => 'sub_xxxxxx',
            'stripe_price_id' => 'prc_xxxxxxx',
            'status' => 'active',
            'plan_name' => 'PLANNAME',
            'amount' => 1000,
            'currency' => 'EUR',
            'interval' => 'month',
            'current_period_start' => now(),
            'current_period_end' => new DateTime('+3 weeks'),
            'billing_name' => 'Bill',
            'billing_email' => 'bill@bill.com',
            'created_at' => now(),
            'updated_at' => now(),
        ] 
    );

    $response = $this
        ->actingAs($user)
        ->get('/subscription');

    $response->assertSee('Subscription Overview');
});
