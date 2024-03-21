<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use DB;
use Str;
use App\Models\NotificationUser;
use Stripe;
use Setting;
use Cartalyst\Stripe\Laravel\Facades\Stripeapi;
use App\Models\Country;
use App\Models\User;
use App\Models\UserTransactionDetails;
use Illuminate\Support\Facades\Auth;
use App\Models\UserRequestMoney;
use Exception;

class PaymentController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //
    }


    public function getCardDetails(Request $request)
    {
        $data =  $request->json()->all();

        $validator = Validator::make($data, [
            'card_no' => 'required',
            'exp_month' => 'required',
            'exp_year' => 'required',
            'cvv' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Please check you Card Details.',
                'error' => $validator->errors(),
            ], 422);
        } else {

            $card_details['card_no'] =  $data['attributes']['card_no'];
            $card_details['exp_month'] =   $data['attributes']['exp_month'];
            $card_details['exp_year'] =   $data['attributes']['exp_year'];
            $card_details['cvv'] =   $data['attributes']['cvv'];

            return response()->json([
                'message' => 'Card Details',
                'data' => $card_details,
            ]);
        }
    }

    public function addAmounttoWallet(Request $request)
    {
        $data =  $request->json()->all();
        $user = Auth::user();

        $validator = Validator::make($data, [
            'source_id' => 'required',
            'amount' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Please check you Card Details.',
                'error' => $validator->errors(),
            ], 422);
        } else {
            //added to database
            $transaction_id = (@$data['transaction_id']) ? $data['transaction_id'] : uniqid();
            $user_id = $user->id;
            $source_id = $data['source_id'];
            $amount = $data['amount'];
            $t_type = "credit";
            $created_at = new \DateTime();
            UserTransactionDetails::create([
                'transaction_id' => $transaction_id,
                'user_id' => $user_id,
                'source_id' => $source_id,
                'amount' => $amount,
                't_type' => $t_type,
                'creater_at' => $created_at
            ]);


            return response()->json([
                'message' => 'Money added successfully in wallet.',
                'data' => [
                    'transaction_id' => $transaction_id,
                    'amount' => $amount,
                ],
            ]);
        }
    }

    public function debitAmountonWallet(Request $request)
    {

        $data =  $request->json()->all();
        $user = Auth::user();

        $validator = Validator::make($data, [
            'amount' => 'required',
            'mobile_no' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Amount or Contact is missing.',
                'error' => $validator->errors(),
            ], 422);
        } else {

            $total_wallet_amount = $this->getWalletAmount($request);
            $total_wallet_amount_value = $total_wallet_amount->getData();

            //print_r($total_wallet_amount_value->data->total_wallet_amount);
            //print_r($data['attributes']['amount']);

            if ($total_wallet_amount_value->data->total_wallet_amount > $data['attributes']['amount']) {

                $transaction_id = uniqid();
                $created_at = new \DateTime();

                //Credit Amount added to database
                $credit_details_user_id = User::where('contact', $data['attributes']['mobile_no'])->first();

                $cuser_id = $credit_details_user_id->id;
                $csource_id = "wallet_" . $user->id . "";
                $camount = $data['attributes']['amount'];
                $ct_type = "credit";
                $ccomments = $data['attributes']['comments'] ?? "No Comment";

                //print_r($credit_details);die;

                UserTransactionDetails::create([
                    'transaction_id' => $transaction_id,
                    'user_id' => $cuser_id,
                    'source_id' => $csource_id,
                    'amount' => $camount,
                    't_type' => $ct_type,
                    'comments' => $ccomments,
                    'created_at' => $created_at
                ]);

                //Debit Amount added to database

                $duser_id = $user->id;
                $dsource_id = "wallet_" . $credit_details_user_id->id . "";
                $damount = $data['attributes']['amount'];
                $dt_type = "debit";
                $dcomments = $data['attributes']['comments'] ?? "No Comments";

                UserTransactionDetails::create([
                    'transaction_id' => $transaction_id,
                    'user_id' => $duser_id,
                    'source_id' => $dsource_id,
                    'amount' => $damount,
                    't_type' => $dt_type,
                    'comments' => $dcomments,
                    'created_at' => $created_at
                ]);
                return response()->json([
                    'message' => 'Money Debit successfully from wallet.',
                    'data' => [
                        'transaction_id' => $transaction_id,
                        'amount' => $damount,
                        'comments' => $dcomments,
                    ],
                ]);
            } else {
                return response()->json([
                    'error' => 'Insufficient amount on your wallet.',
                ], 422);
            }
        }
    }


    public function getWalletAmount()
    {
        try {
            Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $stripe = Stripe::make(env('STRIPE_SECRET'));
            $user = Auth::user();
            $country = Country::where('id', $user->country_code)->pluck('name');
            $notificationuser = NotificationUser::where('user_id', $user->id)->orWhere('receiver_id', $user->id)->get();
            $total_wallet_amount = $user->balance;
            if ($user->stripe_uid == null) {
                $customer = \Stripe\Customer::create(array(
                    'name' => $user->name,
                    'email' => $user->email,
                    "address" => ["city" => $user->city, "country" => $country]
                ));
                $user->stripe_uid = $customer->id;
                $user->save();
                $total_credit_wallet_amount = UserTransactionDetails::where('user_id', $user->id)->where('t_type', 'credit')->sum('amount');
                $total_debit_wallet_amount = UserTransactionDetails::where('user_id', $user->id)->where('t_type', 'debit')->sum('amount');

                return response()->json([
                    'statusCode' => 200,
                    'success' => true,
                    'message' => 'Your Total Wallet Amount',
                    'data' => [
                        'total_wallet_amount' => $total_wallet_amount,
                        'customer_id' => $customer->id,
                        'user_details' => $user,
                        'notificationcount' => $notificationuser->count()
                    ],
                ]);
            } else {
                //Get total credit amount from database
                $total_credit_wallet_amount = UserTransactionDetails::where('user_id', $user->id)->where('t_type', 'credit')->sum('amount');
                $total_debit_wallet_amount = UserTransactionDetails::where('user_id', $user->id)->where('t_type', 'debit')->sum('amount');

                $total_wallet_amount = $user->balance;

                return response()->json([
                    'message' => 'Your Total Wallet Amount',
                    'data' => [
                        'total_wallet_amount' => $total_wallet_amount,
                        'customer_id' => $user->stripe_uid,
                        'user_details' => $user,
                        'notificationcount' => $notificationuser->count()
                    ],
                ]);
            }
        } catch (Exception $th) {
            return response()->json([
                'error' => 'Something went wrong'
            ], 500);
        }
    }


    public function getTransactionDetails()
    {
        try {
            $user = Auth::user();
            // $transaction_details = UserTransactionDetails::with('receiver')->where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(10);
            $transaction_details = UserTransactionDetails::with('receiver')->where('user_id', $user->id)->orWhere('receiver_id', $user->id)->orderBy('created_at', 'desc')->paginate(10);
            //added to database
            // $transaction_details = UserTransactionDetails::with('receiver')->groupBy('source_id')->where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

            return response()->json([
                'message' => 'Your Transaction Details.',
                'transaction_details' => $transaction_details,
            ]);
        } catch (Exception $e) {
            \Log::info($e);
        }
    }


    public function getuniqueTransactionDetails()
    {

        $user = Auth::user();

        // $transaction_details = UserTransactionDetails::with('receiver')->groupBy('receiver_id')->where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $transaction_details = UserTransactionDetails::with('receiver')->groupBy('receiver_id')->where('user_id', $user->id)->orWhere('receiver_id', $user->id)->orderBy('created_at', 'desc')->get();
        return response()->json([
            'statusCode' => 200,
            'success' => true,
            'message' => 'Your Transaction Details.',
            'transaction_details' => $transaction_details,
        ]);
    }

    public function userpaneldetails(Request $request)
    {
        $this->validate($request, [
            'receiver_contact' => 'required',
        ]);
        $userId = Auth::user()->id;
        $receiver = User::where('contact', $request->receiver_contact)->first();
        $receiverId = $receiver->id;
        $transaction_details = UserTransactionDetails::with('receiver')->where(function ($q) use ($userId, $receiverId) {
            $q->where('user_id', $userId)->where('receiver_id', $receiverId)->orWhere('user_id', $receiverId)->orWhere('receiver_id', $userId);
        })->orderBy('created_at', 'asc')->paginate(10);

        return response()->json([
            'statusCode' => 200,
            'success' => true,
            'message' => 'Your Transaction Details.',
            'transaction_details' => $transaction_details,
        ]);
    }

    public function RequestMoney(Request $request)
    {
        $data =  $request->json()->all();
        $user = Auth::user();

        $validator = Validator::make($data, [
            'receiver' => 'required',
            'amount' => 'required',
            'currency' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Check all fields',
                'error' => $validator->errors(),
            ], 422);
        } else {
            $get_receiver_user_id =  User::select('id')->where('contact', $data['attributes']['receiver'])->first();

            //Request Details add to database
            $requester_user_id = $user->id;
            $receiver_user_id = $get_receiver_user_id->id;
            $amount = $data['attributes']['amount'];
            $currency = $data['attributes']['currency'];
            if (isset($data['attributes']['comments'])) {
                $comments = $data['attributes']['comments'];
            } else {
                $comments = null;
            }


            $transaction_id = Setting::get('site_name') . 'REQ_' . Str::random(20);
            $check = UserTransactionDetails::where('transaction_id', $transaction_id)->count();
            if ($check > 0) {
                $transaction_id = Setting::get('site_name') . 'REQ_' . Str::random(20);
            }
            UserTransactionDetails::create([
                'transaction_id' => $transaction_id,
                'user_id' => $user->id,
                'source_id' => $request->card_id,
                'receiver_id' => $get_receiver_user_id->id,
                'amount' => $data['attributes']['amount'],
                't_type' => 'request',
                'comments' => $comments,
            ]);

            $title = $user->name . ' ' . $user->lastname . ' has requested Money from you';
            $messages = 'On Approving the request $ ' . $amount . ' will be debited from your account';
            $notification = new NotificationUser;
            $notification->user_id = $receiver_user_id;
            $notification->message = $title . ' ' . $messages;
            $notification->amount = $amount;
            $notification->status = 'UNREAD';
            $notification->save();

            // UserRequestMoney::create([
            //     'requester_user_id' => $requester_user_id,
            //     'receiver_user_id' => $receiver_user_id,
            //     'amount' => $amount,
            //     'currency' => $currency,
            //     'comments'=> $comments,
            //     'created_at' => $created_at
            // ]);
            return response()->json([
                'message' => 'Money request Sent to User.',
            ]);
        }
    }
}
