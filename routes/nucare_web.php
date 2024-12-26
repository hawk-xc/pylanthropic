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
Route::get('/', [Guest\NucareController::class, 'index'])->name('nucare.index');
Route::get('/form', [Guest\NucareController::class, 'form'])->name('nucare.form');
Route::post('/submit', [Guest\NucareController::class, 'submit'])->name('nucare.submit');
Route::get('/terimakasih', [Guest\NucareController::class, 'thanks'])->name('nucare.thanks');
