<?php

use App\Http\Controllers\Api\CustomerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;


// routes/api.php
Route::post('/test', function() {
    return ['message' => 'OK'];
});

Route::apiResource('products', ProductController::class);
Route::apiResource('customers', CustomerController::class);








