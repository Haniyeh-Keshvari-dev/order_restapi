<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    protected $fillable = ['order_id','product_id','quantity','unit_price'];
    public function product(){

        $this->belongsTo(Products::class);
    }

    public function order(){
        $this->belongsTo(Orders::class);
    }
}
