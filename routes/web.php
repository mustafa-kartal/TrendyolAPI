<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;


Route::get('/', function (){
    return to_route("homeKeyword");
})->name("home");
Route::get('/home/{keyword?}', [HomeController::class, "index"])->name("homeKeyword");
Route::post('/update-price-and-inventory', [HomeController::class, "updatePriceAndInventory"])->name("updatePriceAndInventory");
Route::get('/check/{batchRequestId?}', [HomeController::class, "checkBatchStatus"])->name("checkBatchStatus");
Route::get('/get-products', [ProductController::class, "index"])->name("getProducts");
