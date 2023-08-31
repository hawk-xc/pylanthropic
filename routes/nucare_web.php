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
Route::get('/', [Guest\NucareController::class, 'index'])->name('index');
Route::get('/form', [Guest\NucareController::class, 'form'])->name('form');
Route::post('/submit', [Guest\NucareController::class, 'submit'])->name('submit');
Route::get('/terimakasih', [Guest\NucareController::class, 'thanks'])->name('thanks');
