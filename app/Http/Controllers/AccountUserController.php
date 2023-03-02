<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTraits;
use App\Models\Account;
use App\Models\AccountUser;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class AccountUserController extends Controller
{
    use ResponseTraits;

    //List All Account User//
    public function list()
    {
        $AccountUserList = AccountUser::all();
        return $this->sendSuccessResponse(true,"Data Get Successfully",$AccountUserList);
    }

    //Add Account User//
    public function create(Request $request)
    {
        $validation = validator($request->all(),[
            'email'             =>  ['required','email','exists:users,email','unique:account_users,email'],
            'account_id'        =>  ['required','numeric','exists:accounts,id'],
        ]);

        if($validation->fails())
        {
            return $this->sendErrorResponse($validation);
        }
        
        $user = User::where('email',$request->email)->first();
        if($user){
            AccountUser::create($user->only(['first_name','last_name'])
            +$request->only(['email','account_id']));
            return $this->sendSuccessResponse(true,"Account user Created Sucessfully");
        }
        else
        {
            return $this->sendFailureResponse('User Not Found!!!');
        }
    }

    //Get Account User//
    public function get($accountUser)
    {
        try{
            $AccountUserData = AccountUser::findOrFail($accountUser);
            return $this->sendSuccessResponse(true,"data get Successfully",$AccountUserData);
        }catch(Exception $ex){
            return $this->sendFailureResponse('Data Not Found!!!');
        }
    }

    //Update Account User//
    public function update(Request $request, $accountUser)
    {
        $validation = validator($request->all(),[
            'first_name'        =>  ['required','alpha','max:30','min:3'],
            'last_name'         =>  ['required','alpha','max:30','min:3'],
            'email'             =>  ['required','email'],
        ]);

        if($validation->fails())
        {
            return $this->sendErrorResponse($validation);
        }
        $AccountUserData = AccountUser::find($accountUser);
        if($AccountUserData){
            $AccountUserData->update($request->only(['first_name','last_name','email']));
            return $this->sendSuccessResponse(true,"Your Account Updated Sucessfully.");
        }
        else{
            return $this->sendFailureResponse('Account User Not Found!!!');
        }
    }

    //Delete Account User//
    public function destroy($accountUser)
    {
        try{
            $AccountUserData = AccountUser::findOrFail($accountUser);
            $AccountUserData->delete();
            return $this->sendSuccessResponse(true,"Your Account has been Deleted Sucessfully.");
        }catch(Exception $ex){
            return $this->sendFailureResponse('Data Not Found!!!');
        }
    }
}
