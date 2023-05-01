<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MarketController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', 'App\Http\Controllers\ProductController@indexs');


Route::middleware(['auth'])->group(function () {
    Route::resource('products', ProductController::class);
    Route::resource('markets', MarketController::class);
    Route::get('/market/{id}/show-products', [MarketController::class, 'showProducts'])->name('markets.showProducts');
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
