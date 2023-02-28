<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTraits;
use App\Mail\SendWelcomeMail;
use App\Models\Account;
use App\Models\User;
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
}
