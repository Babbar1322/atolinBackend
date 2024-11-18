<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\UserfeedbackController;
use App\Http\Controllers\Google2FAController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\PaymentInvoiceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'checkApp'], function () {
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

    //User Controller
    Route::post('login', [UserController::class, 'login'])->name('login.api');
    Route::post('register', [UserController::class, 'register'])->name('register.api');
    Route::post('resendotp', [UserController::class, 'resendOTP'])->name('resend.api');
    Route::post('validateotp', [UserController::class, 'validateOTP'])->name('validate.api');
    Route::post('forgotpassword', [UserController::class, 'forgotpassword'])->name('forgot.api');
    Route::post('/forgot/g2fa', [DashboardController::class, 'forgotG2FA']);
    Route::post('/forgot/validateg2fa', [DashboardController::class, 'validateG2FAOtp']);

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('me', [UserController::class, 'me']);

        // Route::get('details', [UserController::class, 'details'])->name('details.api');

        Route::post('validateusers', [UserController::class, 'validateusers'])->name('validateusers.api');

        Route::post('searchuser', [UserController::class, 'getUserSearch'])->name('searchuser.api');

        Route::post('search', [UserController::class, 'usersearch']);

        Route::post('logout', [UserController::class, 'logout'])->name('logout.api');

        Route::post('profileupdate', [UserController::class, 'profileUpdate'])->name('profileupdate.api');

        Route::post('profileimageupdate', [UserController::class, 'profileImageUpdate'])->name('profileimageupdate.api');

        Route::delete('deleteProfilePhoto', [UserController::class, 'deleteProfilePhoto']);

        Route::post('savefeedback', [UserfeedbackController::class, 'store'])->name('savefeedback.api');

        //Google Authentication
        Route::get('/2fa/enable', [Google2FAController::class, 'enableTwoFactorapi']);
        Route::get('/2fa/disable', [Google2FAController::class, 'disableTwoFactorapi']);
        Route::post('/g2fotpcheckenable', [Google2FAController::class, 'g2fotpcheckenable']);

        Route::get('/email/{status}', [DashboardController::class, 'emailsecurity']);

        Route::get('bannerimages', [UserController::class, 'bannerimages'])->name('bannerimage.api');

        Route::post('userpaneldetails', [PaymentController::class, 'userpaneldetails'])->name('userpaneldetails.api');

        Route::post('storenotifications', [UserController::class, 'storeNotifications'])->name('storenotifications.api');

        Route::get('getnotifications', [UserController::class, 'getNotifications'])->name('getnotifications.api');

        Route::get('withdrawhistory', [UserController::class, 'withdrawhistory'])->name('withdrawhistory.api');

        Route::post('storeprivacy', [UserController::class, 'storePrivacy'])->name('storeprivacy.api');

        Route::post('getprivacy', [UserController::class, 'getPrivacy'])->name('getprivacy.api');

        Route::post('createpin', [UserController::class, 'createPaymentPin'])->name('createpin.api');

        Route::post('updatepin', [UserController::class, 'updatePaymentPin'])->name('updatepin.api');

        Route::post('/appuserpin', [UserController::class, 'appuserpin']);

        Route::post('validateapppin', [UserController::class, 'validateAppPin']);

        Route::post('gfavalidateotp', [Google2FAController::class, 'gfavalidateotp']);

        Route::post('decline', [UserController::class, 'decline'])->name('decline.get');

        Route::get('getwalletamount', [PaymentController::class, 'getWalletAmount'])->name('getwalletamount.post');
        //KYC Document verification
        Route::get('kyc', [UserController::class, 'kyc'])->name('kycprocess.get');
        Route::post('kyc', [UserController::class, 'kycprocess'])->name('kycprocess.post');

        Route::post('validatepin', [UserController::class, 'validatePaymentPin'])->name('validatepin.api');

        Route::post('addwalletamount', [PaymentController::class, 'addAmounttoWallet'])->name('addwalletamount.post');

        Route::post('debitwalletamount', [PaymentController::class, 'debitAmountonWallet'])->name('debitwalletamount.post');

        Route::get('uniquetransactions', [PaymentController::class, 'getuniqueTransactionDetails'])->name('getuniqueTransactionDetails.post');

        Route::get('gettransactiondetails', [PaymentController::class, 'getTransactionDetails'])->name('gettransactiondetails.post');

        Route::post('transfermoney', [StripePaymentController::class, 'transfer'])->name('transfer.post');

        Route::get('getallnotifications', [UserController::class, 'getallnotifications'])->name('getallnotifications.get');
        //updatenotification
        Route::get('updatenotificationstatus', [UserController::class, 'updatenotificationstatus'])->name('updatenotificationstatus.post');

        //Stripe Start

        //Add cards to user account
        Route::post('addcard', [StripePaymentController::class, 'addCardtoUserAccount'])->name('addcard.post');
        //Delete cards to user account
        Route::post('deletecard', [StripePaymentController::class, 'deleteCardtoUserAccount'])->name('deletecard.post');
        //List All user cards
        Route::post('listallcard', [StripePaymentController::class, 'listAllUserCards'])->name('listallcard.post');

        //stripe connect
        Route::get('stripe/connect', [StripePaymentController::class, 'stripeconnect'])->name('stripeconnect');

        //Payouts
        Route::post('withdrawrequests', [UserController::class, 'withdrawrequests'])->name('withdrawrequests.get');
        Route::post('withdraw', [StripePaymentController::class, 'withdraw'])->name('withdraw.post');

        //Add Bank to user account
        Route::get("getToken", [StripePaymentController::class, 'getToken']);
        Route::post('addbank', [StripePaymentController::class, 'addBanktoUserAccount'])->name('addbank.post');
        Route::post('verifybank', [StripePaymentController::class, 'verifyUserBank'])->name('verifybank.post');
        Route::post('refreshbank', [StripePaymentController::class, 'refreshBankAccount'])->name('refreshbank.post');
        // Delete Bank Account
        Route::post('deletebank', [StripePaymentController::class, 'deleteBanktoUserAccount'])->name('deletebank.post');
        // List All user banks
        Route::post('listallbanks', [StripePaymentController::class, 'listAllAccountsOfUser'])->name('listallcard.post');
        //Verify Bank Account
        Route::post('submit-verification', [StripePaymentController::class, 'submitVerification'])->name('submit-verification.post');
        // Route::post('verifybank', [StripePaymentController::class, 'verifyBankAccount'])->name('verifybank.post');

        // List All payment methods
        Route::post('list-payment-methods', [StripePaymentController::class, 'listPaymentMethods'])->name('list-payment-methods');


        //Add Wallet Amount
        Route::post('addmoneyonstripe', [StripePaymentController::class, 'addMoneytoWallet'])->name('addmoneyonstripe.post');
        //Get Wallet Amount
        Route::post('getstripewalletamount', [StripePaymentController::class, 'getWalletAmount'])->name('getstripewalletamount.post');

        Route::get('stripe-connect', [UserController::class, 'stripeConnect']);

        //Stripe End

        //Send Card Details to payment Api
        Route::post('getcarddetails', [PaymentController::class, 'getCardDetails'])->name('getcarddetails.post');

        Route::post('requestmoney', [PaymentController::class, 'RequestMoney'])->name('requestmoney.api');

        //new data


        //get user balance
        Route::post('getBalance', [ApiController::class, 'getBalance'])->name('user.getBalance');
        Route::post('creditHistory', [ApiController::class, 'credit_history'])->name('user.creditHistory');
        Route::post('debitHistory', [ApiController::class, 'debit_history'])->name('user.debitHistory');
        Route::post('transactions', [ApiController::class, 'transactions'])->name('user.transactions');
        Route::post('transfer', [ApiController::class, 'transfer'])->name('user.transfer');

        Route::get('get-deposit-fee', [ApiController::class, 'getDepositFee'])->name('get-deposit-fee');

        // Crypto Wallet
        Route::post('create-wallet', [ApiController::class, 'createCryptoWallet']);
        Route::post('import-wallet', [ApiController::class, 'importWallet']);
        Route::post('verify-wallet', [ApiController::class, 'verifyWallet']);
        Route::post('crypto-balance', [ApiController::class, 'getCryptoBalance']);
        Route::post('crypto-balance-by-address', [ApiController::class, 'getBalanceByAddress']);
        // Route::post('send-bnb', [ApiController::class, 'sendBNB']);
        // Route::post('send-token', [ApiController::class, 'sendToken']);
        Route::post('transferCrypto', [ApiController::class, 'transferCrypto']);
        Route::get('crypto-history', [ApiController::class, 'transactionHistory']);
        Route::post('check-address', [ApiController::class, 'checkAddress']);
        Route::post('swap-token', [ApiController::class, 'swapToken']);
        Route::post('switch-network', [ApiController::class, 'switchNetwork']);
    });
    //Route::get('test-api', [ApiController::class, 'test_api']);

    //Get countries &  cities
    Route::get('getcountries', [UserController::class, 'getCountries'])->name('getcountries.get');

    Route::post('getstates', [UserController::class, 'getStates'])->name('getstates.api');

    Route::post('getcities', [UserController::class, 'getCities'])->name('getcities.api');

    //Geting Content API

    Route::get('getfaqs', [FaqController::class, 'showfaq'])->name('getfaqs.get');

    Route::get('getabout', [FaqController::class, 'showabout'])->name('getabout.get');
});

Route::any('transactionUpdate', [StripePaymentController::class, 'transactionUpdate'])->name('transactionUpdate');
Route::post('netsuite', [PaymentInvoiceController::class, 'netsuiteInvoice'])->name('netsuite');
