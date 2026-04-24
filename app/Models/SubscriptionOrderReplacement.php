<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionOrderReplacement extends Model
{
    protected $fillable = [
        'subscription_order_id',
        'product_id',
    ];

    public function order()
    {
        return $this->belongsTo(SubscriptionOrder::class, 'subscription_order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
