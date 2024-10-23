<?php

namespace App\Http\Controllers;

use App\CURL\ExternalAccount;
use App\CURL\Transaction;
use App\Encryption\Encryption;
use App\Models\CryptoTransaction;
use App\Models\TokenSwap;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserTransactionDetails;
use App\Models\UserDocument;
use DB;
use App\Models\NotificationUser;
use App\Models\Document;
use App\Models\WithdrawalRequest;
use Auth;
use Session;
use Carbon\Carbon;
use Setting;
use App\Models\Country;
use App\Models\BannerImage;
use App\Models\UserStripeTransaction;

class DashboardController extends Controller
{

    protected $redirectTo = '/dashboard';
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['privacy_policy', 'contactus', 'login']);
    }

    // public function __invoke(Request $request)
    // {
    //     //
    // }

    public function login(Request $request)
    {
        try {
            $user = User::where('email', $request->email)->first();
            if (isset($user)) {
                $admin = $user->utype;
                if ($admin == 'admin') {
                    Auth::loginUsingId($user->id);
                    return redirect()->intended($this->redirectTo);
                } else {
                    return redirect()->back()->with('flash_error', 'Entered credentials not belongs to admin');
                }
            } else {
                return redirect()->back()->with('flash_error', 'Not found');
            }
        } catch (Exception $e) {
            return back()->with('flash_error', 'Something went wrong');
        }
    }

    public static function withdrawrequests()
    {
        $allwithdrawalrequests = WithdrawalRequest::where("status", "PENDING")->get();
        return view('withdrawalrequests', compact('allwithdrawalrequests'));
    }
    public static function confirmedwithdrawrequests()
    {
        $allwithdrawalrequests = WithdrawalRequest::where("status", "!=", "PENDING")->get();
        return view('withdrawalrequests', compact('allwithdrawalrequests'));
    }

    public function privacy_policy()
    {
        return view('privacy');
    }

    public function contactus()
    {
        return view('contactus');
    }

    public function withdrawapproval(Request $request)
    {
        $withdraw = WithdrawalRequest::find($request->id);
        $user = User::findOrFail($withdraw->user_id);
        $bank = ExternalAccount::getAccountById($withdraw->bank_id);

        if (!$withdraw->bank_id || !$bank['status']) {
            return back()->with('flash_error', 'Bank id doesn\'t exist!');
        }

        // $payout = \Stripe\PaymentIntent::create([
        //     'amount' => $withdraw->amount * 100,
        //     'currency' => 'USD',
        //     'customer' => $cust_id, // Replace with the customer's Stripe ID
        // ]);
        $payout = Transaction::sendToUser($withdraw->amount, $user->priority_id, $bank['response']['guid']);

        if (!$payout['status']) {
            return back()->with('flash_error', $payout['response']);
        }

        $withdraw->status = 'APPROVED';
        $withdraw->save();

        $user = User::find($withdraw->user_id);
        UserStripeTransaction::create([
            'stripe_tid' => $payout['response']['guid'],
            'stripe_uid' => $user->priority_id,
            'card_id' => $bank['response']['guid'],
            'amount' => $withdraw->net_amount,
            'currency' => 'USD',
            't_type' => 'debit',
        ]);

        // UserTransactionDetails::create([
        //     'transaction_id' => $transaction['response']['id'],
        //     'user_id' => $user->id,
        //     'source_id' => 4006917,
        //     'receiver_id' => $user->id,
        //     'amount' => $withdraw->net_amount,
        //     't_type' => 'debit',
        //     'comments' => 'Withdraw to account',
        // ]);

        // $user->balance -= $payout->amount / 100;
        // $user->save();
        // $user->balance = $user->balance - $withdraw->amount;
        // $user->save();

        if (!isset($user)) {
            return back()->with('flash_error', 'Try again later');
        }
        $adminid = 0;
        $notificationuser = new NotificationUser;
        $notificationuser->user_id = $adminid;
        $notificationuser->receiver_id = $user->id;
        $notificationuser->message = "Your withdrawal request has been approved and the amount will be reflected shortly";
        $notificationuser->save();
        return back()->with('flash_success', 'Withdraw request has been approved successfully');
    }
    // public function withdrawapproval(Request $request){
    //     \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    //     $stripe = \Stripe::make(env('STRIPE_SECRET'));


    //     $withdraw = WithdrawalRequest::find($request->id);

    //     if (!$withdraw->bank_id) {
    //         return back()->with('flash_error','Bank id doesn\'t exist!');
    //     }


    //     // $payout = \Stripe\Transfer::create([
    //     //     'source_transaction' => $withdraw->bank_id,
    //     //     'source_type' => 'bank_account',
    //     //     'amount' => $withdraw->amount,
    //     //     'currency' => 'USD',
    //     // ]);
    //     $user = User::findOrFail($withdraw->user_id);
    //     if ($user->stripe_uid == null) {
    //         $customer = \Stripe\Customer::create([
    //             'email' => $user->email,
    //         ]);
    //         $user->stripe_uid = $customer['id'];
    //         $user->save();
    //         $cust_id = $customer['id'];
    //     } else {
    //         $cust_id = $user->stripe_uid;
    //     }
    //     $payout = \Stripe\PaymentIntent::create([
    //         'amount' => $withdraw->amount * 100,
    //         'currency' => 'USD',
    //         'customer' => $cust_id, // Replace with the customer's Stripe ID
    //     ]);
    //     // dd($payout);

    //     if ($payout->status === 'requires_payment_method') {
    //         return back()->with('flash_error','Transaction failed!');
    //     }

    //     $withdraw->status = 'APPROVED';
    //     $withdraw->save();


    //     $user = User::find($withdraw->user_id);
    //     UserStripeTransaction::create([
    //         'stripe_tid' => $payout->balance_transaction,
    //         'stripe_uid' => $user->stripe_uid,
    //         'card_id' => $payout->destination,
    //         'amount' => $payout->amount / 100,
    //         'currency' => $payout->currency,
    //         't_type' => 'debit',
    //     ]);

    //     UserTransactionDetails::create([
    //         'transaction_id' => $payout->id,
    //         'user_id' => $user->id,
    //         'source_id' => $payout->destination,
    //         'receiver_id' => $user->id,
    //         'amount' => $payout->amount / 100,
    //         't_type' => 'debit',
    //         'comments' => 'Withdraw to account',
    //     ]);

    //     $user->balance -= $payout->amount / 100;
    //     $user->save();
    //     // $user->balance = $user->balance - $withdraw->amount;
    //     // $user->save();

    //     if(!isset($user)){
    //         return back()->with('flash_error','Try again later');
    //     }
    //     $adminid = 0;
    //     $notificationuser = new NotificationUser;
    //     $notificationuser->user_id = $adminid;
    //     $notificationuser->receiver_id = $user->id;
    //     $notificationuser->message = "Your withdrawal request has been approved and the amount will be reflected shortly";
    //     $notificationuser->save();
    //     return back()->with('flash_success','Withdraw request has been approved successfully');
    // }

    public function withdrawreject(Request $request)
    {
        $withdraw = WithdrawalRequest::find($request->id);
        $withdraw->status = 'REJECTED';
        $withdraw->save();
        $user = User::find($withdraw->user_id);
        if (!isset($user)) {
            return back()->with('flash_error', 'Try again later');
        }

        // UserTransactionDetails::create([
        //     'transaction_id' => '',
        //     'user_id' => $user->id,
        //     'source_id' => $withdraw->bank_id,
        //     'receiver_id' => $user->id,
        //     'amount' => $withdraw->net_amount,
        //     't_type' => 'credit',
        //     'comments' => 'Refund for Withdrawal Request',
        //     'status' => 'Success'
        // ]);
        Wallet::create([
            'user_id' => $user->id,
            'amount' => $withdraw->net_amount,
            't_type' => 'credit',
            'status' => 'APPROVED',
            'type' => 'REFUND',
            'remarks' => 'Refund for Withdrawal Request',
        ]);

        // $user->balance += $withdraw->net_amount;
        // $user->save();

        $adminid = 0;
        $notificationuser = new NotificationUser;
        $notificationuser->user_id = $adminid;
        $notificationuser->receiver_id = $user->id;
        $notificationuser->message = "Your Withdrawal request was rejected by the admin";
        $notificationuser->save();
        return back()->with('flash_success', 'Withdraw request has been declined successfully');
    }

    // public function requeststore(Request $request){
    //     dd($request->all());
    // }

    public static function getTodaytracsactioncount()
    {

        $getTodaytracsactioncount = Wallet::whereDate('created_at', Carbon::today())->count();

        return $getTodaytracsactioncount;
    }

    public static function getTodayCustomercount()
    {

        $getTodayCustomercount = User::whereDate('created_at', Carbon::today())->count();

        return $getTodayCustomercount;
    }

    public function userdocumentapprove(Request $request)
    {
        try {
            $Kyc = UserDocument::where('user_id', $request->user_id)
                ->where('document_id', $request->doc_id)
                ->first();

            $Kyc->status = $request->status;

            $Kyc->save();

            $user = User::where('id', $request->user_id)->first();
            if ($user) {
                $user->kyc_status = $request->status;
                $user->save();
            }

            return back()->with('flash_success', 'Status Updated Successfully');
        } catch (Exception $e) {
            return back()->with('flash_error', 'Something went wrong');
        }
    }

    public function settings()
    {
        $banner_images = BannerImage::get();

        return view('settings', compact('banner_images'));
    }
    public function tokensettings()
    {
        return view('token-settings');
    }

    public function userdocumentreject(Request $request)
    {
        try {
            $Kyc = UserDocument::where('user_id', $request->user_id)
                ->where('document_id', $request->doc_id)
                ->first();

            $Kyc->status = $request->status;

            $Kyc->save();

            $user = User::where('id', $request->user_id)->first();
            if ($user) {
                $user->kyc_status = $request->status;
                $user->save();
            }

            return back()->with('flash_success', 'Status Updated Successfully');
        } catch (Exception $e) {
            return back()->with('flash_error', 'Something went wrong');
        }
    }

    public function usertransactions()
    {
        $getusersLatestTransactions =  Wallet::orderByDesc('id')->get();
        return view('usertransactionhistory', compact('getusersLatestTransactions'));
    }

    public function storedocument(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'order' => 'required',
            // 'image' => 'required',
            'image' => 'required|mimes:jpg,jpeg,bmp,png|max:2048'
        ]);

        try {

            $document = $request->all();

            if ($request->hasFile('image')) {
                $document['image'] = $request->image->store('documents');
            }

            Document::create($document);
            return back()->with('flash_success', 'Document Saved Successfully');
        } catch (Exception $e) {
            return back()->with('flash_error', 'Document Not Found');
        }
    }

    public static function getTodayCreditAmount()
    {

        $getTodayCreditAmount = Wallet::whereDate('created_at', Carbon::today())->where('t_type', 'credit')->sum('amount');

        return $getTodayCreditAmount;
    }

    public static function getTodayDebitAmount()
    {

        $getTodayDebitAmount = Wallet::whereDate('created_at', Carbon::today())->where('t_type', 'debit')->sum('amount');

        return $getTodayDebitAmount;
    }

    public static function getTotalTracsactioncount()
    {

        $getTotalTracsactioncount = Wallet::count();

        return $getTotalTracsactioncount;
    }

    public static function getTotalCustomercount()
    {

        $getTotalCustomercount = User::where('utype', 'user')->count();

        return $getTotalCustomercount;
    }

    public static function getTotalCreditAmount()
    {

        $getTotalCreditAmount = Wallet::where('t_type', 'credit')->sum('amount');

        return $getTotalCreditAmount;
    }

    public static function getTotalDebitAmount()
    {

        $getTotalDebitAmount = Wallet::where('t_type', 'debit')->sum('amount');

        return $getTotalDebitAmount;
    }

    public static function getTotalSwapFee() {

        $fee = TokenSwap::get();

        $getTotalSwapFee = $fee->sum('fee_amount');

        return $getTotalSwapFee;
    }
    public static function getTotalGasFee() {

        $fee = CryptoTransaction::where('type', 'gas_fee')->sum('amount');

        return $fee;
    }
    public static function getTotalWallet() {

        $credit = Wallet::where('t_type', 'credit')->where('status', 'APPROVED')->sum('amount');
        $debit = Wallet::where('t_type', 'debit')->where('status', 'APPROVED')->sum('amount');

        return $credit - $debit;
    }

    public static function getLatestTransactions()
    {

        $getLatestTransactions = Wallet::whereNotIn('t_type', ['request'])->orderByDesc('id')->limit(5)->get();
        // dd($amt_perday);

        return view('dashboard',  compact('getLatestTransactions'));
    }

    public static function getUsers()
    {

        $getUsers = User::whereNotIn('utype', ['admin'])->orderByDesc('id')->get();

        return view('userslist',  compact('getUsers'));
    }

    public static function getUserDetails(Request $request)
    {

        $userid = request('id');

        $getUserDetails = User::where('id', $userid)->first();
        // dd($getUserDetails);

        $country = Country::where('id', $getUserDetails['country_code'])->first();
        $getcountry = $country->name;
        // dd($getUserDetails1);
        $balance = Wallet::balance($userid);

        $getuserLatestTransactions =  Wallet::orderByDesc('id')->where('user_id', $userid)->get();

        return view('usersdetails',  compact('getUserDetails', 'getuserLatestTransactions', 'getcountry', 'balance'));
    }

    public static function getKycDetails(Request $request)
    {

        $userid = request('id');

        $getKycdetails = UserDocument::where('user_id', $userid)->get();

        return view('kycdetails',  compact('getKycdetails'));
    }

    public static function getUserTransactionDetails()
    {

        $transactionid = request('id');

        $getUserTransactionDetails = Wallet::where('id', $transactionid)->first();


        return view('transactiondetails',  compact('getUserTransactionDetails'));
    }

    public function settings_store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|max:30',
            'site_logo' => 'mimes:jpg,jpeg,bmp,png|max:2048',
            'copyright_content' => 'required',
        ]);

        if ($request->hasFile('site_logo')) {
            $site_logo = $request->site_logo->store('settings');
            Setting::set('site_logo', $site_logo);
        }
        Setting::set('site_name', $request->name);
        Setting::set('copyright_content', $request->copyright_content);
        Setting::set('withdraw_fees', $request->withdraw_fees);
        Setting::set('bank_fee', $request->bank_fee);
        Setting::set('card_fee', $request->card_fee);
        // Setting::set('refferal_bonus', $request->referral_bonus);
        Setting::save();

        return back()->with('flash_success', 'Settings updated successfully');
    }
    public function tokensettings_store(Request $request)
    {
        $this->validate($request, [
            'token_address' => 'required',
            'token_price' => 'required|numeric',
            'wallet_private' => 'required',
            'wallet_address' => 'required',
            'swap_fee' => 'required|numeric',
        ]);

        Setting::set('token_address', $request->token_address);
        Setting::set('token_price', $request->token_price);
        Setting::set('swap_fee', $request->swap_fee);
        Setting::set('wallet_private', Encryption::encrypt($request->wallet_private));
        Setting::set('wallet_address', Encryption::encrypt($request->wallet_address));
        Setting::set('token_network', $request->token_network);
        Setting::save();

        return back()->with('flash_success', 'Settings updated successfully');
    }

    public function gasFeeHistory() {
        $history = CryptoTransaction::where('type', 'gas_fee')->get();
        return view('gas-fee-transaction', compact('history'));
    }

    public function tokenSwapHistory() {
        $history = TokenSwap::with('user')->orderByDesc('id')->get();
        return view('token-swap-history', compact('history'));
    }
    public function tokenSwapFeeHistory() {
        $history = TokenSwap::with('user')->orderByDesc('id')->get();
        $atolinHistory = TokenSwap::where('from', 'TOKEN')->get();
        $tokenHistory = TokenSwap::where('from', 'ATOLIN')->get();
        $totalSwapFee = $atolinHistory->sum('fee_amount');
        $totalTokenFee = $tokenHistory->sum('fee_amount');
        return view('token-swap-fee-history', compact('history', 'totalSwapFee', 'totalTokenFee'));
    }
    public function tokenSwapDetails(TokenSwap $swap) {
        return view('swap-details', compact('swap'));
    }

    public static function getAllTransactions()
    {

        $getAllTransactions = Wallet::orderByDesc('id')->get();

        return view('transactionlist',  compact('getAllTransactions'));
    }

    public function forgotG2FA(Request $request)
    {
        try {
            if (!$request->ajax()) {
                $id = session('2fa:user:id');
                $user = \App\User::find($id);
                $request['email'] = $user->email;
            }
            $this->validate($request, [
                'email' => 'required|email|exists:mysql.users,email'
            ]);
            $checkUser = \App\User::where('email', $request->email)->first();
            if ($checkUser) {
                if ($checkUser->g2f_status != "1" && is_null($checkUser->google2fa_secret)) {
                    if ($request->ajax())
                        return response()->json(['error' => ['msg' => 'user does not enabled 2FA']], 400);
                    else
                        return back()->with('flash_error', 'user does not enabled 2FA');
                }

                $checkPRevious = \App\Forgot2fa::where('token', $checkUser->email_token)->whereNull('g2fa_key')->get();
                if (count($checkPRevious) > 0) {
                    $currentTime = new \DateTime;
                    foreach ($checkPRevious as $key => $value) {
                        $diff = $value->created_at->diffInMinutes($currentTime);
                        if ($diff > 3) {
                            $value->g2fa_key = "EXPIRED";
                            $value->save();
                        } else {
                            if ($request->ajax())
                                return response()->json(['success' => ['msg' => 'We have sent 6 Digits OTP number to your registered email.']], 200);
                            else
                                // \Session::flash('flash_success','We have sent OTP to your email.');
                                return view('2fa.validate');
                        }
                    }
                }

                $otp = rand(100000, 999999);
                $checkOTP = \App\Forgot2fa::where('otp', $otp)->whereNull('g2fa_key')->get();
                if (count($checkOTP) > 0) {
                    $otp = rand(100000, 999999);
                }
                $reset2FA = new \App\Forgot2fa;
                $reset2FA->token = $checkUser->email_token;
                $reset2FA->otp = $otp;
                $reset2FA->save();
                $data['otp'] = $otp;
                $data['email_otp'] = false;
                Mail::to($checkUser->email)->send(new DisableGFA($data));
                if ($request->ajax())
                    return response()->json(['success' => ['msg' => 'We have sent 6 Digits OTP number to your registered email.']], 200);
                else
                    \Session::flash('flash_success', 'We have sent 6 Digits OTP number to your registered email.');
                return view('2fa.validate');
            } else {
                if ($request->ajax())
                    return response()->json(['error' => ['msg' => 'E-mail does not exits in our record.']], 400);
                else
                    return back()->with('flash_error', 'E-mail does not exits in our Record.');
            }
        } catch (Exception $e) {
            return response()->json(['error' => ['msg' => 'Something Went Wrong']], 500);
        }
    }

    public function emailsecurity(Request $request, $status)
    {
        try {
            if (isset($status) && !is_null($status)) {
                $user = Auth::user();
                if ($status == "enable") {
                    $user->email_login = '1';
                } else {
                    $user->email_login = '0';
                }
                $user->save();
                return response()->json(['success' => ['msg' => 'Email Security ' . ucfirst($status) . ' Successfully']], 200);
            }
            return response()->json(['error' => ['msg' => 'Something went wrong']], 400);
        } catch (\Throwable $th) {
            \Log::critical('securityChange', ['message' => $th->getMessage()]);
            return response()->json(['error' => ['msg' => 'Unable to fetch security data. Please try again later']], 500);
        }
    }

    public function validateG2FAOtp(Request $request)
    {
        try {

            if (!$request->ajax()) {
                $id = session('2fa:user:id');
                $user = \App\User::find($id);
                $request['email'] = $user->email;
            }
            $this->validate($request, [
                'otp' => 'required|digits:6',
                'email' => 'required|email',
            ]);
            $user = \App\User::where('email', $request->email)->first();
            if ($user) {
                $token = $user->email_token;
            } else {
                $token = "EMPTY";
            }
            $checkOTP = \App\Forgot2fa::where('token', $token)->where('otp', $request->otp)->whereNull('g2fa_key')->first();
            if ($checkOTP) {
                if ($checkOTP->otp != $request->otp) {
                    if ($request->ajax())
                        return response()->json(['error' => ['msg' => 'OTP is Invalid']], 400);
                    else
                        return back()->with('flash_error', 'OTP is Invalid.');
                }
                $currentTime = new \DateTime;
                $diff = $checkOTP->created_at->diffInMinutes($currentTime);
                if ($diff > 3) {
                    $checkOTP->g2fa_key = "EXPIRED";
                    $checkOTP->save();
                    if ($request->ajax())
                        return response()->json(['error' => ['msg' => 'OTP is Expired.']], 400);
                    else
                        return back()->with('flash_error', 'OTP is Expired.');
                }
                $user = \App\User::where('email_token', $token)->first();
                $user->g2f_status = '0';
                $checkOTP->g2fa_key = $user->google2fa_secret;
                $checkOTP->verified_at = new \DateTime;
                $checkOTP->save();
                $user->google2fa_secret = null;
                $user->save();

                if ($request->ajax())
                    return response()->json(['success' => ['msg' => 'Your 2FA disabled successfully']], 200);
                else
                    return redirect('/login')->with('flash_success', 'Your 2FA disabled successfully');
            } else {
                if ($request->ajax())
                    return response()->json(['error' => ['msg' => 'OTP is Invalid']], 400);
                else
                    return back()->with('flash_error', 'Invalid OTP');
            }
        } catch (Exception $e) {
            return response()->json(['error' => ['msg' => 'Something Went Wrong']], 500);
        }
    }
}
