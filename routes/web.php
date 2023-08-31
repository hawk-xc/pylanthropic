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
Route::get('/payment/callback-doku', [PaymentController::class, 'callbackDoku'])->name('payment.callback.doku');
Route::get('/donatur/wa-check', [Admin\DonaturController::class, 'talentWACheck']);
Route::get('/donatur/wa-dorman', [Admin\DonaturController::class, 'waDorman']);

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

        // DATATABLES
        Route::get('/donatur-datatables', [Admin\DonaturController::class, 'datatablesDonatur'])->name('donatur.datatables');
        Route::get('/donatur-dorman-datatables', [Admin\DonaturController::class, 'datatablesDonaturDorman'])->name('donatur.dorman.datatables');
        Route::get('/donatur-tetap-datatables', [Admin\DonaturController::class, 'datatablesDonaturTetap'])->name('donatur.tetap.datatables');
        Route::get('/donatur-sultan-datatables', [Admin\DonaturController::class, 'datatablesDonaturSultan'])->name('donatur.sultan.datatables');
        Route::get('/donatur-hampir-datatables', [Admin\DonaturController::class, 'datatablesDonaturHampir'])->name('donatur.hampir.datatables');
        Route::get('/donate-datatables', [Admin\DonateController::class, 'datatablesDonate'])->name('donate.datatables');
        Route::get('/donate-perdonatur-datatables/{id}', [Admin\DonateController::class, 'datatablesDonatePerdonatur'])->name('donate.donatur.datatables');
        Route::get('/program-datatables', [Admin\ProgramController::class, 'datatablesProgram'])->name('program.datatables');
        Route::get('/program-dashboard-datatables', [Admin\ProgramController::class, 'datatablesProgramDashboard'])->name('program.dashboard.datatables');
        Route::get('/report/settlement/mutation-datatables', [Admin\ReportController::class, 'datatablesMutation'])->name('report.mutation.datatables');
        Route::get('/report/settlement/transaction-datatables', [Admin\ReportController::class, 'datatablesTransaction'])->name('report.transaction.datatables');
        Route::get('/chat-datatables', [Admin\ChatController::class, 'datatablesChat'])->name('chat.datatables');

        Route::get('/program-show-donate', [Admin\ProgramController::class, 'showDonate'])->name('program.show.donate');
        Route::get('/program-show-summary', [Admin\ProgramController::class, 'showSummary'])->name('program.show.summary');
        Route::post('/program-show-spend', [Admin\ProgramController::class, 'showSpend'])->name('program.spend.show');
        Route::post('/program-submit-spend', [Admin\ProgramController::class, 'submitSpend'])->name('program.spend.submit');

        Route::post('/donate-status-edit', [Admin\DonateController::class, 'statusEdit'])->name('donate.status.edit');
        Route::post('/donate-fu-paid', [Admin\DonateController::class, 'fuPaid'])->name('donate.fu.paid');
        Route::get('/donate-perdonatur/{id}', [Admin\DonateController::class, 'donatePerdonatur'])->name('donate.perdonatur');

        // DONATUR
        Route::get('/donatur/dorman', [Admin\DonaturController::class, 'dorman'])->name('donatur.dorman');
        Route::get('/donatur/tetap', [Admin\DonaturController::class, 'tetap'])->name('donatur.tetap');
        Route::get('/donatur/sultan', [Admin\DonaturController::class, 'sultan'])->name('donatur.sultan');
        Route::get('/donatur/hampir', [Admin\DonaturController::class, 'hampir'])->name('donatur.hampir');
        Route::get('/donatur/update-donate', [Admin\DonaturController::class, 'donateUpdate'])->name('donatur.donate.update');

        // REPORT
        Route::get('/report/collection', [Admin\ReportController::class, 'collection'])->name('report.collection');
        Route::get('/report/monthly', [Admin\ReportController::class, 'monthly'])->name('report.monthly');
        Route::get('/report/matching-transaction', [Admin\ReportController::class, 'mutationMatching'])->name('report.matching');
        Route::get('/report/settlement', [Admin\ReportController::class, 'settlement'])->name('report.settlement');
        Route::post('/report/mutation/edit', [Admin\ReportController::class, 'mutationEdit'])->name('report.mutation.edit');

        // SELECT2
        Route::get('/organization-select2-all', [Admin\OrganizationController::class, 'select2'])->name('organization.select2.all');
        Route::get('/category-select2-all', [Admin\ProgramCategoryController::class, 'select2'])->name('category.select2.all');

        Route::resources([
            'program'          => Admin\ProgramController::class,
            'organization'     => Admin\OrganizationController::class,
            'program-category' => Admin\ProgramCategoryController::class,
            'donatur'          => Admin\DonaturController::class,
            'fundraiser'       => Admin\FundraiserController::class,
            'user'             => Admin\UserController::class,
            'donate'           => Admin\DonateController::class,
            'chat'             => Admin\ChatController::class,
        ]);
    });
});



Route::get('/programs', [Guest\ProgramController::class, 'list'])->name('program.list');
Route::get('/donasi/status', [Guest\DonateController::class, 'paymentStatus'])->name('donate.status');
Route::post('/donasi/status-check/{inv}', [Guest\DonateController::class, 'paymentStatusCheck'])->name('donate.status.check');
Route::get('/{slug}', [Guest\ProgramController::class, 'index'])->name('program.index');
Route::get('/{slug}/info', [Guest\ProgramController::class, 'info'])->name('program.info');
Route::get('/{slug}/donate', [Guest\DonateController::class, 'amount'])->name('donate.amount');
Route::get('/{slug}/payment/{nominal}', [Guest\DonateController::class, 'payment'])->name('donate.payment');
Route::get('/{slug}/checkout/{nominal}/{type}', [Guest\DonateController::class, 'checkout'])->name('donate.checkout');
Route::post('/{slug}/checkout-do', [Guest\DonateController::class, 'checkoutDo'])->name('donate.checkout.do');
Route::post('/{slug}/payment-info', [Guest\DonateController::class, 'paymentInfo'])->name('donate.payment_info');
Route::post('/{slug}/program-read-more-count', [Guest\ProgramController::class, 'countReadMore'])->name('program.count.read_more');

