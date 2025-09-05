<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id','status'];

    public function items() { return $this->hasMany(CartItem::class); }

    public function getSubtotalAttribute()
    {
        return $this->items->sum(fn($i) => $i->quantity * $i->unit_price);
    }
}
