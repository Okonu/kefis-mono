<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FulfilledOrder extends Model
{
    protected $fillable = ['product_id', 'status', 'order_number'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
