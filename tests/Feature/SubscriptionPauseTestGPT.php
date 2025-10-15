<?php

use App\Models\User;
use App\Models\Subscription;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Stripe\StripeClient;
use function Pest\Laravel\{actingAs, post, assertDatabaseHas};
use Mockery;

it('pauses the user subscription successfully', function () {
    // Create a test user
    $user = User::factory()->create();

    // Create a fake subscription in the DB
    $subscription = Subscription::updateOrCreate(
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
            'current_period_start' => now(),
            'current_period_end' => new DateTime('+3 weeks'),
            'billing_name' => 'Bill',
            'billing_email' => 'bill@bill.com',
            'created_at' => now(),
            'updated_at' => now(),
        ] 
    );

    // Mock the Stripe client
    $stripeMock = Mockery::mock(StripeClient::class);
    $stripeMock->subscriptions = Mockery::mock();
    $stripeMock->subscriptions
        ->shouldReceive('update')
        ->once()
        ->with('sub_123456', ['pause_collection' => ['behavior' => 'mark_uncollectible']])
        ->andReturnTrue();

    // Bind the mock to the container so your controller uses it
    app()->instance(StripeClient::class, $stripeMock);

    // Act as the user and hit the route
    actingAs($user);
    $response = post('/subscription/pause');

    // Assert the response and DB
    $response->assertRedirect();
    $response->assertSessionHas('status', 'Subscription has been paused.');

    assertDatabaseHas('subscriptions', [
        'id' => $subscription->id,
        'status' => 'paused',
    ]);
});