<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionOrder extends Model
{
    protected $fillable = [
        'subscription_id',
        'product_id',
        'product_name',
        'quantity',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function replacements()
    {
        return $this->hasMany(SubscriptionOrderReplacement::class);
    }
}