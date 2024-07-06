<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Guest;
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

// Route::get('/', function () { return 'welcome'; });
Route::get('/', [Guest\QurbanController::class, 'index'])->name('index');
Route::get('/cart', [Guest\QurbanController::class, 'cart'])->name('cart');
Route::get('/payment/{id}', [Guest\QurbanController::class, 'payment'])->name('payment');
Route::get('/checkout/{id}/{payment}', [Guest\QurbanController::class, 'checkout'])->name('checkout');
Route::post('/submit/{id}', [Guest\QurbanController::class, 'submit'])->name('qurban.submit');
Route::get('/{inv}/payment-info', [Guest\QurbanController::class, 'paymentInfo'])->name('payment_info');
