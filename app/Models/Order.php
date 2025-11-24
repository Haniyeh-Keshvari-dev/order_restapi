<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['customer_id', 'total_amount'];
    public function customer()
    {
        $this->belongsTo(Customer::class);
    }
    public function orderitems(){

        $this->hasMany(OrderItem::class);
    }
}
