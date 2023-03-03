<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTraits;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use ResponseTraits;

    public function get($id)
    {
        $user = User::with(['accounts','accountUsers','transactions'])->findOrFail($id);
        return $this->sendSuccessResponse(true,'User Data Get successfully',$user);
    }

    public function changePassword(Request $request){
        
        $validation = validator($request->all(),[
            'current_password'  =>  ['required' ,'current_password'],
            'password'          =>  ['required' ,'min:8' ,'confirmed'],
        ],[
            'current_password.' =>  'The current password is incorrect.'
        ]);

        if($validation->fails())
        {
            return $this->sendErrorResponse($validation);
        }

        //$id = Auth::user()->id;
        //$user = User::find($id);
        $user = auth()->user();
        $user->update([
            'password'  => Hash::make($request->password),
        ]);
        return $this->sendSuccessResponse(true,'Password change Successfully');
    }

    public function userProfile($id){
        $userProfile   =   User::with('accounts')->find($id);
       if($userProfile){
            return $this->sendSuccessResponse(true,"User Profile Data",$userProfile); 
       }
       else{
            return $this->sendFailureResponse("User Not Found!!!");
       }
    }

    public function listOfTransactions($id){
        try{
            $listOfTransactions = User::with('transactions')->findOrFail($id);
            return $this->sendSuccessResponse(true,"Account Transaction(s) get Successfully",$listOfTransactions);
        }catch(Exception $ex){
            return $this->sendExecptionMessage($ex);
        }
    }

    public function listOfAccount($id){
        $listOfAccount  = User::with('accounts')->find($id);
        if($listOfAccount)
        {
            return $this->sendSuccessResponse(true,"User Account Get Successfully",$listOfAccount);
        }
        else
        {
            return $this->sendFailureResponse("User Not Found!!!");
        }
    }

    public function listOfAccountUser($id){
        $listOfAccountUser  = User::with('accountUsers')->find($id);
        if($listOfAccountUser)
        {
            return $this->sendSuccessResponse(true,"User Account Get Successfully",$listOfAccountUser);
        }
        else
        {
            return $this->sendFailureResponse("User Not Found!!!");
        }
    }

}
