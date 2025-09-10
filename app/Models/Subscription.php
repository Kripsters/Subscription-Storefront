<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'stripe_customer_id',
        'stripe_subscription_id',
        'stripe_price_id',
        'status',
        'billing_name',
        'billing_email',
        'billing_address',
        'shipping_address',
    ];

    public function orders()
    {
        return $this->hasMany(SubscriptionOrder::class);
    }
}
