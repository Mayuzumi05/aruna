<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::resource('/cashier', \App\Http\Controllers\CashierController::class);
// Route::get('/menus', [CashierController::class, 'index'])->name('menus.index');
Route::get('/cashier/category/{category}', [CashierController::class, 'filterByCategory'])->name('menus.category');
Route::resource('/admin', \App\Http\Controllers\AdminController::class);
Route::post('/checkout', [CashierController::class, 'checkout'])->name('checkout');