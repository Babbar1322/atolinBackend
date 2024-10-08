<?php

namespace App\Http\Controllers;

use App\CURL\Account;
use App\CURL\Customer;
use App\CURL\KYC;
use App\CURL\Transaction;
use App\Encryption\Encryption;
use App\Models\CryptoTransaction;
use App\Models\CryptoWallet;
use Illuminate\Http\Request;
use App\Models\UserTransactionDetails;
use App\Models\User;
use App\Models\UserDocument;
use Setting;

use Validator;

use Auth;
use Http;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Log;

class ApiController extends Controller
{
    public function getBalance(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(["error" => 'Invalid User'], 422);
        }
        $balance = $this->Balance($user->id);
        return response()->json(compact('balance'));
    }

    public function Balance(int $user_id)
    {
        $credit = UserTransactionDetails::where('user_id', $user_id)->where('t_type', 'credit')->where(function ($q) {
            $q->where('status', 'Success')->orWhere('status', 'APPROVED')->orWhere('status', 'COMPLETED');
        })->sum('amount');
        $debit = UserTransactionDetails::where('user_id', $user_id)->where('t_type', 'debit')->where(function ($q) {
            $q->where('status', 'Success')->orWhere('status', 'APPROVED')->orWhere('status', 'COMPLETED');
        })->sum('amount');
        $balance = $credit - $debit;
        // Log::info($balance);
        return $balance;
    }

    public function transfer(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'contact' => 'required|exists:users,contact',
            'amount' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $balance = $this->Balance($user->id);
        if ($request->amount == 0 || $request->amount > $balance) {
            return response()->json(["error" => 'Not enough Balance'], 422);
        }

        $reciever = User::where("contact", $request->contact)->first();

        if ($reciever == null || $user->id == $reciever->id) {
            return response()->json(["error" => 'Invalid contact number'], 422);
        }

        if (!$reciever->priority_id) {
            return response()->json(['error'=> 'User not registered on Platform'], 422);
        }
        $reciever_kyc = KYC::getVerifiedByUser($reciever->priority_id);

        if ($reciever_kyc['status'] && $reciever_kyc['response']['total'] === 0) {
            return response()->json(['error' => 'User KYC not verified.'], 400);
        }

        // $reciever_account =
        // $senderAccount = Account::getByUser($user->priority_id);
        // if (!$senderAccount['status']) {
        //     return response()->json(['error'=> 'You don\'t have Account on Platform'], 400);
        // }
        // $recieverAccount = Account::getByUser($reciever->priority_id);
        // if (! $recieverAccount['status']) {
        //     return response()->json(['error'=> 'Reciever do\'t have Account on Platform'], 400);
        // }
        // $sendFrom = $senderAccount['response']['objects'][0]['guid'];
        // $recieveTo = $recieverAccount['response']['objects'][0]['guid'];
        // $transaction = Transaction::sendUserToUser($request->amount * 100, $sendFrom, $recieveTo);

        // if (!$transaction['status']) {
        //     return response()->json(['error'=> 'Transaction Failed'], 400);
        // }

        $send = new UserTransactionDetails();
        // $send->transaction_id = $transaction['response']['guid'];
        $send->t_type = "debit";
        $send->user_id = $user->id;
        $send->receiver_id = $reciever->id;
        $send->source_id = "transfer";
        $send->amount = $request->amount;
        $send->save();

        $recieve = new UserTransactionDetails();
        // $recieve->transaction_id = $transaction['response']['guid'];
        $recieve->t_type = "credit";
        $recieve->user_id = $reciever->id;
        $recieve->receiver_id = $reciever->id;
        $recieve->source_id = "transfer";
        $recieve->amount = $request->amount;
        $recieve->save();

        return response()->json(["message" => "Amount transfer successfully!"], 200);
    }

    public function credit_histroy(Request $request): JsonResponse
    {
        // $validator = Validator::make($request->all(),[
        //     'token' => 'required',
        // ]);
        // if ($validator->fails()) {
        //     return response()->json(['message'=>$validator->errors()],500);
        // }

        // $user = PersonalAccessToken::findToken($request->token);

        $user = Auth::user();
        if ($user == null) {
            return response()->json(["error" => 'Unauthorised'], 401);
        }
        $history = UserTransactionDetails::where('user_id', $user->id)->where('t_type', 'credit')->orderBy("id", "desc")->paginate(50);
        return response()->json(compact('history'));
    }
    public function debit_histroy(Request $request): JsonResponse
    {
        // $validator = Validator::make($request->all(),[
        //     'token' => 'required',
        // ]);
        // if ($validator->fails()) {
        //     return response()->json(['message'=>$validator->errors()],500);
        // }

        // $user = PersonalAccessToken::findToken($request->token);
        $user = Auth::user();
        if ($user == null) {
            return response()->json(["error" => 'Unauthorised'], 401);
        }
        $history = UserTransactionDetails::where('user_id', $user->id)->where('t_type', 'debit')->orderBy("id", "desc")->paginate(50);
        return response()->json(compact('history'));
    }

    public function transactions(Request $request): JsonResponse
    {
        // $validator = Validator::make($request->all(),[
        //     'token' => 'required',
        // ]);
        // if ($validator->fails()) {
        //     return response()->json(['message'=>$validator->errors()],500);
        // }

        // $user = PersonalAccessToken::findToken($request->token);
        $user = Auth::user();
        if ($user == null) {
            return response()->json(["error" => 'Invalid LoggedIn User'], 422);
        }

        $history = UserTransactionDetails::where('user_id', $user->id)->orderBy("id", "desc")->paginate(50);
        $history->map(function ($data) {
            $data->documents = UserDocument::where("user_id", $data->user_id)->with('document')->first();
            $data->user = User::when($data->receiver_id == 'admin', function ($user) {
                return $user->where('utype', 'admin');
            })->where("id", $data->receiver_id)->select('name')->first();
            return $data;
        });

        return response()->json(compact('history'));
    }



    //admin

    public function sendBalance($id)
    {
        return view('sendBalance', compact('id'));
    }

    public function storeBalance(int $id, Request $request): RedirectResponse
    {
        $this->validate($request, [
            'amount' => 'required',
        ]);

        $send = new UserTransactionDetails();
        $send->t_type = "credit";
        $send->user_id = $id;
        $send->receiver_id = $id;
        $send->source_id = "send";
        $send->amount = $request->amount;
        $send->save();

        return redirect()->route('usermanagement')->with('success', "Balance send Successfully");
    }

    public function getDepositFee(Request $request): JsonResponse
    {
        $bankFee = Setting::get('bank_fee');
        $cardFee = Setting::get('card_fee');
        return response()->json([
            'success' => true,
            'data' => compact('cardFee', 'bankFee'),
        ]);
    }

    public function test_api(Request $request): JsonResponse
    {
        return response()->json("Success");
    }

    public function deleteUser(int $id): RedirectResponse
    {
        $user = User::find($id);
        if (!empty($user) && $user->utype == 'user') {
            Customer::deleteCustomer($user->priority_id);
            $user->delete();
            return redirect()->back()->with(['success' => 'Invalid User!']);
        } else {
            return redirect()->back()->withErrors('Invalid User!');
        }
    }

    private function storeWallet($data, $user)
    {
        return CryptoWallet::create([
            'user_id' => $user,
            'wallet_address' => Encryption::encrypt($data['address']),
            'private_key' => Encryption::encrypt($data['privateKey']),
            'secret_phrase' => Encryption::encrypt($data['secretPhrase']),
            'public_key' => Encryption::encrypt($data['publicKey']),
        ]);
    }

    public function createCryptoWallet()
    {
        $user = Auth::user();

        $wallet = Http::withHeaders([
            'X-Atolin-Node-Request-Only-X' => env('AUTH_KEY'),
        ])->post('http://127.0.0.1:4000/create-account');

        if ($wallet->failed()) {
            return response()->json(['error' => $wallet->json()], 400);
        }

        if ($wallet->successful()) {
            $data = $wallet->json();
            $crypto = $this->storeWallet($data['data'], $user->id);
            $crypto->wallet_address = Encryption::decrypt($crypto->wallet_address);
            $crypto->secret_phrase = Encryption::decrypt($crypto->secret_phrase);

            return response()->json(['message' => 'Wallet Created Successfully', 'wallet' => $crypto], 200);
        }
    }

    public function getCryptoBalance(Request $request)
    {
        $user = Auth::user();
        $wallet = CryptoWallet::where('user_id', $user->id)->first();
        if (empty($wallet)) {
            return response()->json(['error' => 'No Wallet is Connected with This Account'], 400);
        }

        $balance = Http::withHeaders([
            'X-Atolin-Node-Request-Only-X' => env('AUTH_KEY'),
        ])->post('http://127.0.0.1:4000/get-balance', ['walletPrivate' => Encryption::decrypt($wallet->private_key), 'address' => $request->address]);

        if ($balance->failed()) {
            return response()->json(['error' => $balance->json()], 400);
        }

        if ($balance->successful()) {
            $data = $balance->json();
            $data['token']['price'] = Setting::get('token_price');
            $data['token']['fees'] = Setting::get('swap_fee');

            return response()->json(['message' => 'Balance Get Successfully', 'balance' => $data], 200);
        }
    }

    public function getBalanceByAddress(Request $request)
    {
        $user = Auth::user();
        // get-balance-by-address
        $user = Auth::user();
        $wallet = CryptoWallet::where('user_id', $user->id)->first();
        if (empty($wallet)) {
            return response()->json(['error' => 'No Wallet is Connected with This Account'], 400);
        }

        $balance = Http::withHeaders([
            'X-Atolin-Node-Request-Only-X' => env('AUTH_KEY'),
        ])->post('http://127.0.0.1:4000/get-balance-by-address', ['walletPrivate' => Encryption::decrypt($wallet->private_key), 'contractAddress' => $request->address]);

        if ($balance->failed()) {
            return response()->json(['error' => $balance->json()], 400);
        }

        if ($balance->successful()) {
            $data = $balance->json()['balance'];

            return response()->json(['message' => 'Balance Get Successfully', 'balance' => $data], 200);
        }
    }

    public function importWallet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phrase' => "required",
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $wallet = Http::withHeaders([
            'X-Atolin-Node-Request-Only-X' => env('AUTH_KEY'),
        ])->post('http://127.0.0.1:4000/import-wallet', [
            'phrase' => $request->phrase,
        ]);

        if ($wallet->failed()) {
            return response()->json(['error' => $wallet->json()], 400);
        }

        if ($wallet->successful()) {
            $data = $wallet->json();

            $user = Auth::user();
            $wallet = CryptoWallet::where('user_id', $user->id)->first();
            if (empty($wallet)) {
                $crypto = $this->storeWallet($data['data'], $user->id);
                $crypto->wallet_address = Encryption::decrypt($crypto->wallet_address);
            } else {
                $wallet->wallet_address = Encryption::decrypt($wallet->wallet_address);
            }

            return response()->json(['message' => 'Balance Get Successfully', 'wallet' => empty($wallet) ? $crypto : $wallet], 200);
        }
    }

    public function verifyWallet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phrase' => "required",
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => "Secret Phrase is Not Valid"], 422);
        }

        $wallet = CryptoWallet::where('user_id', Auth::user()->id)->first();
        if (empty($wallet)) {
            return response()->json(['error' => 'No Wallet is Connected'], 422);
        }

        $phrase = Encryption::decrypt($wallet->secret_phrase);

        if ($phrase !== $request->phrase) {
            return response()->json(['error' => "Secret Phrase is Not Valid", 'message' => $phrase], 400);
        }

        return response()->json(['message' => 'Secret Phrase Matched Successfully']);

        // if ($wallet->successful()) {
        //     $data = $wallet->json();

        //     $user = Auth::user();
        //     $wallet = CryptoWallet::where('user_id', $user->id)->first();
        //     if (empty($wallet)) {
        //         $crypto = $this->storeWallet($data, $user->id);
        //         $crypto->wallet_address = Encryption::decrypt($crypto->wallet_address);
        //         $crypto->secret_phrase = Encryption::decrypt($crypto->secret_phrase);
        //     }

        //     return response()->json(['message' => 'Balance Get Successfully', 'wallet' => empty($wallet) ? $crypto : $wallet], 200);
        // }
    }

    public function sendBNB(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'toAddress' => 'required',
            'amount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $wallet = CryptoWallet::where('user_id', Auth::user()->id)->first();
        if (empty($wallet)) {
            return response()->json(['error' => 'No Wallet is Connected to this user'], 422);
        }

        $transaction = Http::withHeaders([
            'X-Atolin-Node-Request-Only-X' => env('AUTH_KEY'),
        ])->post('http://127.0.0.1:4000/transfer-bnb', [
            'walletPrivate' => Encryption::decrypt($wallet->private_key),
            'toAddress' => $request->toAddress,
            'amount' => $request->amount,
        ]);

        if ($transaction->successful()) {
            $response = $transaction->json();
            $tx = $response['data'];
            $amount = $response['amount'];
            $to = $response['toAddress'];
            // Store Transaction to Database
            CryptoTransaction::create([
                'user_id' => Auth::user()->id,
                'block_hash' => $tx['blockHash'],
                'amount' => $amount,
                'contract_address' => !empty($tx['contractAddress']) ? Encryption::encrypt($tx['contractAddress']) : null,
                'fee' => !empty($tx['fee']) ? $tx['fee'] : null,
                'from' => Encryption::encrypt($tx['from']),
                'gas_price' => $tx['gasPrice'],
                'hash' => $tx['hash'],
                'status' => $tx['status'],
                'to' => Encryption::encrypt($to),
                'transaction_type' => 'debit',
                'type' => 'bnb'
            ]);
            // $second_wallet = CryptoWallet::where('wallet_address', Encryption::encrypt($tx['from']))->first();
            // $second_user = User::where('id', $second_wallet->user_id)->first();
            // CryptoTransaction::create([
            //     'user_id' => $second_user->id ?? 0,
            //     'block_hash' => $tx['blockHash'],
            //     'amount' => $amount,
            //     'contract_address' => !empty($tx['contractAddress']) ? Encryption::encrypt($tx['contractAddress']) : null,
            //     'fee' => !empty($tx['fee']) ? $tx['fee'] : null,
            //     'from' => Encryption::encrypt($to),
            //     'gas_price' => $tx['gasPrice'],
            //     'hash' => $tx['hash'],
            //     'status' => $tx['status'],
            //     'to' => Encryption::encrypt($tx['from']),
            //     'transaction_type' => 'credit',
            //     'type' => 'bnb'
            // ]);
            return response()->json(['message' => 'Transaction Successful']);
        }
        if ($transaction->failed()) {
            return response()->json(['error' => $transaction->json()], 400);
        }
    }
    public function sendToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'toAddress' => 'required',
            'amount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $wallet = CryptoWallet::where('user_id', Auth::user()->id)->first();
        if (empty($wallet)) {
            return response()->json(['error' => 'No Wallet is Connected to this user'], 422);
        }

        $transaction = Http::withHeaders([
            'X-Atolin-Node-Request-Only-X' => env('AUTH_KEY'),
        ])->post('http://127.0.0.1:4000/transfer-token', [
            'walletPrivate' => Encryption::decrypt($wallet->private_key),
            'toAddress' => $request->toAddress,
            'amount' => $request->amount,
        ]);

        if ($transaction->successful()) {
            $response = $transaction->json();
            $tx = $response['data'];
            $amount = $response['amount'];
            $to = $response['toAddress'];
            // Store Transaction to Database
            CryptoTransaction::create([
                'user_id' => Auth::user()->id,
                'amount' => $amount,
                'block_hash' => $tx['blockHash'],
                'contract_address' => !empty($tx['contractAddress']) ? Encryption::encrypt($tx['contractAddress']) : null,
                'fee' => !empty($tx['fee']) ? $tx['fee'] : null,
                'from' => Encryption::encrypt($tx['from']),
                'gas_price' => $tx['gasPrice'],
                'hash' => $tx['hash'],
                'status' => $tx['status'],
                'to' => Encryption::encrypt($to),
                'transaction_type' => 'debit',
                'type' => 'token'
            ]);
            // $second_wallet = CryptoWallet::where('wallet_address', Encryption::encrypt($tx['from']))->first();
            // $second_user = User::where('id', $second_wallet->user_id)->first();
            // CryptoTransaction::create([
            //     'user_id' => $second_user->id ?? 0,
            //     'amount' => $amount,
            //     'block_hash' => $tx['blockHash'],
            //     'contract_address' => !empty($tx['contractAddress']) ? Encryption::encrypt($tx['contractAddress']) : null,
            //     'fee' => !empty($tx['fee']) ? $tx['fee'] : null,
            //     'from' => Encryption::encrypt($to),
            //     'gas_price' => $tx['gasPrice'],
            //     'hash' => $tx['hash'],
            //     'status' => $tx['status'],
            //     'to' => Encryption::encrypt($tx['from']),
            //     'transaction_type' => 'credit',
            //     'type' => 'token'
            // ]);
            return response()->json(['message' => 'Transaction Successful']);
        }
        if ($transaction->failed()) {
            return response()->json(['error' => $transaction->json()], 400);
        }
    }

    public function transferCrypto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'toAddress' => 'required',
            'amount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $wallet = CryptoWallet::where('user_id', Auth::user()->id)->first();
        if (empty($wallet)) {
            return response()->json(['error' => 'No Wallet is Connected to this user'], 422);
        }

        $transaction = Http::withHeaders([
            'X-Atolin-Node-Request-Only-X' => env('AUTH_KEY'),
        ])->post($request->contractAddress === 'binance' ? 'http://127.0.0.1:4000/transfer-bnb' : 'http://127.0.0.1:4000/transfer-token', [
            'walletPrivate' => Encryption::decrypt($wallet->private_key),
            'toAddress' => $request->toAddress,
            'amount' => $request->amount,
            'contractAddress' => $request->contractAddress,
        ]);

        if ($transaction->successful()) {
            $response = $transaction->json();
            $tx = $response['data'];
            $amount = $response['amount'];
            $to = $response['toAddress'];
            $contractAddress = $response['contractAddress'];
            // Store Transaction to Database
            CryptoTransaction::create([
                'user_id' => Auth::user()->id,
                'amount' => $amount,
                'block_hash' => $tx['blockHash'],
                'contract_address' => !empty($contractAddress) ? $contractAddress : null,
                'fee' => !empty($tx['fee']) ? $tx['fee'] : null,
                'from' => Encryption::encrypt($tx['from']),
                'gas_price' => $tx['gasPrice'],
                'hash' => $tx['hash'],
                'status' => $tx['status'],
                'to' => Encryption::encrypt($to),
                'transaction_type' => 'debit',
                'type' => $request->contractAddress === 'binance' ? 'bnb' : 'token'
            ]);
            return response()->json(['message' => 'Transaction Successful']);
        }
        if ($transaction->failed()) {
            return response()->json(['error' => $transaction->json()], 400);
        }
    }

    public function transactionHistory(Request $request)
    {
        $user = Auth::user();
        $wallet = CryptoWallet::where('user_id', $user->id)->first();
        $history = CryptoTransaction::where(function ($q) use ($wallet) {
            $q->where('from', $wallet->wallet_address)->orWhere('to', $wallet->wallet_address);
        })->where('type', $request->type ?? 'bnb')->where('contract_address', $request->contract_address)->orderBy('created_at', 'DESC')->paginate(10);
        return response()->json(['message' => "History Get", 'history' => $history]);
    }

    public function checkAddress(Request $request)
    {
        $contract = Http::withHeaders([
            'X-Atolin-Node-Request-Only-X' => env('AUTH_KEY'),
        ])->post('http://127.0.0.1:4000/check-token', [
            'address' => $request->address,
        ]);

        if ($contract->successful()) {
            $data = $contract->json()['token'];
            return response()->json(['message' => 'Token valid', 'token' => ['tokenAddress' => $data['tokenAddress'], 'tokenDecimals' => $data['tokenDecimals'], 'tokenName' => $data['tokenName'], 'tokenSymbol' => $data['tokenSymbol']]]);
        }
        if ($contract->failed()) {
            return response()->json(['error' => 'Invalid Address']);
        }
    }

    public function swapToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from' => 'required',
            'amount' => 'required',
            'to' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Please check all feilds', 'message' => 'Request Failed'], 422);
        }

        $wallet = CryptoWallet::where('user_id', Auth::user()->id)->first();
        if (empty($wallet)) {
            return response()->json(['error' => 'No Wallet is Connected to this user'], 422);
        }

        $tokenFee = Setting::get('swap_fee');

        $walletPrivate = '';
        $reciever = '';
        $tokenAmount = 0;
        $atolinAmount = 0;
        $transaction_type = '';
        $transaction_id = '';
        if ($request->from === 'atolin-wallet') {
            $walletPrivate = Encryption::decrypt(Setting::get('wallet_private'));
            $reciever = Encryption::decrypt($wallet->wallet_address);
            $transaction_type = 'debit';
            $tokenPrice = Setting::get('token_price');
            $rawTokenAmount = $request->amount / $tokenPrice;
            $tokenAmount = $rawTokenAmount * (1 - $tokenFee / 100);
            $atolinAmount = $request->amount;
            if ($atolinAmount > $this->Balance(Auth::user()->id)) {
                return response()->json([
                    'error' => 'Insufficiant Wallet Balance',
                ], 400);
            }
            $userAccount = Account::getByUser(Auth::user()->priority_id);
            if (!$userAccount['status']) {
                return response()->json([
                    'error'=> 'You don\'t have account on Platform',
                    ], 400);
            }
            $accountId = $userAccount['response']['objects'][0]['guid'];
            $cybridTransaction = Transaction::sendUserToUser($atolinAmount  * 100, $accountId, env('CYBRID_MAIN_ACCOUNT'));
            if (!$cybridTransaction['status']) {
                return response()->json(['error' => $cybridTransaction['response']], 400);
            }
            $transaction_id = $cybridTransaction['response']['guid'];
        } else {
            $walletPrivate = Encryption::decrypt($wallet->private_key);
            $reciever = Encryption::decrypt(Setting::get('wallet_address'));
            $transaction_type = 'credit';
            $tokenPrice = Setting::get('token_price');
            $tokenAmount = $request->amount;
            // $tokenAmount = ($rawTokenAmount * $tokenFee) / 100;
            $netTokenAmount = $tokenAmount * (1 - $tokenFee / 100);
            $finalPrice = $netTokenAmount * $tokenPrice;
            $atolinAmount = $finalPrice;
            $userAccount = Account::getByUser(Auth::user()->priority_id);
            if (!$userAccount['status']) {
                return response()->json([
                    'error'=> 'You don\'t have account on Platform',
                    ], 400);
            }
            $accountId = $userAccount['response']['objects'][0]['guid'];
            $cybridTransaction = Transaction::sendUserToUser($atolinAmount * 100, env('CYBRID_MAIN_ACCOUNT'), $accountId);
            if (!$cybridTransaction['status']) {
                return response()->json(['error' => $cybridTransaction['response']], 400);
            }
            $transaction_id = $cybridTransaction['response']['guid'];
        }

        $transaction = Http::withHeaders([
            'X-Atolin-Node-Request-Only-X' => env('AUTH_KEY'),
        ])->post('http://127.0.0.1:4000/transfer-token', [
            'walletPrivate' => $walletPrivate,
            'toAddress' => $reciever,
            'amount' => $tokenAmount,
        ]);

        if ($transaction->successful()) {
            $response = $transaction->json();
            $tx = $response['data'];
            $amount = $response['amount'];
            $to = $response['toAddress'];
            $contractAddress = $response['contractAddress'] ?? '';
            // Store Transaction to Database
            CryptoTransaction::create([
                'user_id' => Auth::user()->id,
                'amount' => $amount,
                'block_hash' => $tx['blockHash'],
                'contract_address' => !empty($contractAddress) ? $contractAddress : null,
                'fee' => !empty($tx['fee']) ? $tx['fee'] : null,
                'from' => Encryption::encrypt($tx['from']),
                'gas_price' => $tx['gasPrice'],
                'hash' => $tx['hash'],
                'status' => $tx['status'],
                'to' => Encryption::encrypt($to),
                'transaction_type' => $request->from === 'atolin-wallet' ? 'credit' : 'debit',
                'type' => 'token'
            ]);
            UserTransactionDetails::create([
                'transaction_id' => $transaction_id,
                'user_id' => Auth::user()->id,
                'source_id' => !empty($contractAddress) ? $contractAddress : null,
                'amount' => $atolinAmount,
                't_type' => $transaction_type,
                'transaction_type' => 'token_swap',
                'status' => 'success',
            ]);
            return response()->json(['message' => 'Transaction Successful']);
        }
        if ($transaction->failed()) {
            return response()->json(['error' => $transaction->json()], 400);
        }
    }

    public function testNode()
    {
        $res = Http::get('http://127.0.0.1:4000/test');
        return response()->json($res->json());
    }
}
