<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubcartItem extends Model
{
    protected $fillable = ['subcart_id','product_id','quantity','unit_price'];

    public function subcart()    { return $this->belongsTo(Subcart::class); }
    public function subproduct() { return $this->belongsTo(Product::class); }
}