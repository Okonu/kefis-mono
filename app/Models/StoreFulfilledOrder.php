<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreFulfilledOrder extends Model
{
    use HasFactory;

    protected $fillable = ['product_name', 'quantity', 'order_number'];
}
