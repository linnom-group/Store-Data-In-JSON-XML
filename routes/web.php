<?php

use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/products/store', [ProductsController::class, 'store'])->name('products.store');
Route::get('/json', [ProductsController::class, 'index'])->name('json.data');
