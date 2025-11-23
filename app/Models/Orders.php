<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $fillable = ['customer_id', 'total_amount'];
    public function customer()
    {
        $this->belongsTo(Customers::class);
    }
    public function orderitems(){

        $this->hasMany(OrderItems::class);
    }
}
