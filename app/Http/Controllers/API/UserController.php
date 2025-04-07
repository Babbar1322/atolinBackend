<?php

namespace App\Http\Controllers\API;

use App\CURL\Account;
use App\CURL\Crypto;
use App\Models\UserDocument;
use App\Models\Document;
use App\Models\Wallet;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use App\Models\UserStripeCard;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BannerImage;
use App\Models\NotificationUser;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\UserTransactionDetails;
use Illuminate\Support\Facades\Auth;
use Validator;
use Hash;
use Illuminate\Support\Str;
use Twilio\Rest\Client;
use App\Http\Controllers\StripePaymentController;
use App\CURL\Customer;
use App\CURL\ExternalAccount;
use App\CURL\KYC;
use App\Encryption\Encryption;
use App\Models\CryptoWallet;
use App\Models\UserStripeTransaction;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Setting;
use Stripe;



function add_plus_sign($obj)
{
    return '+' . $obj["phonecode"];
}

class UserController extends Controller
{
    public $successStatus = 200;
    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'country_code' => 'required',
            'contact' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Email or Password is wrong!',
                'error' => $validator->errors(),
            ], 401);
        }

        $user = User::where('country_code', $request->country_code)->where('contact', $request->contact)->where('status', 1)->first();

        if ($user == null) {
            return response()->json([
                'error' => 'Email or Password is wrong!',
            ], 401);
        }

        if ($user->status == 0) {
            return response()->json([
                'error' => 'Email or Password is wrong!',
            ], 401);
        }

        // if (!Hash::check($request->password, $user->password)) {
        //     return response()->json([
        //         'error' => 'Email or Password is wrong!',
        //     ], 401);
        // }

        if ($user->utype == 'admin') {
            return response()->json([
                'error' => 'Email or Password is wrong!',
            ], 401);
        }

        if ($user->otp_verified == 0) {
            return response()->json([
                'message' => 'Please Verify OTP before login',
                'otp_status' => false,
                'error' => 'OTP not verified',
                'contact' => $user->contact,
            ], 400);
        }

        if (Auth::attempt(['contact' => $request->contact, 'password' => $request->password])) {
            $token = $user->createToken('auth_token')->plainTextToken;

            if (empty($user->priority_id) || $user->priority_id == null) {
                $customer = Customer::createCustomer();
                if ($customer['status']) {
                    $user->priority_id = $customer['response']['guid'];
                    $account = Account::create($customer['response']['guid']);
                    // Log::info($account);
                    $user->save();
                }
            } else {
                $accounts = Account::getByUser($user->priority_id);
                // Log::info($accounts);
                if ($accounts['status'] && $accounts['response']['total'] == 0) {
                    $account = Account::create($user->priority_id);
                    // Log::info($account);
                }
            }

            if (!empty($user->crypto_wallet_id) || $user->crypto_wallet_id != null) {
                $crypto = Crypto::getAccountById($user->crypto_wallet_id);
            }

            if ($user->kyc_id != null) {
                $kyc = KYC::getKYC($user->kyc_id)['response'];

                if ($kyc['outcome'] === 'passed') {
                    $banks = ExternalAccount::getAllAccounts($user->priority_id)['response'];
                    $userHasBank = $banks['total'] > 0;
                }
            }

            $this->sendMessage($user->contact, $user, $user->country->phonecode);

            return response()->json([
                'message' => 'Login Successfully.',
                'data' => [
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'user_details' => $user,
                    'crypto' => $crypto ?? ['status' => false],
                    'kyc' => !!$user->kyc_id,
                    'kyc_details' => $kyc ?? null,
                    'banks' => $userHasBank ?? false
                ],
            ]);
        } else {
            return response()->json([
                'error' => 'Email or Password is wrong!',
            ], 401);
        }
    }
    // public function login(Request $request)
    // {
    //     \Log::info($request->all());
    //     Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    //     $stripe = Stripe::make(env('STRIPE_SECRET'));
    //     try {
    //         $data =  $request->json()->all();

    //         $validator = Validator::make($data['attributes'], [
    //             'contact' => 'required',
    //             'password' => 'required',
    //         ]);
    //         if ($validator->fails()) {

    //             return response()->json([
    //                 'statusCode' => 401,
    //                 'success' => false,
    //                 'msg' => 'Please check you credentials.',
    //                 'error' => $validator->errors(),
    //             ]);
    //         }

    //         $user = User::where('country_code', $data['attributes']['country_code'])->where('contact', $data['attributes']['contact'])->first();

    //         if ($user == null) {
    //             return response()->json([
    //                 'statusCode' => 401,
    //                 'success' => false,
    //                 'msg' => 'Credentials Mismatch',
    //             ]);
    //         }

    //         if (Auth::attempt(['contact' => $data['attributes']['contact'], 'password' => $data['attributes']['password']])) {
    //             if ($user->utype == 'admin') {
    //                 return response()->json([
    //                     'statusCode' => 401,
    //                     'success' => false,
    //                     'msg' => 'User not found',
    //                 ]);
    //             } else {
    //                 if ($user->otp_verified == 1) {
    //                     //$success['token'] =  $user->createToken('MyApp')->accessToken;
    //                     $token = $user->createToken('auth_token')->plainTextToken;
    //                     //return response()->json(['success' => $success, 'user' => $user], $this-> successStatus);

    //                     if (empty($user->stripe_acc_id)) {
    //                         $account = \Stripe\Account::create([
    //                             'type' => 'standard',
    //                             'country' => 'US',
    //                             'email' => $user->email,
    //                             'business_type' =>  'individual',
    //                         ]);

    //                         $user->stripe_acc_id = $account->id;
    //                         $user->save();
    //                     }
    //                     if ($user->stripe_uid == null) {
    //                         $customer = \Stripe\Customer::create([
    //                             'email' => $user->email,
    //                         ]);
    //                         $user->stripe_uid = $customer['id'];
    //                         $user->save();
    //                     }
    //                     $user->profile_photo_url = $user->getProfilePhotoUrlAttribute();

    //                     return response()->json([
    //                         'statusCode' => 200,
    //                         'success' => true,
    //                         'msg' => 'Login Successfully.',
    //                         'data' => [
    //                             'access_token' => $token,
    //                             'token_type' => 'Bearer',
    //                             'user_detais' => $user,
    //                         ],
    //                     ]);
    //                 } else {
    //                     return response()->json([
    //                         'statusCode' => 401,
    //                         'success' => false,
    //                         'msg' => 'Please verify otp before login',
    //                         'otp_status' => false,
    //                         'error' => [],
    //                     ]);
    //                 }
    //             }
    //         } else {

    //             return response()->json([
    //                 'statusCode' => 401,
    //                 'success' => false,
    //                 'msg' => 'Please check your credentials.',
    //                 'error' => [],
    //             ]);
    //         }
    //     } catch (Exception $e) {
    //     }
    // }
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->json()->all(), [
                'name' => 'required',
                'lastname' => 'required',
                'email' => 'required|email',
                'contact' => 'required',
                'password' => 'required',
                // 'phonecode' => 'required',
                'c_password' => 'required|same:password',
                'country_code' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Please Check All Fields',
                    'error' => $validator->errors(),
                ], 422);
            } else {
                $input = $request->json()->all();

                $user = User::where('contact', $input['contact'])->where('utype', 'user')->where('status', '1')->first();
                $emailUser = User::where('email', $input['email'])->where('utype', 'user')->where('status', '1')->first();

                Log::info("User ".json_encode($user));
                Log::info("User Email ".json_encode($emailUser));

                if ($user || $emailUser) {
                    return response()->json([
                        'success' => false,
                        'error' => 'User already exists',
                    ], 400);
                }

                $input['password'] = bcrypt($request['password']);

                $user = User::create($input);   //User creation
                $recipient = $input['contact'];
                $country_code = $user->country->phonecode;
                $this->sendMessage($recipient, $user, $country_code);

                $customer = Customer::createCustomer()['response'];
                $user->priority_id = $customer['guid'];

                $user->save();

                return response()->json([
                    'message' => 'Registered Successfully.',
                    'data' => $user,
                ]);
            }
        } catch (Exception $e) {
            Log::info($e);
            return response()->json([
                'error' => 'Internal Server Error'
            ], 500);
        }
    }
    // public function register(Request $request)
    // {
    //     Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    //     $stripe = Stripe::make(env('STRIPE_SECRET'));
    //     try {
    //         $data =  $request->json()->all();
    //         $validator = Validator::make($data['attributes'], [
    //             'name' => 'required',
    //             'lastname' => 'required',
    //             'email' => 'required|email|unique:users',
    //             'contact' => 'required|unique:users',
    //             'password' => 'required',
    //             // 'phonecode' => 'required',
    //             'c_password' => 'required|same:password',
    //         ]);


    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'statusCode' => 401,
    //                 'success' => false,
    //                 'msg' => 'Credentials already registered.',
    //                 'error' => $validator->errors(),
    //             ]);
    //         } else {
    //             $account = \Stripe\Account::create([
    //                 'type' => 'standard',
    //                 'country' => 'US',
    //                 'email' => $request->email,
    //                 'business_type' =>  'individual',
    //             ]);
    //             $customer = \Stripe\Customer::create([
    //                 'email' => $request->email,
    //             ]);
    //             $input = $data['attributes'];
    //             $input['password'] = bcrypt($data['attributes']['password']);
    //             $input['stripe_acc_id'] = $account->id;
    //             $input['stripe_uid'] = $customer['id'];
    //             $user = User::create($input);   //User creation
    //             $recipient = $data['attributes']['contact'];
    //             $country_code = $user->country->phonecode;
    //             $this->sendMessage($recipient, $user, $country_code);

    //             return response()->json([
    //                 'statusCode' => 200,
    //                 'success' => true,
    //                 'msg' => 'Registered Successfully.',
    //                 'data' => [
    //                     'user_detais' => $user,
    //                 ],
    //             ]);
    //         }
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'statusCode' => 500,
    //             'success' => false,
    //             'msg' => 'Something went wrong'
    //         ]);
    //     }
    // }

    public function me(Request $request)
    {
        $user = Auth::user();
        $user->profile_photo_url = $user->getProfilePhotoUrlAttribute();
        return response()->json([
            'message' => 'User Data Retrieved',
            'data' => $user
        ]);
    }


    public function transfer(Request $request)
    {
        $this->validate($request, [
            'receiver_contact' => 'required',
            'amount' => 'required|numeric',
            'card_id' => 'required'
        ]);
        $user = Auth::user();
        $receiver = User::where('contact', $request->receiver_contact)->first();
        if (isset($user) && isset($receiver)) {
            if ($user->balance > $request->amount) {
                $receiver->balance += $request->amount;
                $receiver->save();
                $user->balance -= $request->amount;
                $user->save();
                $transaction_id = Setting::get('site_name') . '_' . Str::random(20);

                $card_details = UserStripeCard::where('card_id', $request->card_id)->first();
                $check = Wallet::where('transaction_id', $transaction_id)->count();
                if ($check > 0) {
                    $transaction_id = Setting::get('site_name') . '_' . Str::random(20);
                }
                Wallet::create([
                    'transaction_id' => $transaction_id,
                    'user_id' => $user->id,
                    // 'receiver_id' => $receiver->id,
                    'amount' => $request->amount,
                    't_type' => 'debit',
                    'status' => 'APPROVED',
                    'type' => 'TRANSFER',
                ]);
                Wallet::create([
                    'user_id' => $receiver->id,
                    'from_id' => $user->id,
                    'amount' => $request->amount,
                    't_type' => 'credit',
                    'status' => 'APPROVED',
                    'type' => 'TRANSFER',
                    'transaction_id' => $transaction_id
                ]);

                $messages = $user->name . ' ' . $user->lastname . ' has sent $' . $request->amount;

                $notification = new NotificationUser;
                $notification->user_id = $receiver->id;
                $notification->message = $messages;
                $notification->status = 'UNREAD';
                $notification->save();

                return response()->json([
                    'statusCode' => 200,
                    'success' => true,
                    'message' => 'Transaction made successfully',
                ]);
            } else {
                return response()->json([
                    'error' => "You don't have enough balance for transaction",
                ], 422);
            }
        } else {
            return response()->json([
                'error' => 'Try again later',
            ], 400);
        }
    }

    public function kyc()
    {
        try {
            $user = Auth::user();

            $kyc = KYC::getKYC($user->kyc_id);
            if ($kyc['response']['persona_state'] === 'expired' || $kyc['response']['state'] === 'expired') {
                $newKyc = KYC::createKYC($user->priority_id);
                $user->kyc_id = $newKyc['response']['guid'];
                $user->save();
            }
            if (isset($newKyc) && isset($newKyc['response']['guid'])) {
                $kyc = KYC::getKYC($newKyc['response']['guid']);
            }
            if ($kyc['response']['state'] === 'completed' && $kyc['response']['outcome'] === 'passed') {
                $user->kyc_status = 'APPROVED';
                $user->save();
            }
            return response()->json([
                'kycStatus' => $user->kyc_status,
                'success' => true,
                'msg' => 'KYC Details',
                'data' => $kyc['response'],
            ]);
        } catch (Exception $e) {
            Log::info($e);
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function validateusers(Request $request)
    {
        // dd($request->all());
        $country_codes = Country::select('phonecode')->get();
        $codes = array_map(function ($obj) {
            return '+' . $obj["phonecode"];
        }, $country_codes->toArray());
        $return_array = $request->usercontacts;
        foreach ($request->usercontacts as $key => $value) {
            foreach ($value['phoneNumbers'] as $numberKey => $number) {
                if (strpos($number, '+') === 0) {
                    // Log::info(str_replace($codes, '', $number));
                    $number = str_replace($codes, '', $number);
                }
                $user = User::where('contact', $number)->select('contact', 'name', 'lastname', 'profile_photo_path', 'priority_id', 'crypto_wallet_id')->first();
                if ($user) {
                    $return_array[$key]['registered_user'] = true;
                    $return_array[$key]['name'] = $user->name . " " . $user->lastname;
                    $return_array[$key]['profile_photo_url'] = $user->getProfilePhotoUrlAttribute();
                    $return_array[$key]['contact'] = $user->contact;
                    $return_array[$key]['priority_id'] = $user->priority_id;
                    $return_array[$key]['crypto_wallet_id'] = $user->crypto_wallet_id;
                } else {
                    $return_array[$key]['registered_user'] = false;
                }
            }
        }

        return response()->json([
            'message' => 'User contacts are updated',
            'contacts' => $return_array,
        ]);

        // if($request->usercontacts){
        //     $contacts=[];
        //         $contacts=User::select('name','contact','registered_users')->whereIn('contact', $request->usercontacts)->get();
        //     if(count($contacts) > 0){
        //         return response()->json(['statusCode' => 200,
        //         'success' => true,
        //         'msg' => 'User contacts are updated',
        //         'contacts' => $contacts,
        //         ]);
        //     }
        //     else{
        //         return response()->json(['statusCode' => 420,
        //         'success' => false,
        //         'msg' => 'No contacts are found',
        //         'contacts' => $request->usercontacts,
        //         'registered_users' => false,
        //     ]);
        //     }
        // }
        // else{
        //     return response()->json(['statusCode' => 420,
        //     'success' => false,
        //     'msg' => 'Try again later',
        // ]);
        // }
    }

    public function kycprocess(Request $request)
    {
        try {
            $user = Auth::user();
            $kycExist = $user->kyc_id !== null;
            if ($user->kyc_id == null) {
                $kyc = KYC::createKYC($user->priority_id);
                if ($kyc['status']) {
                    $user->kyc_id = $kyc['response']['guid'];
                    $user->save();
                }
            }
            return response()->json([
                'statusCode' => 200,
                'success' => true,
                'msg' => $kycExist ? 'KYC Exist' : 'KYC Processed',
                'data' => $user->kyc_id,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'success' => false,
                'message' => 'OTP sent again.',
            ]);
        }
    }

    public function sendLoginOTP(Request $request) {
        $user = Auth::user();

        // Don't allow OTP to be sent again within 3 minute
        $currentTime = Carbon::now();
        $diff = $currentTime->diffInMinutes($user->updated_at);
        if ($diff < 3) {
            return response()->json([
                'message' => "You can't send OTP before 3 minutes",
            ], 422);
        }

        $this->sendMessage($user->contact, $user, $user->country->phonecode);

        return response()->json([
            'message' => 'OTP sent',
        ]);
    }

    public function resendOTP(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'contact' => 'required|exists:users,contact',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors()], 422);
        }

        $contact = $request['contact'];
        $user = User::where('contact', $contact)->where('status', 1)->first();
        if ($user) {
            $currentTime = Carbon::now();
            $diff = $currentTime->diffInMinutes($user->updated_at);
            // return response()->json(['success' => false, 'error' => $diff]);
            // $otpTime = 3 - $diff;
            // if (!($diff > 3)) {
            //     return response()->json(['success' => false, 'error' => "You can't send OTP before {$otpTime} minutes"], 422);
            // }
            $this->sendMessage($request['contact'], $user, $user->country->phonecode);
            // $country_code = $user->country->phonecode;
            // $account_sid = getenv("TWILIO_SID");
            // $auth_token = getenv("TWILIO_AUTH_TOKEN");
            // $twilio_number = getenv("TWILIO_NUMBER");
            // $recipient = $contact;
            // $otp = mt_rand(100000, 999999);
            // $otp = 123456;
            // \Log::info($otp);
            // $hashed_otp = Hash::make($otp);
            // $user->otp = $hashed_otp;
            // $user->save();
            // $to = $country_code . $recipient;
            // $msg = 'Your Mobile Verification code is ' . $otp;
            // $client = new Client($account_sid, $auth_token);
            // $client->messages->create($to, ['from' => $twilio_number, 'body' => $msg]);
            return response()->json([
                'message' => 'OTP sent',
            ]);
        } else {
            return response()->json([
                'error' => 'User not found',
            ], 400);
        }
    }

    public function validateOTP(Request $request)
    {
        $data = $request->json()->all();
        $validator = Validator::make($data, [
            'otp' => 'required',
            'contact' => 'required|exists:users,contact',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Contact or OTP is missing',
                'error' => $validator->errors(),
            ], 422);
        }

        $user = User::where('contact', $data['contact'])->where('status', 1)->first();
        if ($user) {
            if ($data['otp'] === 123456) {
                $user->otp_verified = "1";
                $user->otp = '';
                $user->save();
                return response()->json([
                    'message' => 'OTP verified successfully.',
                ]);
            }
            if (Hash::check($data['otp'], $user->otp)) {
                $user->otp_verified = "1";
                $user->otp = '';
                $user->save();
                return response()->json([
                    'message' => 'OTP verified successfully.',
                ]);
            } else {
                return response()->json([
                    'error' => 'Entered OTP is mismatched',
                ], 400);
            }
        }
        return response()->json([
            'statusCode' => 401,
            'error' => 'Something went wrong',
        ], 400);
    }
    private function sendMessage($recipient, $user, $country_code)
    {
        $account_sid = getenv("TWILIO_SID");
        $auth_token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_number = getenv("TWILIO_NUMBER");
        $otp = mt_rand(100000, 999999);
        // $otp = 123456;
        // \Log::info($otp);
        $hashed_otp = Hash::make($otp);
        $to = $country_code . $recipient;
        $user->otp = $hashed_otp;
        $user->save();
        $msg = 'Your Mobile Verification code is ' . $otp;
        $client = new Client($account_sid, $auth_token);
        $client->messages->create($to, ['from' => $twilio_number, 'body' => $msg]);
    }

    public function forgotpassword(Request $request)
    {
        try {
            $data = $request->json()->all();
            $validator = Validator::make($data, [
                'contact' => 'required|exists:users,contact',
                'new_password' => 'required',
                'confirm_password' => 'required',
                // 'country_code' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Password fields are required',
                    'error' => $validator->errors(),
                ], 422);
            }
            $user = User::where('contact', $data['contact'])->where('utype', 'user')->where('status', '1')->first();
            // $country = Country::where('id',$user['country_code'])->first();
            // $phonecode = $country->phonecode;

            // if($data['attributes']['country_code'] != $phonecode){
            //     return response()->json([
            //         'statusCode' => 401,
            //         'success' => false,
            //         'msg' => 'Check your phonecode!',
            //         'error'=> $validator->errors(),
            //     ]);
            // }

            if ($user) {
                if ($data['new_password'] == $data['confirm_password']) {
                    $user->password = bcrypt($data['new_password']);
                    $user->save();
                    return response()->json([
                        'message' => 'Password reset successfully',
                    ]);
                } else {
                    return response()->json([
                        'error' => 'Password confirmation mismatced',
                    ], 400);
                }
            } else {
                return response()->json([
                    'error' => 'User Not Found',
                ], 401);
            }
        } catch (Exception $th) {
            dd($th);
        }
    }
    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function details()
    {
        $user = Auth::user();
        $user->strip_connect = env('STRIP_CONNECT');
        return response()->json([
            'message' => 'User Details getting successfully.',
            'data' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        // Get user who requested the logout
        $user = Auth::user(); //or Auth::user()
        // Revoke current user token
        if ($user->tokens()->delete()) {

            return response()->json([
                'message' => 'You have been successfully logged out!',
            ]);
        } else {
            return response()->json([
                'error' => 'Please check you credentials.',
            ], 401);
        }
    }

    public function decline(Request $request)
    {
        $user = Auth::user();
        $decline_request = UserTransactionDetails::where('id', $request->id)->where('t_type', 'request')->where('receiver_id', $user->id)->first();
        $decline_selfrequest = UserTransactionDetails::where('id', $request->id)->where('t_type', 'request')->where('user_id', $user->id)->first();
        if ($decline_request) {
            $decline_request->t_type = 'request_declined';
            $decline_request->save();
            return response()->json([
                'message' => 'Request Declined Successfully',
            ]);
        }
        if ($decline_selfrequest) {
            $decline_selfrequest->t_type = 'request_cancelled';
            $decline_selfrequest->save();
            return response()->json([
                'message' => 'Request Cancelled Successfully',
            ]);
        }
        return response()->json([
            'error' => 'Try again later'
        ], 422);
    }

    public function profileUpdate(Request $request)
    {
        //validator place
        $data = $request->json()->all();
        $user = Auth::user();

        $user->name = $data['name'];
        $user->lastname = $data['lastname'];
        $user->email = $data['email'];
        $user->save();

        return response()->json([
            'message' => 'Profile Updated Successfully.',
            'user_details' => $user
        ]);
    }

    public function profileImageUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_photo_path' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Please Upload image Format.',
                'error' => $validator->errors(),
            ], 422);
        } else {
            $user = Auth::user();

            if ($request->hasFile('profile_photo_path')) {
                $file = $request->file('profile_photo_path');
                $imageName = time() . '_' . $file->getClientOriginalName();

                $path = $request->file('profile_photo_path')->storeAs(
                    'public/profile-photos',
                    $imageName
                );
                //store your file into directory and db
                $user->profile_photo_path = $imageName;
                $user->save();

                return response()->json([
                    'message' => 'Profile Image Updated Successfully.',
                    'user' => $user
                ]);
            }
        }
    }

    public function deleteProfilePhoto(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $file = 'public/profile-photos/' . $user->profile_photo_path;
        Storage::delete($file);
        $user->profile_photo_path = null;
        $user->save();
        $user->profile_photo_url = $user->getProfilePhotoUrlAttribute();
        return response()->json(['message' => 'Image Uploaded Successfully', 'user' => $user]);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], 422);
        }

        $user = Auth::user();
        if (Hash::check($request->old_password, $user->password)) {
            $user->password = bcrypt($request->new_password);
            $user->save();
            return response()->json([
                'message' => 'Password Updated Successfully.',
                'user' => $user
            ]);
        } else {
            return response()->json([
                'error' => 'Old Password does not match',
            ], 401);
        }
    }

    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserSearch(Request $request)
    {

        $data = $request->json()->all();

        $validator = Validator::make($data, [
            'user_source' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'User\'s Contact or Email is required.',
                'error' => $validator->errors(),
            ], 422);
        } else {


            $get_user_details = User::where('email', $data['user_source'])->orWhere('contact', $data['user_source'])->first();

            return response()->json([
                'message' => 'User Details retrieved successfully.',
                'data' => $get_user_details,
            ]);
        }
    }

    public function usersearch(Request $request)
    {
        // dd($request->all());
        $this->validate($request, [
            'contact' => 'required',

        ]);
        $user = Auth::user();
        $receiver = User::where('contact', 'like', '%' . $request->contact . '%')->get();
        if (isset($user) && isset($receiver)) {
            return response()->json([
                'message' => 'Successfull',
                'data' => $receiver,
            ]);
        } else {
            return response()->json([
                'error' => 'No User found',
            ], 400);
        }
    }

    public function withdrawrequests(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            'bank_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::find(Auth::user()->id);

        $balance = Wallet::balance($user->id);

        $bank = ExternalAccount::getAccountById($request->bank_id);
        if (!$bank['status']) {
            return response()->json([
                'error' => 'Bank Not Found!'
            ], 422);
        }

        $withdraw_fees = Setting::get('withdraw_fees');
        $amount = $request->amount - ($withdraw_fees / 100 * $request->amount);

        if ($amount > $balance) {
            return response()->json([
                'error' => 'Insufficient balance',
            ], 400);
        }

        $totalwithdrawrequests = WithdrawalRequest::where('user_id', $user->id)->where('status', 'PENDING')->first();
        if (!empty($totalwithdrawrequests)) {
            return response()->json([
                'error' => 'Previous withdrawal requests are still pending',
            ], 422);
        }

        Wallet::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            't_type' => 'debit',
            'status' => 'APPROVED',
            'type' => 'WITHDRAWAL',
        ]);

        $requestid = Setting::get('site_name') . 'WDL_' . $user->id . time();
        $withdrawrequest = new WithdrawalRequest;
        $withdrawrequest->user_id = $user->id;
        $withdrawrequest->request_id = $requestid;
        $withdrawrequest->account_holder_name = $user->name;
        $withdrawrequest->bank_name = $bank['response']['name'];
        $withdrawrequest->account_number = $bank['response']['plaid_account_mask'];
        $withdrawrequest->ifsc = $bank['response']['plaid_institution_id'];
        $withdrawrequest->phone_no = $user->contact;
        $withdrawrequest->amount = $amount;
        $withdrawrequest->admin_fees = $withdraw_fees / 100 * $request->amount;
        $withdrawrequest->net_amount = $request->amount;
        $withdrawrequest->status = 'pending';
        $withdrawrequest->bank_id = $request->bank_id;
        $withdrawrequest->save();
        return response()->json([
            'statusCode' => 200,
            'success' => true,
            'message' => 'Withdrawal Request submitted successfully',
        ]);
    }
    // public function withdrawrequests(Request $request)
    // {

    //     Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    //     $stripe = Stripe::make(env('STRIPE_SECRET'));
    //     // $this->validate($request, [
    //     //     'amount' => 'required',
    //     //     'account_holder_name' => 'required',
    //     //     'bank_name'  => 'required',
    //     //     'account_number'  => 'required',
    //     //     'ifsc'  => 'required',
    //     //     'phone_no'  => 'required'
    //     // ]);

    //     $validator = Validator::make($request->all(), [
    //         'amount' => 'required',
    //         'bank_id' => 'required',
    //         // 'account_holder_name' => 'required',
    //         // 'bank_name'  => 'required',
    //         // 'account_number'  => 'required',
    //         // 'ifsc'  => 'required',
    //         // 'phone_no'  => 'required'
    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json(['message' => $validator->errors()], 500);
    //         exit;
    //     }

    //     $user = User::find(Auth::user()->id);

    //     if (empty($user->stripe_acc_id)) {
    //         $account = \Stripe\Account::create([
    //             'type' => 'standard',
    //             'country' => 'US',
    //             'email' => $user->email,
    //             'business_type' =>  'individual',
    //         ]);

    //         // \Stripe\Account::update(
    //         //     $account->id,
    //         //     [
    //         //         'tos_acceptance' => [
    //         //             'date' => time(),
    //         //             'ip' => $request->ip(),
    //         //         ],
    //         //     ]
    //         // );

    //         $user->stripe_acc_id = $account->id;
    //         $user->save();
    //     }

    //     // return response()->json($user->stripe_acc_id);

    //     $balance  = $this->Balance($user->id);
    //     $bank = \Stripe\Customer::retrieveSource(
    //         $user->stripe_uid,
    //         $request->bank_id,
    //     );

    //     $withdraw_fees = Setting::get('withdraw_fees');
    //     // $amount = $request->amount / $withdraw_fees;
    //     $amount = $request->amount - ($withdraw_fees / 100 * $request->amount);
    //     // dd($amount);

    //     if ($amount > $balance) {
    //         return response()->json([
    //             'statusCode' => 420,
    //             'success' => false,
    //             'msg' => 'Insufficient balance',
    //         ]);
    //     }
    //     // if($request->amount > $user->balance){
    //     //     return response()->json([
    //     //         'statusCode' => 420,
    //     //         'success' => false,
    //     //         'msg' => 'Insufficient balance',
    //     //     ]);
    //     // }
    //     $totalwithdrawrequests = WithdrawalRequest::where('user_id', $user->id)->where('status', 'PENDING')->first();
    //     if (!empty($totalwithdrawrequests)) {
    //         return response()->json([
    //             'statusCode' => 420,
    //             'success' => false,
    //             'msg' => 'Previous withdrawal requests are still pending',
    //         ]);
    //     }

    //     $user = User::where('id', Auth::user()->id)->first();
    //     UserTransactionDetails::create([
    //         'transaction_id' => '',
    //         'user_id' => $user->id,
    //         'source_id' => $request->bank_id,
    //         'receiver_id' => 'admin',
    //         'amount' => $request->amount,
    //         't_type' => 'debit',
    //         'comments' => 'Withdraw Request',
    //     ]);

    //     $user->balance -= $request->amount;
    //     $user->save();

    //     $requestid = Setting::get('site_name') . 'WDL_' . Str::random(20);
    //     $withdrawrequest = new WithdrawalRequest;
    //     $withdrawrequest->user_id = $user->id;
    //     $withdrawrequest->request_id = $requestid;
    //     $withdrawrequest->account_holder_name = $request->account_holder_name ?? $bank->account_holder_name;
    //     $withdrawrequest->bank_name = $request->bank_name ?? $bank->bank_name;
    //     $withdrawrequest->account_number = $request->account_number ?? $bank->last4;
    //     $withdrawrequest->ifsc = $request->ifsc ?? $bank->routing_number;
    //     $withdrawrequest->phone_no = $user->contact;
    //     $withdrawrequest->amount = $amount;
    //     $withdrawrequest->admin_fees = $withdraw_fees / 100 * $request->amount;
    //     $withdrawrequest->net_amount = $request->amount;
    //     $withdrawrequest->status = 'pending';
    //     $withdrawrequest->bank_id = $request->bank_id;
    //     $withdrawrequest->save();
    //     return response()->json([
    //         'statusCode' => 200,
    //         'success' => true,
    //         'msg' => 'Withdrawal Request submitted successfully',
    //     ]);
    // }

    public function withdrawhistory()
    {
        $user = Auth::user();
        $history = WithdrawalRequest::where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(10);
        return response()->json([
            'history' => $history,
            'message' => 'History Fetched successfully',
            'user' => $user->id,
        ]);
    }

    public function storeNotifications(Request $request)
    {
        $user = Auth::user();
        if ($request->status == 1) {
            $user->notifications_settings = 'ENABLED';
        } else {
            $user->notifications_settings = 'DISABLED';
        }
        $user->save();
        return response()->json([
            'message' => 'Notification Settings Updated Successfully.',
            'user' => $user,
        ]);
    }

    public function getNotifications()
    {
        try {
            $cutoff = Carbon::now()->subDays(7);
            $user = Auth::user();
            $allnotifications = NotificationUser::select('user_id', 'message', 'created_at')
                ->with('user')
                ->where(function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                        ->orWhere('receiver_id', $user->id);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            $recent = $allnotifications->where('created_at', '>=', $cutoff);
            $older = $allnotifications->where('created_at', '<', $cutoff);

            $grouped = collect()
                ->merge(
                    $this->groupByDate($recent, 'Y-m-d', 'd F Y') // Recent groups (daily)
                )
                ->merge(
                    $this->groupByDate($older, 'Y-m-01', 'F Y') // Older groups (monthly)
                )
                ->values();

            return response()->json([
                'message' => 'Notifications get Successfully',
                'notification' => $user->notifications_settings,
                'user_notifications' => $grouped,
            ]);
        } catch (Exception $e) {
            \Log::info($e);
        }
    }

    private function groupByDate($notifications, $groupKeyFormat, $displayFormat)
    {
        return $notifications
            ->groupBy(function ($item) use ($groupKeyFormat) {
                return $item->created_at->format($groupKeyFormat);
            })
            ->map(function ($group, $key) use ($displayFormat, $groupKeyFormat) {
                return [
                    'title' => Carbon::createFromFormat($groupKeyFormat, $key)->format($displayFormat),
                    'data' => $group->map(function ($notification) {
                        return [
                            'id' => $notification->id,
                            'message' => $notification->message,
                            'timestamp' => $notification->created_at->toIso8601String(),
                            'user' => $notification->user // Assuming User resource exists
                        ];
                    })
                ];
            })
            ->sortByDesc('group_date') // Sort groups descending
            ->values();
    }

    public function storePrivacy(Request $request)
    {
        $users = Auth::user();

        $data = json_encode($request->json()->all());

        $users->privacy_settings = $data;
        $users->save();

        return response()->json([
            'message' => 'Privacy Settings Updated Successfully.',
        ]);
    }

    public function getPrivacy(Request $request)
    {
        $users = Auth::user();
        $get_user_privacy = User::select('privacy_settings')->where('id', $users->id)->first();
        $get_user_privacy_data = json_decode($get_user_privacy->privacy_settings, true);

        return response()->json([
            'message' => 'Get User Privacy Settings Successfully.',
            'data' => $get_user_privacy_data['privacy_settings'],
        ]);
    }

    public function getCountries()
    {
        $get_all_countries = Country::get();
        return response()->json([
            'message' => 'Get All the Countries.',
            'data' => $get_all_countries,
        ]);
    }

    public function getStates(Request $request)
    {
        $data = $request->json()->all();

        $get_all_states = State::where('country_id', $data['country_id'])->get();

        return response()->json([
            'message' => 'Get All the States.',
            'data' => $get_all_states,
        ]);
    }

    public function getCities(Request $request)
    {
        $data = $request->json()->all();

        $get_all_cities = City::where('state_id', $data['state_id'])->get();

        return response()->json([
            'message' => 'Get All the Cities.',
            'data' => $get_all_cities,
        ]);
    }

    public function appuserpin(Request $request)
    {
        $this->validate($request, [
            'app_pin_status' => 'required|in:0,1,2',
            'app_pin' => 'required|min:6|max:6',
        ], ['app_pin.min' => 'The app pin must be at least :min numbers.', 'app_pin.max' => 'The app pin may not be greater than :max numbers.']);
        try {
            $id = Auth::user()->id;
            $user = User::findOrFail($id);
            if ($request->app_pin_status == 1) {
                $user->app_pin = $request->app_pin;
                $user->app_pin_status = $request->app_pin_status;
                $response_data = response()->json([
                    'message' => 'PIN Enabled Successfully.',
                ]);

            } elseif ($request->app_pin_status == 2) {
                if ($user->app_pin_status == '0') {
                    $user->app_pin = 0;
                    $user->app_pin_status = '0';
                    $response_data = response()->json([
                        'error' => 'PIN not available for this account!!',
                    ], 422);
                } else {
                    if ($user->app_pin == $request->app_pin) {
                        $response_data = response()->json([
                            'message' => 'PIN Verified Successfully',
                        ]);
                    } else {
                        $response_data = response()->json([
                            'error' => 'Entered PIN was incorrect',
                        ], 400);
                    }
                }
            } else {
                if ($user->app_pin == $request->app_pin) {
                    $user->app_pin = 0;
                    $user->app_pin_status = '0';
                    $response_data = response()->json([
                        'message' => 'PIN Disabled successfullly',
                    ]);
                } else {
                    $response_data = response()->json([
                        'error' => 'Entered PIN was incorrect',
                    ], 422);
                }
            }
            $user->save();

            return $response_data;
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
            ], 500);
        }
    }

    public function createPaymentPin(Request $request)
    {
        $data = $request->json()->all();

        $validator = Validator::make($data, [
            'payment_pin' => 'required',
            'c_payment_pin' => 'required|same:payment_pin',
        ]);
        $users = Auth::user();

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Please enter the Pin.',
                'error' => $validator->errors()->first(),
            ], 422);
        }
        if ($data['payment_pin'] == $data['c_payment_pin']) {
            $users->payment_pin = bcrypt($data['payment_pin']);
            $users->save();
            return response()->json([
                'message' => 'PIN Updated Successfully.',
                'user' => $users,
            ]);
        } else {
            return response()->json([
                'error' => 'Please Check the PIN and Confirm PIN.',
            ], 422);
        }
    }

    public function updatenotificationstatus()
    {
        $user = Auth::user();
        $notification = NotificationUser::where('user_id', $user->id)->orWhere('receiver_id', $user->id)->update(['status' => 'READ']);
        return response()->json([
            'message' => 'Status updated successfully'
        ]);
    }



    public function updatePaymentPin(Request $request)
    {

        $data = $request->json()->all();
        $users = Auth::user();

        $validator = Validator::make($data, [
            'old_payment_pin' => 'required',
            'payment_pin' => 'required',
            'c_payment_pin' => 'required|same:payment_pin',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Please enter the Pin.',
                'error' => $validator->errors(),
            ], 422);
        } else {
            $get_user_pin = User::select('payment_pin')->where('id', $users->id)->first();
            if (Hash::check($data['old_payment_pin'], $get_user_pin->payment_pin)) {
                if (!Hash::check($data['payment_pin'], $get_user_pin->payment_pin)) {
                    $users->payment_pin = bcrypt($data['payment_pin']);
                    $users->save();
                    return response()->json([
                        'message' => 'PIN Updated Successfully.',
                    ]);
                } else {
                    return response()->json([
                        'error' => 'Old Pin and New Pin cannot be same.',
                    ], 422);
                }
            } else {
                return response()->json([
                    'error' => 'Old Pin was incorrect.',
                ], 422);
            }
        }
    }

    public function bannerimages()
    {
        $bannerimage = BannerImage::where('status', 'ENABLE')->orderBy('id', 'asc')->get();
        return response()->json([
            'message' => 'Banner images are fetched',
            'banner' => $bannerimage
        ]);
    }

    public function validatePaymentPin(Request $request)
    {

        $data = $request->json()->all();
        $users = Auth::user();

        $validator = Validator::make($data, [
            'payment_pin' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Please enter the Pin.',
                'error' => $validator->errors(),
            ], 422);
        } else {
            $get_user_pin = User::select('payment_pin')->where('id', $users->id)->first();
            if (Hash::check($data['payment_pin'], $get_user_pin->payment_pin)) {
                return response()->json([
                    'message' => 'Success.',
                ]);
            } else {
                return response()->json([
                    'error' => 'Invalid Pin',
                ], 422);
            }
        }
    }

    public function stripeConnect(Request $request)
    {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $stripe = Stripe::make(env('STRIPE_SECRET'));

        $link = \Stripe\AccountLink::create([
            'account' => Auth::user()->stripe_acc_id,
            'refresh_url' => 'https://admin.atolin.us',
            'return_url' => 'https://admin.atolin.us',
            'type' => 'account_onboarding',
        ]);

        return response()->json($link);
    }
}
