<?php

namespace App\Http\Controllers;
use ValidatesRequests;
use Auth;
use Cache;
use Crypt;
use Google2FA;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Illuminate\Foundation\Validation\ValidatesRequests;
use \ParagonIE\ConstantTime\Base32;
use App\Http\Requests\ValidateSecretRequest;
use App\Models\User;

class Google2FAController extends Controller
{
    

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('web');
    }

    /**
     * Generate a secret key in Base32 format
     *
     * @return string
     */
    private function generateSecret()
    {
        $randomBytes = random_bytes(10);

        return Base32::encodeUpper($randomBytes) ;
    }

    /**
     * Used to validate G2F
     */
    public function g2fotpcheckenable(Request $request)
    {    
        try {
            $userId = Auth::user()->id;                
            $key    = $userId . ':' . $request->totp;                
            $secret = Crypt::decryptString(Auth::user()->g2f_temp);
            $temp=Google2FA::verifyKey($secret,$request->totp);

            $response=[];
            $status=0;
            $message="";

            if(!Cache::has($key)){
                if($temp==true){
                    Cache::add($key, true, 4);

                    $user=User::findOrFail($userId);
                    $user->two_factor_secret = $user->g2f_temp;
                    $user->g2f_status = "1";
                    //$user->g2f_temp = Null;
                    $user->save();
                    return response()->json([
                        'statusCode' => 200,
                        'success' => true,
                        'msg' => 'Google two factor authentication enabled successfully',
                        ]); 
                }else{                
                    return response()->json([
                        'statusCode' => 420,
                        'success' => false,
                        'msg' => 'Please check the otp, and try again',
                        ]); 
                    // return response()->json(['error' => ['msg' => 'Please check the otp, and try again...']],420);
                }
            }else{  
                return response()->json([
                    'statusCode' => 420,
                    'success' => false,
                    'msg' => 'Used token,Cannot reuse token',
                    ]);           
                // return response()->json(['error' => ['msg' => 'Used token,Cannot reuse token...']],420);           
            }             
        } catch (\Throwable $th) {
            return response()->json([
                'statusCode' => 500,
                'success' => false,
                'msg' => 'something went wrong',
                ]);   
            // return response()->json(['error' => ['msg' => 'something went wrong']],500);
        }
    }

    public function enableTwoFactorapi()
    {
        try{
            //generate new secret
            $secret = $this->generateSecret();

            //get user
            $user = Auth::user();

            //encrypt and then save secret            
            $user->g2f_temp = Crypt::encryptString($secret);
            $user->save();
            return response()->json([
                'statusCode' => 200,
                'success' => true,
                'msg' => 'success',
                'secret' => $secret
                ]);  
            // return response()->json(['secret' => $secret], 200); 

        } catch (Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'success' => false,
                'msg' => 'something went wrong',
                ]);  
            // return response()->json(['error' => ['msg' => 'something went wrong']],500);
        }
        
    }

    public function disableTwoFactorapi()
    {
        try{
            $user = Auth::user();
            //make secret column blank
            $user->two_factor_secret = null;
            $user->g2f_status = 0;
            $user->save(); 
            return response()->json([
                'statusCode' => 200,
                'success' => true,
                'msg' => 'Disabled Successfully',
                ]);  
            // return response()->json(['success' => ['msg' =>'Disabled Successfully']], 200); 
            
        } catch (Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'success' => false,
                'msg' => 'something went wrong',
                ]);  
            // return response()->json(['error' => ['msg' => 'something went wrong']],500);
        }
       
    }

    public function enableTwoFactor(Request $request)
    {
        //generate new secret

        $secret = $this->generateSecret();
        //get user
        $user = $request->user();

        //encrypt and then save secret
        $user->g2f_temp = Crypt::encryptString($secret);
        $user->save();

        //generate image for QR barcode
        $imageDataUri = Google2FA::getQRCodeInline(
            $request->getHttpHost(),
            $user->email,
            $secret,
            200
        );

        // return "goo";   
        return  ['image' => $imageDataUri,'secret' => $secret];
    }
    public function g2fotpcheckenablenew(Request $request)
    {        
        try{
            $user = Auth::user();        
            $key    = $user->id . ':' . $request->totp;    
            $secret = Crypt::decryptString(Auth::user()->g2f_temp);
            $verifyOtp=Google2FA::verifyKey($secret,$request->totp);

            if(!Cache::has($key)){
                if($verifyOtp==true){
                    Cache::add($key, true, 4);

                    $user=Auth::user();
                    $user->g2f_status = '1';
                    $user->google2fa_secret = $user->g2f_temp;
                    // $user->g2f_temp = Null;
                    $user->save();

                    $status=1;
                    $message= 'Google two factor authentication enabled successfully...';

                }else{
                    $status=0;
                    $message='Please check the otp, and try again...';
                }
            }else{

                $status=0;
                $message='Used token,Cannot reuse token...';
            } 
            return response()->json([
                'statusCode' => 500,
                'success' => false,
                'message' => $message,
                'status'=>$status
                ]); 
            // return response()->json(['status'=>$status,'message'=>$message], 200); 
        }catch(Exception $e){

        }
    }


    public function disableTwoFactor(Request $request)
    {
        $this->validate($request, [
            'otp' => 'required|digits:6',
        ]);
        $user = $request->user();
        $userId = $user->id; 
        $key    = $userId . ':' . $request->otp;        
        // $secret = Crypt::decrypt(Auth::user()->google2fa_secret);
        $secret = Crypt::decryptString($user->two_factor_secret);
        $temp=Google2FA::verifyKey($secret,$request->otp);

        if(!Cache::has($key)){
            if($temp==true){
                Cache::add($key, true, 4);

                // $user=Auth::user();
                $user->two_factor_secret = null;
                // $user->g2f_temp = null;
                $user->g2f_status = '0';
                $user->save();
                $status=1;
                return redirect('/security')->with('flash_success','G2FA disabled');
                // $message= 'Google two factor authentication enabled successfully...';
            }else{
                $status=0;
                return redirect('/security')->with('flash_error','Please check the otp, and try again...');
                // $message='Please check the otp, and try again...';
            }
        }else{
            $status=0;
            return redirect('/security')->with('flash_error','Used token,Cannot reuse token...');
            // $message='Used token,Cannot reuse token...';
        } 
        // return response()->json(['status'=>$status,'message'=>$message], 200); 
        //make secret column blank
        return redirect('/security')->with('flash_success','G2FA disabled');
    }
    public function forgotG2FA(Request $request){
        try{
            if(!$request->ajax()){
                $id = session('2fa:user:id');
                $user = \App\User::find($id);
                $request['email'] = $user->email;
            }
            $this->validate($request,[
                'email' => 'required|email|exists:mysql.users,email'
            ]);
            $checkUser = \App\User::where('email',$request->email)->first();
            if($checkUser){
                if($checkUser->g2f_status != "1" && is_null($checkUser->google2fa_secret)){
                    if($request->ajax())
                        return response()->json([
                        'statusCode' => 420,
                        'success' => false,
                        'message' => 'user does not enabled 2FA',
                        ]); 
                        // return response()->json(['error' => ['msg' => 'user does not enabled 2FA']],400);
                    else
                        return back()->with('flash_error','user does not enabled 2FA');
                }

                $checkPRevious = \App\Forgot2fa::where('token',$checkUser->email_token)->whereNull('g2fa_key')->get();
                if(count($checkPRevious) > 0){
                    $currentTime = new \DateTime;
                    foreach ($checkPRevious as $key => $value) {
                        $diff = $value->created_at->diffInMinutes($currentTime);
                        if($diff > 3){
                            $value->g2fa_key = "EXPIRED";
                            $value->save();
                        }else{
                            if($request->ajax())
                            return response()->json([
                                'statusCode' => 200,
                                'success' => true,
                                'message' => 'We have sent 6 Digits OTP number to your registered email',
                                ]); 
                                // return response()->json(['success' => ['msg' => 'We have sent 6 Digits OTP number to your registered email.']],200);
                            else
                                // \Session::flash('flash_success','We have sent OTP to your email.');
                                return view('2fa.validate');
                        }
                    }
                }

                $otp = rand(100000,999999);
                $checkOTP = \App\Forgot2fa::where('otp',$otp)->whereNull('g2fa_key')->get();
                if(count($checkOTP) > 0){
                    $otp = rand(100000, 999999);
                }
                $reset2FA = new \App\Forgot2fa;
                $reset2FA->token = $checkUser->email_token;
                $reset2FA->otp = $otp;
                $reset2FA->save();
                $data['otp'] = $otp;
                Mail::to($checkUser->email)->send(new DisableGFA($data));
                if($request->ajax())
                return response()->json([
                    'statusCode' => 200,
                    'success' => true,
                    'message' => 'We have sent 6 Digits OTP number to your registered email',
                    ]); 
                    // return response()->json(['success' => ['msg' => 'We have sent 6 Digits OTP number to your registered email.']],200);
                else
                    \Session::flash('flash_success','We have sent 6 Digits OTP number to your registered email.');
                    return view('2fa.validate');
            }else{
                if($request->ajax())
                return response()->json([
                    'statusCode' => 420,
                    'success' => false,
                    'message' => 'E-mail does not exits in our record',
                    ]); 
                    
                    // return response()->json(['error' => ['msg' => 'E-mail does not exits in our record.']],400);
                else
                    return back()->with('flash_error','E-mail does not exits in our Record.');
                    
            }
        }catch(Exception $e){
            return response()->json([
                'statusCode' => 500,
                'success' => false,
                'message' => 'Soemthing went wrong',
                ]); 
            // return response()->json(['error' => ['msg' => 'Something Went Wrong']],500);
        }
    }

    public function gfavalidateotp(Request $request)
    {   
        try{     
            $userId = Auth::user()->id;        
            //$value=$request->totp;
            $key    = $userId . ':' . $request->totp;        
            $secret = Crypt::decryptString(Auth::user()->g2f_temp);
            $temp=Google2FA::verifyKey($secret,$request->totp);

            if(!Cache::has($key)){
                if($temp==true){
                    Cache::add($key, true, 4);
                    // Auth::loginUsingId($userId);
                    return response()->json([
                        'statusCode' => 200,
                        'success' => true,
                        'message' => 'Logged Successfully',
                        'status' => 1
                        ]); 
                }else{
                    return response()->json([
                        'statusCode' => 420,
                        'success' => false,
                        'message' => 'Invalid OTP',
                        'status' => 0
                        ]); 
                }
            }else{
                //echo "Used TOken,Cannot reuse token";
                return response()->json([
                    'statusCode' => 420,
                    'success' => false,
                    'message' => 'Cannot reuse token',
                    'status' => 0
                    ]);
                // return response()->json(['status'=>0,'error' => ['msg' =>trans('flash.admin.token_reuse')]], 420); 
            }   
        } catch (Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'success' => false,
                'message' => 'Something went wrong'
                ]);
            //  return response()->json(['error' => ['msg' => trans('flash.admin.something_went_wrong')]], 500);
        }      
    }

    public function validateG2FAOtp(Request $request){
        try{
            if(!$request->ajax()){
                $id = session('2fa:user:id');
                $user = \App\User::find($id);
                $request['email'] = $user->email;
            }
            $this->validate($request,[
                'otp' => 'required|integer|digits:6',
                'email' => 'required|email',
            ]);
            $user = \App\User::where('email',$request->email)->first();            
            if($user){
                $token = $user->email_token;
            }else{
                $token = "EMPTY";
            }
            $checkOTP = \App\Forgot2fa::where('token',$token)->where('otp',$request->otp)->whereNull('g2fa_key')->first();
            if($checkOTP){
                if($checkOTP->otp != $request->otp){
                    if($request->ajax())
                        return response()->json(['error' => ['msg' => 'OTP is Invalid']],400);
                    else
                        return back()->with('flash_error','OTP is Invalid.');
                }
                $currentTime = new \DateTime;
                $diff = $checkOTP->created_at->diffInMinutes($currentTime);
                if($diff > 3){
                    $checkOTP->g2fa_key = "EXPIRED";
                    $checkOTP->save();
                    if($request->ajax())
                        return response()->json(['error' => ['msg' => 'OTP is Expired.']],400);
                    else
                        return back()->with('flash_error','OTP is Expired.');
                }
                $user = \App\User::where('email_token',$token)->first();
                $user->g2f_status = '0';
                $checkOTP->g2fa_key = $user->google2fa_secret;
                $checkOTP->verified_at = new \DateTime;
                $checkOTP->save();
                $user->google2fa_secret = null;
                $user->save();

                if($request->ajax())
                return response()->json([
                    'statusCode' => 200,
                    'success' => true,
                    'message' => 'Your 2FA disabled successfully',
                    ]); 
                    // return response()->json(['success' => ['msg' => 'Your 2FA disabled successfully']],200);
                else
                    return redirect('/login')->with('flash_success','Your 2FA disabled successfully');
            }else{
                if($request->ajax())
                    return response()->json([
                        'statusCode' => 420,
                        'success' => false,
                        'message' => 'OTP is Invalid',
                    ]);
                    // return response()->json(['error' => ['msg' => 'OTP is Invalid']],400);
                else
                    return back()->with('flash_error','Invalid OTP');
            }

        }catch(Exception $e){
            return response()->json([
                'statusCode' => 500,
                'success' => false,
                'message' => 'Something Went Wrong',
            ]);
            // return response()->json(['error' => ['msg' => 'Something Went Wrong']],500);
        }
    }
    public function validat2FA(Request $request){
            $user = \App\User::find(session('2fa:user:id'));
            if($user){
                $secret = Crypt::decryptString($user->google2fa_secret);
                $temp=Google2FA::verifyKey($secret,$request->otp);
                if($temp == true){
                    return ['status' => 1, 'msg' => 'verified'];
                }else{
                    return ['status' => 0, 'msg' => 'Invalid OTP'];
                }
            }
            return ['status' => 0, 'msg' => 'User does not enabled 2FA.'];
    }

}
