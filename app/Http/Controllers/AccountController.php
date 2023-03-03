<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTraits;
use App\Models\Account;
use App\Models\User;
use Dflydev\DotAccessData\Data;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    use ResponseTraits;

    //List All Account//
    public function list(){
        $accountList = Account::all();
        return $this->sendSuccessResponse(true,"Data Get Successfully",$accountList);
    }
    
    //Add Account Data
    public function create(Request $request){
        $validation = validator($request->all(),[
            'account_name'      => ['required' ,'max:100'],      
            'account_number'    => ['required' ,'numeric' ,'min_digits:10' ,'max_digits:12' ,'unique:accounts,account_number'],
        ]);

        if($validation->fails())
        {
            return $this->sendErrorResponse($validation);
        } 
        
        $userId =   auth::user()->id;
        $account = Account::create($request->only(['account_name' ,'account_number'])
        +[
            'user_id'   =>  $userId,
        ]);
        return $this->sendSuccessResponse(true,"Your Account Created Suucessfully.",$account);
    }

    //Update Account Data//
    public function update(Request $request,$id){
        $validation = validator($request->all(),[
            'account_name'      => ['required' ,'max:100'],      
            'account_number'    => ['required' ,'numeric' ,'min_digits:10' ,'max_digits:12'],
        ]);
       
        if($validation->fails())
        {
            return $this->sendErrorResponse($validation);
        } 
        $accountData = Account::find($id);
        if($accountData)
        {
            $accountData->update($request->only(['account_name' ,'account_number']));
            return $this->sendSuccessResponse(true ,"Your Account Updated Suucessfully." ,$id);
        }
        else
        {
            return $this->sendFailureResponse("User Not Found!!!");
        }
    }
    
    //Get Account Data//
    public function get($id){
        try{
            $account = Account::findOrFail($id);
            $account->load(['accountUsers','transactions']);
            return $this->sendSuccessResponse(true,"data get Successfully",$account);
        }catch(Exception $ex){
            return $this->sendExecptionMessage($ex);
        }
    }

    //Delete Account Data//
    public function destroy($id){
        try{
            $accountData = Account::findOrFail($id);
            $accountData->delete();
            return $this->sendSuccessResponse(true,"Your Account has been Deleted Sucessfully.");
        }catch(Exception $ex){
            return $this->sendFailureResponse('Data Not Found!!!');
        }
    }
    
    //get all Transaction 
    public function listOfTransactions($id){
        try{
            $listOfTransactions = Account::with('transactions')->latest()->findOrFail($id);
            return $this->sendSuccessResponse(true,"Account Transaction(s) get Successfully",$listOfTransactions);
        }catch(Exception $ex){
            return $this->sendExecptionMessage($ex);
        }
    }

    //get All user Account related to account
    public function listOfAccountUser($id){
        $listOfAccountUser  = Account::with('accountUsers')->find($id);
        dd($listOfAccountUser);
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
