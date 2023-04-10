<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin;
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

// Route::get('/', function () { return view('welcome'); });
Route::get('/', [Guest\HomeController::class, 'index'])->name('index');
Route::get('/programs', [Guest\ProgramController::class, 'list'])->name('program.list');
Route::get('/{slug}', [Guest\ProgramController::class, 'index'])->name('program.index');
Route::get('/{slug}/donate', [Guest\DonateController::class, 'amount'])->name('donate.amount');
Route::get('/{slug}/payment', [Guest\DonateController::class, 'payment'])->name('donate.payment');
Route::get('/{slug}/checkout', [Guest\DonateController::class, 'checkout'])->name('donate.checkout');
Route::get('/{slug}/payment-info', [Guest\DonateController::class, 'paymentInfo'])->name('donate.payment_info');


Route::group([
    'as'     => 'adm.',   // for route(adm.xx)
    'prefix' => 'adm'       // for uri 
], function() {
    // Login
    Route::group([
        'namespace' => 'Auth',
    ], function() {
        Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('do-login', [LoginController::class, 'login'])->name('login.submit');
        Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    });

    // Dashboard
        Route::group([
        'middleware' => ['auth']
    ], function() {
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('index');
        Route::get('/donatur-datatables', [Admin\DonaturController::class, 'datatablesDonatur'])->name('donatur.datatables');
        Route::resources([
            'program'          => Admin\ProgramController::class,
            'organization'     => Admin\OrganizationController::class,
            'program-category' => Admin\ProgramCategoryController::class,
            'donatur'          => Admin\DonaturController::class,
            'fundraiser'       => Admin\FundraiserController::class,
            'user'             => Admin\UserController::class,
            'donate'           => Admin\DonateController::class,
        ]);
    });
});





    


