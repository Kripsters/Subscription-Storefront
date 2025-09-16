<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    protected $fillable = [
        'user_id',
        'stripe_payment_intent_id',
        'stripe_invoice_id',
        'amount',
        'currency',
        'status',
        'paid_at',
        'raw_data'
    ];
}
