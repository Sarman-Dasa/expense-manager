<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTraits;
use App\Mail\SendWelcomeMail;
use App\Models\Account;
use App\Models\PasswordReset;
use App\Models\User;
use App\Notifications\resetPasswordMail;
use App\Notifications\sendVerifyAcccount;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use ResponseTraits;

    public function register(Request $request){
        $validation = validator($request->all(),[
            'first_name'        =>  'required|string|alpha|max:30',
            'last_name'         =>  'required|string|alpha|max:30',
            'email'             =>  'required|email|unique:users,email',
            'phone'             =>  'required|digits:10|unique:users,phone',
            'password'          =>  'required|min:8|confirmed',
            'account_name'      =>  'required|string|max:100',
            'account_number'    =>  'required|min_digits:10|max_digits:12|unique:accounts,account_number',
            'gender'            =>  'required|in:Male,Female',
        ]);

        if($validation->fails())
        {
            return $this->sendErrorResponse($validation);
        }

        $user = User::create($request->only(['first_name' ,'last_name' ,'email' ,'phone'])
        +[
            'password'              =>  Hash::make($request->password),
            'email_verify_token'    =>  Str::random(64),
        ]);
        
        $account = Account::create($request->only(['account_name' ,'account_number'])
        +[
            'is_default'    =>  true,
            'user_id'       =>  $user->id
        ]);
        
        Mail::to($user->email)->send(new SendWelcomeMail($user));
        $user->notify(new sendVerifyAcccount($user));
        return $this->sendSuccessResponse(true,"Your accound has been create Successfully.");
    }

    public function accountVerify($token){
        $user = User::where('email_verify_token' ,'=' ,$token)->first();
        if($user){
            $user->update([
                'is_onborded'           =>  true,
                'email_verified_at'     =>  Carbon::now(),
                'email_verify_token'    =>  null,
            ]);
            return response("your Account Verify Successfull");
        }
        else{
            return $this->sendSuccessResponse(true,"Account Already Verifyed");
        }
    }

    public function login(Request $request){
        $validation = validator($request->all() ,[
            'email'     =>  ['required' ,'email' ,'exists:users,email'],
            'password'  =>  ['required'],
        ]);

        if($validation->fails())
        {
            return $this->sendErrorResponse($validation);
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password,'is_onborded'=>1]))
        {
            //$user = User::where('email', $request->email)->first();
            $user = auth()->user();
            $token = $user->createToken("API TOKEN")->plainTextToken;
            $userName = $user->first_name;
            return $this->sendSuccessResponse(true,'Successfully loggon as '.$userName,$token);
        }
        else
        {
            return $this->sendFailureResponse("Invalid password!!!");
        }
    }

    public function sendMailForForgotPassword(Request $request){
        $validation = validator($request->all(),[
            'email'     => ['required','email']
        ]);

        if($validation->fails())
        {
            return $this->sendErrorResponse($validation);
        }

        $user = User::where('email', $request->email)->first();
        if($user)
        {
            $token = Str::random(64);
            $data1 = PasswordReset::updateOrCreate(
            ['email'=> $request->email],
            [
                'email'         => $request->email,
                'token'         => $token,
                'created_at'    => now(),
                'expired_at'    => now()->addDays(2),
            ]);
            $user['token'] = $token;
           $user->notify(new resetPasswordMail($user));
           return $this->sendSuccessResponse(true,'Check your mail box!',$token);
        }
        else
        {
            return $this->sendFailureResponse("Email Address can't match!!! try again");
        }
    }

    public function resetPassword(Request $request){
        $validation = validator($request->all(),[
            'email'         =>  ['required' ,'email' ,'exists:password_resets,email'],
            'password'      =>  ['required' ,'min:8' ,'confirmed'],
            'token'         =>  ['required' ,'exists:password_resets,token'],
        ]);

        if($validation->fails())
        {
            return $this->sendErrorResponse($validation);
        }
        $date = date('Y-m-d');
        //dd(now());
        $user = PasswordReset::where('email',$request->email)->where('token',$request->token)->first();
        $dateCheck = $user->expired_at >=now();
        //dd($dateCheck);
        if($dateCheck)
        {
            $user = User::where('email',$request->email)->first();
           // dd($user);
            $user->update([
                'password' => Hash::make($request->password)
            ]);
            return $this->sendSuccessResponse(true,'password reset successfully.');
        }
        else{
            return $this->sendFailureResponse("This password reset token expired!!!");
        }
    }
}
