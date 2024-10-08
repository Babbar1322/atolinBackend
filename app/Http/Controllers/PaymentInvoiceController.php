<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Models\PaymentInvoice;
use Http;
use Illuminate\Http\Request;
use Log;
use Mail;

class PaymentInvoiceController extends Controller
{
    public function showForm()
    {
        return view('payment-invoice');
    }
    public function showCardForm()
    {
        return view('payment-card-invoice');
    }

    public function makePayment(Request $request)
    {
        $request->validate([
            'account_number' => 'required',
            'account_holder_name' => 'required',
            'routing_number' => 'required|numeric|min:9',
            'account_type' => 'required|in:Savings,Checking',
            'contact_name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required'
        ]);

        if (empty($request->query('amount')) && empty($request->amount)) {
            return redirect()->back()->withErrors(['amount' => 'Amount is Required'])->withInput();
        }
        if (empty($request->query('invoice_id')) && empty($request->invoice_id)) {
            return redirect()->back()->withErrors(['invoice_id' => 'Invoice ID is Required'])->withInput();
        }

        $data = [
            'paymentType' => 'Sale',
            'bankAccount' => [
                'accountNumber' => $request->account_number, // 'account_number',
                'routingNumber' => $request->routing_number, // 'routing_number',
                'type' => $request->account_type, // 'account_type',
                'name' => $request->account_holder_name, // 'account_holder_name'
            ],
            'entryClass' => 'WEB',
            'authOnly' => false,
            'isAuth' => false,
            'isSettleFunds' => false,
            'source' => 'API',
            'taxExempt' => false,
            'merchantId' => env('MX_MERCHANT_ID'),
            'amount' => $request->amount, // 'amount',
            'invoice' => $request->invoice_id, // 'invoice_max_16'
            'tenderType' => 'ACH'
        ];
        $response = Http::withHeaders([
            'PromiseMode' => 'NEVER',
            'Authorization' => 'Basic ' . base64_encode(env('PRIORITY_USER') . ":" . env('PRIORITY_PASS')),
        ])->post("https://sandbox.api.mxmerchant.com/checkout/v3/payment?echo=true&includeCustomerMatches=false", $data);

        if ($response->successful()) {
            $res = $response->json();
            $invoiceData = $request->except('_method', '_token');
            $invoiceData['payment_id'] = $res['id'];
            $invoiceData['status'] = $res['status'];
            PaymentInvoice::create($invoiceData);
            if ($res['status'] === 'Declined') {
                return redirect()->back()->withErrors(['error' => $res['authMessage']])->withInput();
            }
            $netResponse = $this->netsuiteReq($request->invoice_id, $request->amount);
            if (!$netResponse['status']) {
                Log::info("NetSuite Error Start " . date('d-m-y h:i:s'));
                Log::info($netResponse['data']);
                Log::info("NetSuite Error End");
            }
            if ($request->has('email') && !empty($request->email)) {
                Mail::to($request->email)->send(new WelcomeMail($request->invoice_id, $request->amount));
            }
            return redirect()->back()->with('success', 'Request Submitted');
        }
        if ($response->failed()) {
            if (!empty($response->json()['message'])) {
                if ($response->json()['message'] === 'Validation error happened') {
                    return redirect()->back()->withErrors(['error' => $response->json()['details']])->withInput();
                }
                return redirect()->back()->withErrors(['error' => $response->json()['message']])->withInput();
            }
            return redirect()->back()->withErrors(['error' => $response->json()])->withInput();
        }
    }

    public function makeCardPayment(Request $request)
    {
        $request->validate([
            'card_number' => 'required',
            'expiry_month' => 'required',
            'expiry_year' => 'required',
            'cvv' => 'required|numeric',
            'contact_name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required'
        ]);

        // dd($request->all());

        if (empty($request->query('amount')) && empty($request->amount)) {
            return redirect()->back()->withErrors(['amount' => 'Amount is Required'])->withInput();
        }
        if (empty($request->query('invoice_id')) && empty($request->invoice_id)) {
            return redirect()->back()->withErrors(['invoice_id' => 'Invoice ID is Required'])->withInput();
        }

        $data = [
            'paymentType' => 'Sale',
            'cardAccount' => [
                'number' => $request->card_number,
                'expiryMonth' => $request->expiry_month,
                'expiryYear' => $request->expiry_year,
                'cvv' => $request->cvv,
                'avsStreet' => $request->address,
                'avsZip' => $request->zip
            ],
            'entryClass' => 'WEB',
            'authOnly' => false,
            'isAuth' => false,
            'isSettleFunds' => false,
            'source' => 'API',
            'taxExempt' => false,
            'merchantId' => env('MX_MERCHANT_ID'),
            'amount' => $request->amount,
            'tenderType' => 'Card',
            'invoice' => $request->invoice_id,
          ];
        $response = Http::withHeaders([
            'PromiseMode' => 'NEVER',
            'Authorization' => 'Basic ' . base64_encode(env('PRIORITY_USER') . ":" . env('PRIORITY_PASS')),
        ])->post("https://sandbox.api.mxmerchant.com/checkout/v3/payment?echo=true&includeCustomerMatches=false", $data);

        if ($response->successful()) {
            $res = $response->json();
            $invoiceData = $request->except('_method', '_token');
            $invoiceData['status'] = $res['status'];
            PaymentInvoice::create($invoiceData);
            if ($res['status'] === 'Declined') {
                return redirect()->back()->withErrors(['error' => $res['authMessage']])->withInput();
            }
            $netResponse = $this->netsuiteReq($request->invoice_id, $request->amount);
            if (!$netResponse['status']) {
                Log::info("NetSuite Error Start " . date('d-m-y h:i:s'));
                Log::info($netResponse['data']);
                Log::info("NetSuite Error End");
            }
            if ($request->has('email') && !empty($request->email)) {
                Mail::to($request->email)->send(new WelcomeMail($request->invoice_id, $request->amount));
            }
            return redirect()->back()->with('success', 'Request Submitted');
        }
        if ($response->failed()) {
            if (!empty($response->json()['message'])) {
                if ($response->json()['message'] === 'Validation error happened') {
                    return redirect()->back()->withErrors(['error' => $response->json()['details']])->withInput();
                }
                return redirect()->back()->withErrors(['error' => $response->json()['message']])->withInput();
            }
            return redirect()->back()->withErrors(['error' => $response->json()])->withInput();
        }
    }

    public function netsuiteReq($invoice, $amount)
    {

        if (!defined('NETSUITE_DEPLOYMENT_URL')) {
            define("NETSUITE_DEPLOYMENT_URL", 'https://7345457-sb2.restlets.api.netsuite.com/app/site/hosting/restlet.nl?script=2187&deploy=1');
        }
        if (!defined('NETSUITE_URL')) {
            define("NETSUITE_URL", 'https://7345457-sb2.restlets.api.netsuite.com');
        }
        if (!defined('NETSUITE_REST_URL')) {
            define("NETSUITE_REST_URL", 'https://7345457-sb2.restlets.api.netsuite.com/app/site/hosting/restlet.nl');
        }
        if (!defined('NETSUITE_SCRIPT_ID')) {
            define("NETSUITE_SCRIPT_ID", 2187);
        }
        if (!defined('NETSUITE_DEPLOY_ID')) {
            define("NETSUITE_DEPLOY_ID", 1);
        }
        if (!defined('NETSUITE_ACCOUNT')) {
            define("NETSUITE_ACCOUNT", '7345457_SB2');
        }
        if (!defined('NETSUITE_CONSUMER_KEY')) {
            define("NETSUITE_CONSUMER_KEY", env('NETSUITE_CLIENT_ID'));
        }
        if (!defined('NETSUITE_CONSUMER_SECRET')) {
            define("NETSUITE_CONSUMER_SECRET", env('NETSUITE_CLIENT_SECRET'));
        }
        if (!defined('NETSUITE_TOKEN_ID')) {
            define("NETSUITE_TOKEN_ID", env('NETSUITE_TOKEN_ID'));
        }
        if (!defined('NETSUITE_TOKEN_SECRET')) {
            define("NETSUITE_TOKEN_SECRET", env('NETSUITE_TOKEN_SECRET'));
        }

        $payload = array(
            "invoiceNumber" => $invoice,
            "baseAmount" => $amount
        );

        $oauth_nonce = md5(mt_rand());
        $oauth_timestamp = time();
        $oauth_signature_method = 'HMAC-SHA256';
        $oauth_version = "1.0";

        $base_string =
            "POST&" . urlencode(NETSUITE_REST_URL) . "&" .
            urlencode(
                "deploy=" . NETSUITE_DEPLOY_ID
                    . "&oauth_consumer_key=" . NETSUITE_CONSUMER_KEY
                    . "&oauth_nonce=" . $oauth_nonce
                    . "&oauth_signature_method=" . $oauth_signature_method
                    . "&oauth_timestamp=" . $oauth_timestamp
                    . "&oauth_token=" . NETSUITE_TOKEN_ID
                    . "&oauth_version=" . $oauth_version
                    . "&script=" . NETSUITE_SCRIPT_ID
            );

        $key = rawurlencode(NETSUITE_CONSUMER_SECRET) . '&' . rawurlencode(NETSUITE_TOKEN_SECRET);
        $signature = base64_encode(hash_hmac("sha256", $base_string, $key, true));
        $auth_header = 'OAuth '
            . 'realm="' . rawurlencode(NETSUITE_ACCOUNT) . '",'
            . 'oauth_consumer_key="' . rawurlencode(NETSUITE_CONSUMER_KEY) . '",'
            . 'oauth_token="' . rawurlencode(NETSUITE_TOKEN_ID) . '",'
            . 'oauth_signature_method="' . rawurlencode($oauth_signature_method) . '",'
            . 'oauth_timestamp="' . rawurlencode($oauth_timestamp) . '",'
            . 'oauth_nonce="' . rawurlencode($oauth_nonce) . '",'
            . 'oauth_version="' . rawurlencode($oauth_version) . '",'
            . 'oauth_signature="' . rawurlencode($signature) . '"';

            $response = Http::withHeaders([
                "Authorization" => $auth_header,
                "Content-Type" => "application/json"
            ])->post(NETSUITE_DEPLOYMENT_URL, $payload);

            if($response->successful()) {
                return ["status" => true, "data" => $response->json()];
            }

            if($response->failed()) {
                return ["status" => false, "data" => $response->json()];
            }
    }

    public function netsuiteInvoice(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'amount' => 'required|numeric',
            'category' => 'required',
            'date' => 'required',
            'netsuiteUrl' => 'required',
            'realm' => 'required',
            'consumerKey' => 'required',
            'consumerSecret' => 'required',
            'accessToken' => 'required',
            'tokenSecret' => 'required',
        ], [
            'netsuiteUrl.required' => 'NetSuite URL is Required',
            'realm.required' => 'Realm is Required',
            'consumerKey.required' => 'Consumer Key is Required',
            'consumerSecret.required' => 'Consumer Secret is Required',
            'accessToken.required' => 'Access Token is Required',
            'tokenSecret.required' => 'Token Secret is Required',
        ]);
        $url = self::extractUrlParts($request->netsuiteUrl);
        if (!defined('NETSUITE_DEPLOYMENT_URL')) {
            define("NETSUITE_DEPLOYMENT_URL", $request->netsuiteUrl);
        }
        if (!defined('NETSUITE_URL')) {
            define("NETSUITE_URL", $url['baseUrl']);
        }
        if (!defined('NETSUITE_REST_URL')) {
            define("NETSUITE_REST_URL", $url['fullPath']);
        }
        if (!defined('NETSUITE_SCRIPT_ID')) {
            define("NETSUITE_SCRIPT_ID", $url['scriptId']);
        }
        if (!defined('NETSUITE_DEPLOY_ID')) {
            define("NETSUITE_DEPLOY_ID", $url['deployId']);
        }
        if (!defined('NETSUITE_ACCOUNT')) {
            define("NETSUITE_ACCOUNT", $request->realm);
        }
        if (!defined('NETSUITE_CONSUMER_KEY')) {
            define("NETSUITE_CONSUMER_KEY", $request->consumerKey);
        }
        if (!defined('NETSUITE_CONSUMER_SECRET')) {
            define("NETSUITE_CONSUMER_SECRET", $request->consumerSecret);
        }
        if (!defined('NETSUITE_TOKEN_ID')) {
            define("NETSUITE_TOKEN_ID", $request->accessToken);
        }
        if (!defined('NETSUITE_TOKEN_SECRET')) {
            define("NETSUITE_TOKEN_SECRET", $request->tokenSecret);
        }

        $payload = array(
            "email" => $request->email,
            "amount" => $request->amount,
            "category" => $request->category,
            "date" => $request->date,
        );

        $oauth_nonce = md5(mt_rand());
        $oauth_timestamp = time();
        $oauth_signature_method = 'HMAC-SHA256';
        $oauth_version = "1.0";

        $base_string =
            "POST&" . urlencode(NETSUITE_REST_URL) . "&" .
            urlencode(
                "deploy=" . NETSUITE_DEPLOY_ID
                    . "&oauth_consumer_key=" . NETSUITE_CONSUMER_KEY
                    . "&oauth_nonce=" . $oauth_nonce
                    . "&oauth_signature_method=" . $oauth_signature_method
                    . "&oauth_timestamp=" . $oauth_timestamp
                    . "&oauth_token=" . NETSUITE_TOKEN_ID
                    . "&oauth_version=" . $oauth_version
                    . "&script=" . NETSUITE_SCRIPT_ID
            );

        $key = rawurlencode(NETSUITE_CONSUMER_SECRET) . '&' . rawurlencode(NETSUITE_TOKEN_SECRET);
        $signature = base64_encode(hash_hmac("sha256", $base_string, $key, true));
        $auth_header = 'OAuth '
            . 'realm="' . rawurlencode(NETSUITE_ACCOUNT) . '",'
            . 'oauth_consumer_key="' . rawurlencode(NETSUITE_CONSUMER_KEY) . '",'
            . 'oauth_token="' . rawurlencode(NETSUITE_TOKEN_ID) . '",'
            . 'oauth_signature_method="' . rawurlencode($oauth_signature_method) . '",'
            . 'oauth_timestamp="' . rawurlencode($oauth_timestamp) . '",'
            . 'oauth_nonce="' . rawurlencode($oauth_nonce) . '",'
            . 'oauth_version="' . rawurlencode($oauth_version) . '",'
            . 'oauth_signature="' . rawurlencode($signature) . '"';

        $response = Http::withHeaders([
            "Authorization" => $auth_header,
            "Content-Type" => "application/json"
        ])->post(NETSUITE_DEPLOYMENT_URL, $payload);

        if ($response->successful()) {
            $data = $response->json();
            if (!$data['success']) {
                Log::error("NetSuite Error Start " . date('d-m-y h:i:s'));
                Log::error(json_encode($data));
                Log::error("NetSuite Error End");
                return ["status" => false, "data" => $data];
            }
            return ["status" => true, "data" => $response->json()];
        }

        if ($response->failed()) {
            Log::error("NetSuite Error Start " . date('d-m-y h:i:s'));
            Log::error(json_encode($response->json()));
            Log::error("NetSuite Error End");
            return ["status" => false, "data" => $response->json()];
        }
    }

    private static function extractUrlParts($url) {
        if (empty($url)) {
            return;
        }
        // Parse the URL and its components
        $parsedUrl = parse_url($url);

        // Extract the base URL (scheme + host)
        $baseUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];

        // Extract the full path
        $fullPath = $baseUrl . $parsedUrl['path'];

        // Extract the script and deploy id from query string
        parse_str($parsedUrl['query'], $queryParams);
        $scriptId = isset($queryParams['script']) ? $queryParams['script'] : null;
        $deployId = isset($queryParams['deploy']) ? $queryParams['deploy'] : null;

        // Extract the account id from the host
        preg_match('/(\d+-\w+)/', $parsedUrl['host'], $matches);
        $accountId = isset($matches[1]) ? str_replace('-', '_', strtoupper($matches[1])) : null;

        return [
            'baseUrl' => $baseUrl,
            'fullPath' => $fullPath,
            'scriptId' => $scriptId,
            'deployId' => $deployId,
            'accountId' => $accountId
        ];
    }
}
