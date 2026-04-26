<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'shipping',
        'billing',
    ];

    protected $casts = [
        'shipping' => 'array',
        'billing'  => 'array',
    ];
}
