<?php

use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;


// routes/api.php
Route::post('/test', function() {
    return ['message' => 'OK'];
});

Route::apiResource('products', ProductController::class);
Route::apiResource('customers', CustomerController::class);
Route::apiResource('orders', OrderController::class);








