<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentResource;
use App\Http\Controllers\BannerResource;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\PaymentInvoiceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth/login');
});

/*Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard'); */

Route::post('/login', [DashboardController::class, 'login'])->name('login');
Route::get('/privacy_policy', [DashboardController::class, 'privacy_policy'])->name('privacy');
Route::get('/contactus', [DashboardController::class, 'contactus'])->name('contacts');
Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::get('/dashboard', [DashboardController::class, 'getLatestTransactions'])->name('dashboard');


    Route::get('/withdrawrequests', [DashboardController::class, 'withdrawrequests'])->name('withdrawrequests');

    // Route::post('/requeststore', [DashboardController::class, 'requeststore'])->name('requeststore');

    Route::get('/usermanagement', [DashboardController::class, 'getUsers'])->name('usermanagement');

    Route::post('/userdocument/approve', [DashboardController::class, 'userdocumentapprove'])->name('userdocument.approve');

    Route::get('/withdrawreject', [DashboardController::class, 'withdrawreject'])->name('withdrawreject');

    Route::get('/withdrawapproval', [DashboardController::class, 'withdrawapproval'])->name('withdrawapproval');

    Route::post('/userdocument/reject', [DashboardController::class, 'userdocumentreject'])->name('userdocument.reject');

    Route::resource('document', DocumentResource::class);

    Route::resource('banner', BannerResource::class);

    Route::get('/sitesettings', [DashboardController::class, 'settings'])->name('sitesettings');

    Route::post('/settingstore', [DashboardController::class, 'settings_store'])->name('settings.store');

    Route::get('/tokensettings', [DashboardController::class, 'tokensettings'])->name('tokensettings');

    Route::post('/tokensettingstore', [DashboardController::class, 'tokensettings_store'])->name('tokensettings.store');

    Route::get('/kycdoc/{id}', [DashboardController::class, 'getKycDetails'])->name('user');

    Route::get('/user/{id}', [DashboardController::class, 'getUserDetails'])->name('user');

    Route::get('/transaction/{id}', [DashboardController::class, 'getUserTransactionDetails'])->name('user');

    Route::get('/transactions', [DashboardController::class, 'getAllTransactions'])->name('transactions');


    //new code
    Route::get('/sendBalance/{id}', [ApiController::class, 'sendBalance'])->name('sendBalance');
    Route::post('/storeBalance/{id}', [ApiController::class, 'storeBalance'])->name('storeBalance');

    Route::delete('delete-user/{id}', [ApiController::class, 'deleteUser'])->name('delete-user');
});
Route::get('/payment', [PaymentInvoiceController::class, 'showForm'])->name('pay');
Route::post('/payment', [PaymentInvoiceController::class, 'makePayment'])->name('makePayment');
Route::get('/payment-card', [PaymentInvoiceController::class, 'showCardForm'])->name('pay-card');
Route::post('/payment-card', [PaymentInvoiceController::class, 'makeCardPayment'])->name('makeCardPayment');
