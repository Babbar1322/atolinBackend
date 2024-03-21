<?php



namespace App\Http\Controllers;

use App\CURL\Customer;
use App\CURL\Card;
use App\CURL\ExternalAccount;
use App\CURL\Transaction;
use App\Models\Country;
use App\Models\UserTransactionDetails;
use App\Models\UserStripeTransaction;
use App\Models\UserRefund;
use App\Models\NotificationUser;
use App\Models\PaymentLog;
use App\Models\StripeBankDetails;
use Illuminate\Http\Request;
use Session;
use Auth;
use Stripe;
use Validator;
use Cartalyst\Stripe\Laravel\Facades\Stripeapi;
use DB;
use Illuminate\Support\Str;
use Throwable;
use App\Models\UserStripeCard;
use App\Models\User;
use Cartalyst\Stripe\Exception\StripeException;
use Exception;
use Log;
use Setting;

class StripePaymentController extends Controller

{
    public function __construct()
    {
        $this->apiToken = env('PRIORITY_API_KEY');
    }

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripe()
    {
        return view('stripe');
    }

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public static function createStripeUserAccount(Request $request)
    {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $customer = \Stripe\Customer::create(array(
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            "address" => ["city" => $request->get('city'), "country" => $request->get('issuing_country')]
        ));

        return $customer->id;
    }


    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    //    public function addCardtoUserAccount(Request $request)
    //
    //    {
    //        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    //        $stripe = Stripe::make(env('STRIPE_SECRET'));
    //        // dd($stripe);
    //        $validator = Validator::make($request->all(), [
    //            'card_no' => 'required',
    //            'exp_month' => 'required',
    //            'exp_year' => 'required',
    //            'cvv' => 'required',
    //        ]);
    //
    //        if ($validator->fails()) {
    //            return response()->json(['error' => $validator->errors()], 401);
    //        }
    //
    //        try {
    //            // $user = Auth::user();
    //            $user = Auth::user();
    //            if ($user->stripe_uid == null) {
    //
    //                $customer = \Stripe\Customer::create([
    //                    'email' => Auth::user()->email,
    //                ]);
    //                $user->stripe_uid = $customer['id'];
    //                $user->save();
    //                $cust_id = $customer['id'];
    //            } else {
    //
    //                $cust_id = $user->stripe_uid;
    //            }
    //
    //            $token = $stripe->tokens()->create([
    //                'card' => [
    //                    'name' => $request->get('name'),
    //                    'number'    => $request->get('card_no'),
    //                    'exp_month' => $request->get('exp_month'),
    //                    'exp_year'  => $request->get('exp_year'),
    //                    'cvc'       => $request->get('cvv'),
    //                    "address_city" => "Chennai",
    //                    "address_country" => "Inda",
    //                    "address_line1" => '1',
    //                    "address_line2" => '2',
    //                    "address_state" => "Tamil Nadu",
    //                    "address_zip" => "602002"
    //                ],
    //            ]);
    //            if (!isset($token['id'])) {
    //                $response = \Session::put('error', 'The Stripe Token was not generated correctly');
    //                return response()->json(['error' => 'The Stripe Token was not generated correctly'], 401);
    //            }
    //
    //            //print_r($token['card']);die;
    //
    //            //Add card to customer
    //            //   $customer = \Stripe\Customer::createSource(
    //            //     $request->get('cid'),
    //            //     ['source' => $token['id']]
    //            // );
    //
    //            $customer = \Stripe\Customer::retrieve($cust_id);
    //            // $card = $customer->sources->create(["source" => $request->stripe_token]);
    //            $card = $customer->createSource($customer->id, ["source" => $token['id']]);
    //
    //            try {
    //                //Add user user card details to database
    //                $stripe_uid = $request->get('cid');
    //                $card_id = $token['card']['id'];
    //                $fingerprint = $token['card']['fingerprint'];
    //                $last4 = $token['card']['last4'];
    //                $brand = $token['card']['brand'];
    //                $country = $token['card']['country'];
    //                $exp_month = $token['card']['exp_month'];
    //                $exp_year = $token['card']['exp_year'];
    //                $created_at = new \DateTime();
    //
    //                UserStripeCard::create([
    //                    'stripe_uid' => $stripe_uid,
    //                    'card_id' => $card_id,
    //                    'fingerprint' => $fingerprint,
    //                    'last4' => $last4,
    //                    'brand' => $brand,
    //                    'card_type' => $request->card_type,
    //                    'card_holder_name' => $request->card_holder_name,
    //                    'country' => $country,
    //                    'exp_month' => $exp_month,
    //                    'exp_year' => $exp_year,
    //                    'created_at' => $created_at
    //                ]);
    //            } catch (\Illuminate\Database\QueryException $e) {
    //                return response()->json(['error' => ['msg' => 'Card already exists']], 401);
    //            }
    //
    //            return response()->json([
    //                'statusCode' => 200,
    //                'success' => true,
    //                'msg' => 'Card added successfully',
    //            ]);
    //        } catch (\Stripe\Exception\CardException $e) {
    //            $error = $e->getError()->message;
    //            return response()->json(['error' => ['msg' => $error]], 401);
    //        }
    //    }

    public function addCardtoUserAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'card_no' => 'required|min:13|max:17',
            'exp_month' => 'required',
            'exp_year' => 'required|between:4,4',
            'cvv' => 'required',
            'address_line1' => 'required',
            // 'address_line2' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $user = User::where('id', Auth::user()->id)->first();

            $cardCreate = Card::createCard($user, $request);
            if (!$cardCreate['status']) {
                return response()->json([
                    'error' => $cardCreate['response'],
                ], 422);
            }

            $response = Card::getCard($user->id, $request->card_no);
            if (!$response['status']) {
                return response()->json([
                    'error' => 'Card Not Found',
                ], 422);
            }
            $card = $response['response'];

            try {
                $card_id = $card['id'];

                $last4 = $card['cardNumberLast4'];
                $country = $card['billingAddress']['state'];
                $exp_month = $card['expiryMonth'];
                $exp_year = $card['expiryYear'];
                $created_at = new \DateTime();

                UserStripeCard::create([
                    'priority_id' => $user->priority_id,
                    'card_id' => $card_id,
                    'last4' => $last4,
                    'card_type' => $request->card_type,
                    'card_holder_name' => $request->name,
                    'country' => $country,
                    'exp_month' => $exp_month,
                    'exp_year' => $exp_year,
                    'created_at' => $created_at
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['error' => 'Card already exists'], 400);
            }

            return response()->json([
                'statusCode' => 200,
                'success' => true,
                'message' => 'Card added successfully',
            ]);
        } catch (\Stripe\Exception\CardException $e) {
            $error = $e->getError()->message;
            return response()->json(['error' => $error], 400);
        }
    }


    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteCardtoUserAccount(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'card_id'=> 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'error'=> $validator->errors()],422);
            }
            //Delete card to customer
            Card::deleteCard(Auth::user()->id, $request->card_id);

            try {
                UserStripeCard::where('card_id', $request->card_id)->delete();
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['error' => $e->getMessage(), 'message' => 'Card already Deleted.'], 400);
            }

            return response()->json(['success' => 'Card deleted Successfully.'], 200);
        } catch (Stripe\Exception\InvalidRequestException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    // public function deleteCardtoUserAccount(Request $request)
    // {
    //     Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    //     $stripe = Stripe::make(env('STRIPE_SECRET'));
    //     try {

    //         //Delete card to customer
    //         $customer = \Stripe\Customer::deleteSource(
    //             $request->get('cid'),
    //             $request->get('card_id')
    //         );

    //         try {
    //             //Add user user card details to database

    //             UserStripeCard::where('card_id', $request->get('card_id'))->delete();
    //         } catch (\Illuminate\Database\QueryException $e) {

    //             return response()->json(['error' => $e->getMessage(), 'msg' => 'Card already added.'], 401);
    //         }
    //         //print_r($token['card']);die;

    //         return response()->json(['success' => 'Card deleted Successfully.'], 200);
    //     } catch (Stripe\Exception\InvalidRequestException $e) {
    //         $response = \Session::put('error', $e->getMessage());
    //         return response()->json(['error' => $e->getMessage()], 401);
    //     }
    // }


    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function listAllUserCards(Request $request)
    {
        try {
            //Gat all card Details
            $user = User::find(Auth::user()->id);
            $Listallcards = Card::getAllCards($user->priority_id);

            if (!$Listallcards['status']) {
                return response()->json(['error' => $Listallcards['response']], 422);
            }
            return response()->json(['message' => 'Cards Listed Successfully.', 'cards' => $Listallcards['response']], 200);
        } catch (Stripe\Exception\InvalidRequestException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    // public function listAllUserCards(Request $request)
    // {
    //     Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    //     $stripe = Stripe::make(env('STRIPE_SECRET'));
    //     try {
    //         //Gat all card Details
    //         $Listallcards = \Stripe\Customer::allSources(
    //             $request->cid,
    //             ['object' => 'card']
    //         );
    //         //print_r($token['card']);die;

    //         return response()->json(['success' => true, '' => 'All card Listed Successfully.', 'Allcards' => $Listallcards], 200);
    //     } catch (Stripe\Exception\InvalidRequestException $e) {
    //         $response = \Session::put('error', $e->getMessage());
    //         return response()->json(['error' => $e->getMessage()], 401);
    //     }
    // }

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function addBanktoUserAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_type' => 'required|in:SAVINGS,CHECKING',
            'routing_number' => 'required',
            'account_number' => 'required',
            'holder_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $user = Auth::user();
        // \Log::info($user);
        try {
            $res = ExternalAccount::addExternalAccount($user->priority_id, $request);
            if (!$res['status']) {
                return response()->json(['error'=> $res['response']], 422);
            }

            $bankAccount = ExternalAccount::getAccount($user->id, $request->account_number);
            if (!$bankAccount['status']) {
                return response()->json(['error'=> $res['response']], 422);
            }

            return response()->json(['message' => 'Bank added Successfully.'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
    // public function addBanktoUserAccount(Request $request)
    // {
    //     Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    //     $stripe = Stripe::make(env('STRIPE_SECRET'));

    //     $validator = Validator::make($request->all(), [
    //         // 'country' => 'required',
    //         'currency' => 'required',
    //         'account_number' => 'required',
    //         // 'cid' => 'required',
    //         'account_holder_name' => 'required',
    //         // 'account_holder_type' => 'required'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 401);
    //     }
    //     $user = Auth::user();
    //     if ($user->stripe_uid == null) {

    //         $customer = \Stripe\Customer::create([
    //             'email' => Auth::user()->email,
    //         ]);
    //         $user->stripe_uid = $customer['id'];
    //         $user->save();
    //         $cust_id = $customer['id'];
    //     } else {

    //         $cust_id = $user->stripe_uid;
    //     }

    //     if (empty($user->stripe_acc_id)) {
    //         $account = \Stripe\Account::create([
    //             'type' => 'standard',
    //             'country' => 'US',
    //             'email' => $user->email,
    //             'business_type' =>  'individual',
    //         ]);

    //         $user->stripe_acc_id = $account->id;
    //         $user->save();
    //     }

    //     try {
    //         $banktoken = $stripe->tokens()->create([
    //             'bank_account' => [
    //                 'country' => 'US',
    //                 'currency' => $request->get('currency'),
    //                 'account_number'  => $request->get('account_number'),
    //                 'routing_number' => $request->get('routing_number'),
    //                 'account_holder_name' => $request->get('account_holder_name'),
    //                 'account_holder_type' => 'individual',
    //             ],
    //         ]);
    //         //print_r($banktoken);die;

    //         if (!isset($banktoken['id'])) {
    //             $response = \Session::put('error', 'The Stripe Bank Token was not generated correctly');
    //             return response()->json(['error' => 'The Stripe Bank Token was not generated correctly'], 401);
    //         }

    //         // try {
    //         //     //Add user Bank details to database
    //         //     $stripe_uid = $cust_id;
    //         //     $bank_id = $banktoken['bank_account']['id'];
    //         //     $bank_name = $banktoken['bank_account']['bank_name'];
    //         //     $fingerprint = $banktoken['bank_account']['fingerprint'];
    //         //     $last4 = $banktoken['bank_account']['last4'];
    //         //     $routing_number = $banktoken['bank_account']['routing_number'];
    //         //     $country = $banktoken['bank_account']['country'];
    //         //     $currency = $banktoken['bank_account']['currency'];
    //         //     $created_at = new \DateTime();

    //         //     UserStripeTransaction::create([
    //         //         'stripe_uid' => $stripe_uid,
    //         //         'bank_id' => $bank_id,
    //         //         'fingerprint' => $fingerprint,
    //         //         'last4' => $last4,
    //         //         'bank_name' => $bank_name,
    //         //         'country' => $country,
    //         //         'routing_number' => $routing_number,
    //         //         'currency' => $currency,
    //         //         'created_at' => $created_at
    //         //     ]);
    //         // } catch (\Illuminate\Database\QueryException $e) {

    //         //     return response()->json(['error' => $e->getMessage(), 'msg' => 'Bank already added.'], 401);
    //         // }
    //         //print_r($token['card']);die;

    //         //Add Bank to customer
    //         $customer = \Stripe\Customer::createSource(
    //             $cust_id,
    //             ['source' => $banktoken['id']]
    //         );

    //         return response()->json(['success' => 'Bank added Successfully.'], 200);
    //     } catch (Exception $e) {
    //         $response = \Session::put('error', $e->getMessage());
    //         return response()->json(['error' => $e->getMessage()], 401);
    //     }
    // }

    /**
     * Delete Bank Account From User
     * @return \Illuminate\Http\Response
     */
    public function deleteBanktoUserAccount(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'account_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=> $validator->errors()],422);
            }

            $user = Auth::user();
            //Delete bank to customer
            $res = ExternalAccount::deleteAccount($user->priority_id, $request->account_id);
            if (!$res['status']) {
                return response()->json(['error'=> $res['response']], 422);
            }

            return response()->json(['message' => 'Bank deleted Successfully.'], 200);
        } catch (Stripe\Exception\InvalidRequestException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
    // public function deleteBanktoUserAccount(Request $request)
    // {
    //     Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    //     $stripe = Stripe::make(env('STRIPE_SECRET'));

    //     try {
    //         $user = Auth::user();
    //         //Delete bank to customer
    //         $customer = \Stripe\Customer::deleteSource(
    //             $user->stripe_uid,
    //             $request->get('bank_id')
    //         );

    //         // try {
    //         //     //Delete user user card details to database
    //         //     UserStripeTransaction::where('bank_id', $request->get('bank_id'))->delete();
    //         // } catch (\Illuminate\Database\QueryException $e) {

    //         //     return response()->json(['error' => $e->getMessage(), 'msg' => 'Bank already added.'], 401);
    //         // }
    //         //print_r($token['card']);die;

    //         return response()->json(['success' => 'Bank deleted Successfully.'], 200);
    //     } catch (Stripe\Exception\InvalidRequestException $e) {
    //         $response = \Session::put('error', $e->getMessage());
    //         return response()->json(['error' => $e->getMessage()], 401);
    //     }
    // }

    /**
     * Verify Bank Account that linked to Users account.
     * @return \Illuminate\Http\Response
     */
    public function verifyUserBank(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'amount_1'=> 'required|numeric|lt:1|gt:0',
                'amount_2'=> 'required|numeric|lt:1|gt:0',
                'account_id'=> 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=> $validator->errors()],422);
            }

            $user = Auth::user();

            $res = ExternalAccount::verifyAccount($user->priority_id, $request->account_id, $request->amount_1, $request->amount_2);

            if (!$res['status']) {
                return response()->json(['error'=> $res['response']], 400);
            }

            return response()->json(['message' => 'Request Submitted'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error'=> $th], 400);
        }
    }

    /**
     * Get Bank Accounts that linked to Users account.
     * @return \Illuminate\Http\Response
     */

    public function listAllAccountsOfUser(Request $request)
    {
        try {
            $user = User::find(Auth::user()->id);
            $ListallBanks = ExternalAccount::getAllAccounts($user->priority_id);
            if (!$ListallBanks['status']) {
                return response()->json(['error'=> $ListallBanks['response']], 422);
            }

            return response()->json(['message' => 'Accounts Listed Successfully.', 'data' => $ListallBanks['response']], 200);
        } catch (Stripe\Exception\InvalidRequestException $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }
    // public function listAllAccountsOfUser(Request $request)
    // {
    //     Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    //     $stripe = Stripe::make(env('STRIPE_SECRET'));

    //     try {
    //         $user = Auth::user();
    //         $ListallBanks = \Stripe\Customer::allSources(
    //             $user->stripe_uid,
    //             ['object' => 'bank_account']
    //         );
    //         //print_r($token['card']);die;

    //         return response()->json(['success' => true, 'message' => 'All card Listed Successfully.', 'data' => $ListallBanks], 200);
    //     } catch (Stripe\Exception\InvalidRequestException $e) {
    //         $response = \Session::put('error', $e->getMessage());
    //         return response()->json(['error' => $e->getMessage()], 401);
    //     }
    // }

    public function listPaymentMethods(Request $request)
    {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $stripe = Stripe::make(env('STRIPE_SECRET'));

        try {
            $user = Auth::user();
            $ListallBanks = \Stripe\Customer::allSources(
                $user->stripe_uid,
            );
            //print_r($token['card']);die;

            return response()->json(['success' => true, 'message' => 'All card Listed Successfully.', 'data' => $ListallBanks], 200);
        } catch (Stripe\Exception\InvalidRequestException $e) {
            $response = \Session::put('error', $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }


    /**

     * success response method.

     *

     * @return \Illuminate\Http\Response

     */

    public function stripeconnect(Request $request)
    {

        if (isset($request->code)) {

            try {



                $post = [
                    'client_secret' => env('STRIPE_SECRET'),
                    'code' => $request->code,
                    'grant_type' => 'authorization_code'
                ];
                $curl = curl_init("https://connect.stripe.com/oauth/token");
                curl_setopt($curl, CURLOPT_HEADER, 0);
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                $result = curl_exec($curl);
                $curl_error = curl_error($curl);
                curl_close($curl);
                $stripe = json_decode($result);

                if (isset($stripe->stripe_user_id)) {
                    $user = User::where('id', Auth::user()->id)->first();
                    $user->stripe_acc_id = $stripe->stripe_user_id;
                    //   $provider->status = 'onboarding';
                    $user->save();

                    if ($request->ajax()) {
                        return response()->json(['message' => 'Your stripe account connected successfully']);
                    }
                } else {

                    if ($request->ajax()) {
                        return response()->json(['message' => $curl_error]);
                    }
                }
            } catch (Exception $e) {
                // dd($e);
                return response()->json(['error' => $e], 500);
            }
        } else {
            if ($request->ajax()) {

                return response()->json(['message' => $request->error_description]);
            }
        }
    }


    public function transfer(Request $request)
    {
        $this->validate($request, [
            'receiver_contact' => 'required',
            'amount' => 'required|numeric',
            'card_id' => 'required'
        ]);
        $user = Auth::user();
        $receiver = User::where('contact', $request->receiver_contact)->whereNotNull('stripe_acc_id')->first();
        if (isset($user)) {
            if (isset($receiver)) {
                if ($user->balance > $request->amount) {
                    $card_details = UserStripeCard::where('card_id', $request->card_id)->first();

                    $StripeWalletCharge = $request->amount * 100;
                    try {

                        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

                        $Charge = \Stripe\Charge::create(array(
                            "amount" => $StripeWalletCharge,
                            "currency" => "usd",
                            "customer" => Auth::user()->stripe_uid,
                            "card" => $request->card_id,
                            "description" => "Adding Money for " . Auth::user()->email,
                            "receipt_email" => Auth::user()->email
                        ));


                        $transfer = \Stripe\Transfer::create(array(
                            "amount" => $StripeWalletCharge,
                            "currency" => "usd",
                            "destination" => $receiver->stripe_acc_id,
                            "transfer_group" => Auth::user()->id . "Transfer" . $receiver->id,
                        ));
                        // \Log::info('$transfer');

                        // \Log::info($transfer);
                    } catch (\Stripe\Exception\InvalidRequestException $e) {
                        \Log::info($e);
                        if ($request->ajax()) {
                            return response()->json(['error' => $e->getMessage()], 422);
                        } else {
                            return back()->with('flash_error', $e->getMessage());
                        }
                    }




                    $receiver->balance += $request->amount;
                    $receiver->save();
                    $user->balance -= $request->amount;
                    $user->save();
                    $transaction_id = 'TX_' . Str::random(20);

                    $check = UserTransactionDetails::where('transaction_id', $transaction_id)->count();
                    if ($check > 0) {
                        $transaction_id = 'TX_' . Str::random(20);
                    }
                    UserTransactionDetails::create([
                        'transaction_id' => $transaction_id,
                        'user_id' => $user->id,
                        'source_id' => $request->card_id,
                        'receiver_id' => $receiver->id,
                        'amount' => $request->amount,
                        't_type' => 'debit',
                        'last4' => 4242,
                        'comments' => $request->comments,
                    ]);

                    $messages = $user->name . ' ' . $user->lastname . ' has sent $' . $request->amount;

                    $notification = new NotificationUser;
                    $notification->user_id = $receiver->id;
                    $notification->message = $messages;
                    $notification->status = 'UNREAD';
                    $notification->save();
                    return response()->json([
                        'message' => 'Transaction made successfully',
                    ]);
                } else {
                    return response()->json([
                        'error' => "You don't have enough balance for transaction",
                    ], 422);
                }
            } else {
                return response()->json([
                    'error' => 'Receiver Strip not Connected',
                ], 422);
            }
        } else {
            return response()->json([
                'error' => 'Try again later',
            ], 500);
        }
    }



    // public function verifyBankAccount(Request $request)
    // {

    //     try {

    //         Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    //         $stripe = Stripe::make(env('STRIPE_SECRET'));

    //         //Verify Bank to customer
    //         $customer = \Stripe\Customer::verifySource(
    //             $request->get('cid'),
    //             $request->get('bank_id'),

    //         );
    //         /*
    //         $customer = $stripe->customers()->verifySource(
    //             $request->get('cid'),
    //             $request->get('bank_id'),

    //           );*/

    //         return response()->json(['success' => 'Bank Verified Successfully.'], 200);
    //     } catch (\Cartalyst\Stripe\Exception\MissingParameterException $e) {
    //         $response =  \Session::put('error', $e->getMessage());
    //         return response()->json(['error' => $e->getMessage()], 401);
    //     }
    // }

    public function withdraw(Request $request)
    {
        try {
            $stripe = new \Stripe\StripeClient(
                'sk_test_51JNG2iSJQbATJIIr7RyukJwRRdes2uoHB610B9zYZIAjiE2ILHyRRP6PSnaEnVh6If3J873BsnLY4k8YMBg7fkJP004zINHdm2'
            );
            $user = Auth::user();
            if ($user->balance > $request->amount) {
                $payouts = $stripe->payouts->create([
                    'amount' => $request->amount,
                    'currency' => $request->currency,
                    'destination' => $request->card_id,
                ]);
                UserRefund::create([
                    'transaction_id' => $payouts['id'],
                    'amount' => $payouts['amount'],
                    'user_id' => $user->id,
                    'destination' => $payouts['destination'],
                    'status' => $payouts['status'],
                ]);
            } else {
                return response()->json([
                    'error' => "You don't have enough balance for withdrawal",
                ], 400);
            }
        } catch (Throwable $e) {
            dd($e);
        }
    }

    /**

     * success response method.

     *

     * @return \Illuminate\Http\Response

     */
    public function getWalletAmount(Request $request)
    {
        try {

            Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            $walletAmount =   Stripe\Balance::retrieve();

            return response()->json(['total_amount' => $walletAmount], 200);
        } catch (\Cartalyst\Stripe\Exception\MissingParameterException $e) {
            $response =  \Session::put('error', $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function addMoneytoWallet(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'amount'=> 'required',
                'source'=> 'required',
                'currency'=> 'required',
                'type' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()],422);
            }
            $user = Auth::user();

            $amount = $request->amount;
            $deposit_fee = Setting::get($request->type === 'ACH' ? 'bank_fee' : 'card_fee');
            if (!empty($deposit_fee)) {
                $amount = $request->amount + ($deposit_fee / 100 * $request->amount);
            }

            $transactionId = time() . $user->id;

            $transaction = Transaction::collectToMainAccount($amount, $request->source, $transactionId, $request->type);
            if (!$transaction['status']) {
                return response()->json(['error'=> $transaction['response']],422);
            }

            $userTransaction = Transaction::getTransaction($transactionId);
            if (!$userTransaction['status']) {
                return response()->json(['error'=> $userTransaction['response']],422);
            }

            PaymentLog::create([
                'user_id' => $user->id,
                'response' => 'pending'
            ]);

            if ($transaction['status']) {
                PaymentLog::create([
                    'user_id' => $user->id,
                    'response' => json_encode($userTransaction['response']),
                    'payment_id' => $userTransaction['response']['id']
                ]);

                UserStripeTransaction::create([
                    'stripe_tid' => $userTransaction['response']['id'],
                    'stripe_uid' => $user->priority_id,
                    'card_id' => $request->source,
                    'amount' => $userTransaction['response']['amount'],
                    'currency' => $request->currency,
                    't_type' => $userTransaction['response']['method'],
                    'request_at' => new \DateTime(),
                    'created_at' => new \DateTime()
                ]);

                UserTransactionDetails::create([
                    'transaction_id' => $userTransaction['response']['id'],
                    'user_id' => $user->id,
                    'source_id' => $request->source,
                    'receiver_id' => $user->id,
                    'amount' => $amount,
                    't_type' => 'credit',
                    'comments' => 'Added From Prioriy',
                    'status' => 'Pending'
                ]);

                return response()->json([
                    'message' => "Request has been submitted successfully",
                ]);
            } else {
                return response()->json([
                    'message' => "Money not added in wallet!",
                ]);
            }
        } catch (\Cartalyst\Stripe\Exception\MissingParameterException $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        } catch (StripeException $e) {
            return response()->json(['message' => $e], 400);
        }
    }
    // public function addMoneytoWallet(Request $request)
    // {
    //     try {
    //         $user = Auth::user();
    //         Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

    //         $amount = $request->amount;
    //         $deposit_fee = Setting::get('deposit_fees');
    //         if (!empty($deposit_fee)) {
    //             $amount = $request->amount + ($deposit_fee / 100 * $request->amount);
    //         }
    //         // return response()->json(['msg' => $amount]);

    //         $charge = Stripe\Charge::create([

    //             "amount" => ceil($amount * 100),

    //             "currency" => $request->currency,

    //             "source" => $request->source,

    //             'customer' => $request->cid,

    //             'description' => 'This transaction is for testing purpose'
    //         ]);


    //         //print_r($charge);die;

    //         PaymentLog::create([
    //             'user_id' => $user->id,
    //             'response' => 'pending'
    //         ]);


    //         if ($charge['status'] == 'succeeded') {

    //             PaymentLog::create([
    //                 'user_id' => $user->id,
    //                 'response' => json_encode($charge),
    //                 'payment_id' => $charge['id']
    //             ]);

    //             //added to database
    //             $stripe_tid = $charge['id'];
    //             $stripe_uid = $charge['customer'];
    //             $card_id = $charge['source']['id'];
    //             $amount = $charge['amount'] / 100;
    //             $currency = $charge['currency'];
    //             $t_type = $charge['payment_method_details']['card']['funding'];
    //             $request_at = new \DateTime();
    //             $created_at = new \DateTime();

    //             // DB::insert('insert into user_stripe_transactions_details ( stripe_tid,stripe_uid,card_id,amount,currency,t_type,request_at,created_at) values (?, ?,?,?,?,?,?,?)', [$stripe_tid,$stripe_uid,$card_id,$amount,$currency,$t_type,$request_at,$created_at]);

    //             UserStripeTransaction::create([
    //                 'stripe_tid' => $stripe_tid,
    //                 'stripe_uid' => $stripe_uid,
    //                 'card_id' => $card_id,
    //                 'amount' => $amount,
    //                 'currency' => $currency,
    //                 't_type' => $t_type,
    //                 'request_at' => $request_at,
    //                 'created_at' => $created_at
    //             ]);

    //             UserTransactionDetails::create([
    //                 'transaction_id' => $charge['id'],
    //                 'user_id' => $user->id,
    //                 'source_id' => $charge['source']['id'],
    //                 'receiver_id' => $user->id,
    //                 'amount' => $amount,
    //                 't_type' => 'credit',
    //                 'comments' => 'Added From Stripe',
    //             ]);

    //             $user->balance += $amount;
    //             $user->save();

    //             $response = \Session::put('success', 'Money add successfully in wallet');
    //             // return response()->json(['success'=>'Money add successfully in wallet'], 200);
    //             return response()->json([
    //                 'statusCode' => 200,
    //                 'success' => true,
    //                 'msg' => "Money add successfully in wallet",
    //             ]);
    //         } else {
    //             $response = \Session::put('error', 'Money not add in wallet!!');
    //             // return response()->json(['error'=>'Money not add in wallet!!'], 401);
    //             return response()->json([
    //                 'statusCode' => 420,
    //                 'success' => false,
    //                 'msg' => "Money not add in wallet!!",
    //             ]);
    //         }
    //     } catch (\Cartalyst\Stripe\Exception\MissingParameterException $e) {
    //         $response =  \Session::put('error', $e->getMessage());
    //         return response()->json(['error' => $e->getMessage()], 401);
    //     } catch (StripeException $e) {
    //         return response()->json(['success' => false, 'message' => $e], 400);
    //     }
    // }
    public function transactionUpdate(Request $request){
        Log::info('webhook log start');
        Log::info($request);
        Log::info('webhook log end');
        return response()->json(true);
    }
}
