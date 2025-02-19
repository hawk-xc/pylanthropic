<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Guest;
use App\Http\Controllers\MutasiController;
use App\Http\Controllers\FbAdsController;
use App\Http\Controllers\DonationController;
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
Route::post('/callback.php', [App\Http\Controllers\WaBlastController::class, 'callbackRuangWa'])->name('wa.callback.ruangwa');
Route::get('/payment/callback-doku', [PaymentController::class, 'callbackDoku'])->name('payment.callback.doku');

// Donatur auto check
Route::get('/donatur/wa-check', [DonationController::class, 'talentWACheck']);
Route::get('/donatur/wa-dorman', [DonationController::class, 'waDorman']);
Route::get('/donatur/wa-summary-donate', [DonationController::class, 'waSummaryDonate']);
Route::get('/donatur/wa-promosi-program', [DonationController::class, 'waProgramSpecific']);

// FU GOPAY ke-1
Route::get('/donatur/wa-fu-1-gopay', [DonationController::class, 'donateFu1Gopay']);
// FU Selain Gopay, QRIS, BCA ke-1
Route::get('/donatur/wa-fu-1-bank-transfer-non-bca', [DonationController::class, 'donateFu1BankTransfer']);
// FU Trans ke-2
Route::get('/donatur/wa-fu-2', [DonationController::class, 'donateFu2Sc']);

// Sum Donate Nominal to Program
Route::get('/donate/sum-donate', [DonationController::class, 'sumDonate']);

// Sum Donate Nominal to Donatur
Route::get('/donate/sum-donate-donatur', [DonationController::class, 'donateUpdate'])->name('refresh.donatur.donate');

// Cancel Transaction with status=draft dan created at before 5 days ago
Route::get('/donate/cancel-transaction-status-5day-ago', [DonationController::class, 'updateTransactionStatus']);

// TELEGRAM NOTIFICATION
Route::post('/notification/telegram/{invoice}', [Guest\DonateController::class, 'sendNotifTelegram'])->name('notif.telegram.newdonate');

// MUTASIBANK
Route::post('/callback_mutasibank_mandiri', [MutasiController::class, 'index']);

// FB ADS API
Route::get('/fb-ads-list-campaign', [FbAdsController::class, 'index']);
Route::get('/fb-ads-detail-per-campaign', [FbAdsController::class, 'detailPerCampaign']);
Route::get('/fb-ads-auto-rules-off', [FbAdsController::class, 'autoRulesOff']);
Route::get('/fb-ads-auto-rules-on', [FbAdsController::class, 'autoRulesOn']);
Route::get('/fb-ads-auto-get-spend', [FbAdsController::class, 'getSpend']);

// Page Campaigner
Route::get('/campaigner/{id}', [Guest\ProgramController::class, 'campaigner'])->name('campaigner');

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
        Route::get('/donate-mutation-datatables', [Admin\DonateController::class, 'datatablesDonateMutation'])->name('donate.mutation.datatables');
        Route::get('/donate-qurban-datatables', [Admin\DonateController::class, 'datatablesDonateQurban'])->name('donate.qurban.datatables');
        Route::get('/donate-perdonatur-datatables/{id}', [Admin\DonateController::class, 'datatablesDonatePerdonatur'])
        ->name('donate.donatur.datatables');
        Route::get('/program-datatables', [Admin\ProgramController::class, 'datatablesProgram'])->name('program.datatables');
        Route::get('/program-dashboard-datatables', [Admin\ProgramController::class, 'datatablesProgramDashboard'])
        ->name('program.dashboard.datatables');
        Route::get('/fundraiser-dashboard-datatables', [Admin\DonateController::class, 'datatablesFundraiserDashboard'])
        ->name('fundraiser.dashboard.datatables');
        Route::get('/report/settlement/mutation-datatables', [Admin\ReportController::class, 'datatablesMutation'])
        ->name('report.mutation.datatables');
        Route::get('/report/settlement/transaction-datatables', [Admin\ReportController::class, 'datatablesTransaction'])
        ->name('report.transaction.datatables');
        Route::get('/chat-datatables', [Admin\ChatController::class, 'datatablesChat'])->name('chat.datatables');

        // PROGRAM
        Route::get('/program-show-donate', [Admin\ProgramController::class, 'showDonate'])->name('program.show.donate');
        Route::get('/program-show-summary', [Admin\ProgramController::class, 'showSummary'])->name('program.show.summary');
        Route::get('/program-show-spend', [Admin\ProgramController::class, 'showSpend'])->name('program.spend.show');
        Route::post('/program-submit-spend', [Admin\ProgramController::class, 'submitSpend'])->name('program.spend.submit');
        Route::post('/program-submit-image-content', [Admin\ProgramController::class, 'storeImagecontent'])->name('program.image.content.submit');
        Route::post('/program-chek-url', [Admin\ProgramController::class, 'checkUrl'])->name('program.create.check_url');
        Route::get('/program-detail-stats/{id}', [Admin\ProgramController::class, 'detailStats'])->name('program.detail.stats');
        Route::get('/program-detail-donatur/{id}', [Admin\ProgramController::class, 'detailDonatur'])->name('program.detail.donatur');
        Route::get('/program-detail-fundraiser/{id}', [Admin\ProgramController::class, 'detailFundraiser'])->name('program.detail.fundraiser');
        Route::get('/program-visitor-stats', [Admin\ProgramController::class, 'statsVisitor'])->name('program.visitor.stats');
        Route::get('/program-donate-performance', [Admin\ProgramController::class, 'donatePerformance'])->name('program.donate.performance');
        Route::get('/program-select2-all', [Admin\ProgramController::class, 'select2'])->name('program.select2.all');

        // PROGRAM SPENT
        Route::get('/program-spent', [Admin\SpentController::class, 'index'])->name('spent.index');
        Route::get('/program-spent-datatables', [Admin\SpentController::class, 'spentDatatables'])->name('spent.datatables');

        // PROGRAM PAYOUT
        Route::get('/program-payout', [Admin\PayoutController::class, 'index'])->name('payout.index');
        Route::get('/program-payout-datatables', [Admin\PayoutController::class, 'payoutDatatables'])->name('payout.datatables');

        // DONATE
        Route::post('/donate-status-edit', [Admin\DonateController::class, 'statusEdit'])->name('donate.status.edit');
        Route::post('/donate-fu-paid', [Admin\DonateController::class, 'fuPaid'])->name('donate.fu.paid');
        Route::get('/donate-perdonatur/{id}', [Admin\DonateController::class, 'donatePerdonatur'])->name('donate.perdonatur');
        Route::post('/donate-check-alarm', [Admin\DonateController::class, 'donateCheckAlarm'])->name('donate.check.alarm');
        Route::get('/donate-mutation-list', [Admin\DonateController::class, 'donateMutation'])->name('donate.mutation');
        Route::get('/donate-qurban-list', [Admin\DonateController::class, 'donateQurban'])->name('donate.qurban');
        Route::get('/donate-manual-add/{id}', [Admin\DonateController::class, 'manualAdd'])->name('donate.manual_add');
        Route::post('/donate-auto-add', [Admin\DonateController::class, 'autoAdd'])->name('donate.auto_add');

        // ADS
        Route::get('/ads-need-action', [Admin\AdsController::class, 'adsNeedAction'])->name('ads.need.action');
        Route::post('/ads-need-action-update-status', [Admin\AdsController::class, 'adsNeedActionStatusChange'])->name('ads.need.action.status.update');
        Route::get('/ads-campaign-list', [Admin\AdsController::class, 'campaignList'])->name('ads.campaign.index');
        Route::get('/ads-campaign-list-datatables', [Admin\AdsController::class, 'datatablesCampaign'])->name('ads.campaign.datatables');
        Route::get('/ads-balance-status', [Admin\AdsController::class, 'balanceStatus'])->name('ads.balance');
        Route::get('/ads-roas', [Admin\AdsController::class, 'roas'])->name('ads.roas');
        Route::get('/ads-select2-all', [Admin\AdsController::class, 'select2'])->name('ads.select2.all');
        Route::get('/ads-get-new-campaign', [Admin\AdsController::class, 'getNewCampaign'])->name('ads.get.new.campaign');

        // DONATUR
        Route::get('/donatur/dorman', [Admin\DonaturController::class, 'dorman'])->name('donatur.dorman');
        Route::get('/donatur/tetap', [Admin\DonaturController::class, 'tetap'])->name('donatur.tetap');
        Route::get('/donatur/sultan', [Admin\DonaturController::class, 'sultan'])->name('donatur.sultan');
        Route::get('/donatur/hampir', [Admin\DonaturController::class, 'hampir'])->name('donatur.hampir');
        Route::get('/donatur/update-donate', [Admin\DonaturController::class, 'donateUpdate'])->name('donatur.donate.update');

        // REPORT
        Route::get('/report/collection', [Admin\ReportController::class, 'collection'])->name('report.collection');
        Route::get('/report/monthly', [Admin\ReportController::class, 'monthly'])->name('report.monthly');
        Route::get('/report/monthly-to-monthly', [Admin\ReportController::class, 'monthlyToMonthly'])->name('report.mtm');
        Route::get('/report/matching-transaction', [Admin\ReportController::class, 'mutationMatching'])->name('report.matching');
        Route::get('/report/settlement', [Admin\ReportController::class, 'settlement'])->name('report.settlement');
        Route::post('/report/mutation/edit', [Admin\ReportController::class, 'mutationEdit'])->name('report.mutation.edit');

        // REPORT AUTOMATE
        Route::get('/report-auto/monthly', [Admin\ReportAutomateController::class, 'monthly'])->name('report.auto.monthly');
        Route::get('/report-auto/monthly/donatur-list', [Admin\ReportAutomateController::class, 'donaturList'])->name('report.auto.monthly.donatur.list');
        Route::get('/report-auto/monthly/donatur-update', [Admin\ReportAutomateController::class, 'updateDonate'])->name('report.auto.monthly.donatur.update');

        // SELECT2
        Route::get('/organization-select2-all', [Admin\OrganizationController::class, 'select2'])->name('organization.select2.all');
        Route::get('/category-select2-all', [Admin\ProgramCategoryController::class, 'select2'])->name('category.select2.all');

        // LEADS
        Route::get('/grab-amalsholeh', [Admin\LeadsController::class, 'grabLeadsAmalsholeh'])->name('leads.grab.amalsholeh');
        Route::get('/grab-sharinghappiness', [Admin\LeadsController::class, 'grabLeadsSharingHappiness'])->name('leads.grab.sharinghappiness');
        Route::get('/list-leads-grab', [Admin\LeadsController::class, 'grabList'])->name('leads.grab.list');
        Route::get('/list-leads-grab-datatables', [Admin\LeadsController::class, 'grabDatatables'])->name('leads.grab.datatables');
        Route::get('/list-leads', [Admin\LeadsController::class, 'index'])->name('leads.index');
        Route::get('/list-leads-datatables', [Admin\LeadsController::class, 'leadsDatatables'])->name('leads.datatables');
        Route::get('/list-leads-grab-status', [Admin\LeadsController::class, 'editStatusGrab'])->name('leads.grab.status');
        Route::get('/leads-org-edit/{id}', [Admin\LeadsController::class, 'editOrganization'])->name('leads.org.edit');
        Route::post('/leads-org-update/{id}', [Admin\LeadsController::class, 'updateOrganization'])->name('leads.org.update');
        Route::post('/leads-org-chat', [Admin\LeadsController::class, 'waOrganization'])->name('leads.org.chat');
        Route::get('/leads-org-list', [Admin\LeadsController::class, 'listOrganization'])->name('leads.org.list');
        Route::get('/list-leads-org-datatables', [Admin\LeadsController::class, 'orgDatatables'])->name('leads.org.datatables');
        Route::get('/list-leads-org-add', [Admin\LeadsController::class, 'orgCreate'])->name('leads.org.add');
        Route::post('/list-leads-org-store', [Admin\LeadsController::class, 'orgStore'])->name('leads.org.store');

        // Organization
        Route::get('/datatables-org', [Admin\OrganizationController::class, 'orgDatatables'])->name('org.datatables');

        Route::resources([
            'program'          => Admin\ProgramController::class,
            'organization'     => Admin\OrganizationController::class,
            'program-category' => Admin\ProgramCategoryController::class,
            'donatur'          => Admin\DonaturController::class,
            'fundraiser'       => Admin\FundraiserController::class,
            'user'             => Admin\UserController::class,
            'donate'           => Admin\DonateController::class,
            'chat'             => Admin\ChatController::class,
            'ads'              => Admin\AdsController::class,
            'payout'           => Admin\PayoutController::class,
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

