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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use ResponseTraits;
    public function register(Request $request)
    {
        $validation = validator($request->all(),[
            'first_name'        =>  'required|alpha',
            'last_name'         =>  'required|alpha',
            'email'             =>  'required|email|unique:users',
            'phone'             =>  'required|digits:10|unique:users',
            'password'          =>  ['required',Password::min(8),'confirmed']
        ]);

        if($validation->fails())
        {
            return $this->sendErrorResponse($validation);
        }
        
        $request['password'] = Hash::make($request->password);
        $request['email_verify_token'] = Str::random(64);
        $user = User::create($request->all());
        
        $account = Account::create([
            'account_name'      =>  $request['account_name'],
            'account_number'    =>  $request['account_number'],
            'is_default'        =>  true,
            'email'             =>  $request->email,
            'user_id'           =>  $user->id, 
        ]);
        //Mail::to($user->email)->send(new SendWelcomeMail($user));
        $user->notify(new sendVerifyAcccount($user));
        return $this->sendSuccessResponse(true,$request->account_name ." Your accound has been create Successfully.");
    }

    public function accountVerify($token)
    {
        $user = User::where('email_verify_token','=',$token);
        $user->update([
            'is_onborded'           => true,
            'email_verified_at'     => now(),
            'email_verify_token'    => "",    
        ]);
        return response("your Account Verify Successfull");
    }

    public function login(Request $request)
    {
        $validation = validator($request->all(),[
            'email'     =>  ['required','email','exists:users,email'],
            'password'  =>  ['required'],
        ]);

        if($validation->fails())
        {
            return $this->sendErrorResponse($validation);
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password,'is_onborded'=>1]))
        {
            $user = User::where('email', $request->email)->first();
            $token = $user->createToken("API TOKEN")->plainTextToken;
            return $this->sendSuccessResponse(true,'Successfully loggon as '.Auth::user()->first_name,$token);
        }
        else
        {
            return $this->sendFailureResponse("Invalid password!!!");
        }
    }

    public function sendMailForForgotPassword(Request $request)
    {
        $validation = validator($request->all(),[
            'email'     => ['required','email']
        ]);

        if($validation->fails())
        {
            return $this->sendErrorResponse($validation);
        }

        $email = $request->email;
        $user = User::where('email',$email)->first();
        if($user)
        {
            $token = Str::random(64);
            $data1 = PasswordReset::updateOrCreate(
            ['email'=>$email],
            [
                'email'=>$email,
                'token'=>$token,
                'created_at'=>now()
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

    public function resetPassword(Request $request)
    {
        $validation = validator($request->all(),[
            'email'         =>  ['required','email','exists:password_resets,email'],
            'password'      =>  ['required',Password::min(8),'confirmed'],
            'token'         =>  ['required','exists:password_resets,token'],
        ]);

        if($validation->fails())
        {
            return $this->sendErrorResponse($validation);
        }

        $check = PasswordReset::where('email',$request->email)->where('token',$request->token)->get();
        if(count($check) > 0)
        {
            $user = User::where('email',$request->email);
            $user->update([
                'password' => Hash::make($request->password)
            ]);
            return $this->sendSuccessResponse(true,'password reset successfully.');
        }
        else{
            return $this->sendFailureResponse("This password reset token is invalid");
        }
    }

    public function changePassword(Request $request){
        
        $validation = validator($request->all(),[
            'current_password'  =>  ['required','current_password'],
            'password'          =>  ['required',Password::min(8),'confirmed'],
        ]);

        if($validation->fails())
        {
            return $this->sendErrorResponse($validation);
        }

        $id = Auth::user()->id;
        $user = User::find($id);
        $user->update([
            'password'  => Hash::make($request->password),
        ]);
        return $this->sendSuccessResponse(true,'Password Chnage Successfully');

    }
}
