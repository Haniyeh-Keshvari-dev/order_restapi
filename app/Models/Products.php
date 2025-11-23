<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $fillable = ['name','sku','price','stock_quantity'];
    public function orderitems()
    {
        $this->hasMany(OrderItems::class);
    }
}
