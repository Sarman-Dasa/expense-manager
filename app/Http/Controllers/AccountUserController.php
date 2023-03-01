<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTraits;
use App\Models\Account;
use App\Models\AccountUser;
use Illuminate\Http\Request;

class AccountUserController extends Controller
{
    use ResponseTraits;

    //List All Account User//
    public function list()
    {
        $data = AccountUser::all();
        return $this->sendSuccessResponse(true,"Data Get Successfully",$data);
    }

    //Add Account User//
    public function create(Request $request)
    {
       
        $validation = validator($request->all(),[
            'first_name'        =>  ['required','alpha','max:30','min:3'],
            'last_name'         =>  ['required','alpha','max:30','min:3'],
            'email'             =>  ['required','email','unique:account_users,email'],
            'account_id'        =>  ['required','numeric','exists:accounts,id'],
        ]);

        if($validation->fails())
        {
            return $this->sendErrorResponse($validation);
        }
        
        AccountUser::create($request->only(['first_name','last_name','email','account_id']));
        return $this->sendSuccessResponse(true,"Account user Created Sucessfully");
    }

    //Get Account User//
    public function get($accountUser)
    {
        $data = AccountUser::find($accountUser);
        if($data)
            return $this->sendSuccessResponse(true,"data get Successfully",$data);
        return $this->sendFailureResponse('Data Not Found!!');
    }

    //Update Account User//
    public function update(Request $request, AccountUser $accountUser)
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

        //$accountUser->update($request->all());
        $accountUser->update($request->only(['first_name','last_name','email']));
        return $this->sendSuccessResponse(true,"Your Account Updated Sucessfully.");
    }

    //Delete Account User//
    public function destroy($accountUser)
    {
        $data = AccountUser::findor ($accountUser);
        if($data){
            $data->delete();
            return $this->sendSuccessResponse(true,"Your Account has been Deleted Sucessfully.");
        }
        return $this->sendFailureResponse('Data Not Found!!');
       
    }
}
