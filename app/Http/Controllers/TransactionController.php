<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTraits;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Exception;
use Illuminate\Validation\Rules\Enum;

class TransactionController extends Controller
{
    use ResponseTraits;

    //List Transaction
    public function list(){
        $transactionList = Transaction::all();
        return $this->sendSuccessResponse(true,"Data Get Successfully",$transactionList);
    }

    //Add Transaction Data
    public function create(Request $request){
        $validation = validator($request->all(),[
            'type'              =>  ['required' ,'alpha' ,'in:income,expense'],
            'category'          =>  ['required' ,'alpha_dash' ,'max:30'],
            'amount'            =>  ['required' ,'numeric'],
            'account_user_id'   =>  ['required' ,'numeric' ,'exists:account_users,id'],
            'account_id'        =>  ['required' ,'numeric' ,'exists:accounts,id'],
        ],[
            'account_user_id.exists'   =>  'this Account User id Does not exists!!!',
            'account_id.exists'        =>  'this Account id Does not exists!!!',
        ]);

        if($validation->fails())
        {
            return $this->sendErrorResponse($validation);
        }

        Transaction::create($request->only('type' ,'category' ,'amount' ,'account_user_id' ,'account_id'));
        $msg = "";
        if($request->type == 'income')
            $msg = "money received successfully";
        else
            $msg = "money Send successfully";
        return $this->sendSuccessResponse(true,$msg,$request->amount." Rs.");
    }

    //update Transaction 
    public function update(Request $request, Transaction $transaction){
        $validation = validator($request->all(),[
            'type'              =>  ['required' ,'alpha' ,'in:income,expense'],
            'category'          =>  ['required' ,'alpha_dash'],
            'amount'            =>  ['required' ,'numeric'],
            'account_user_id'   =>  ['required' ,'numeric' ,'exists:account_users,id'],
            'account_id'        =>  ['required' ,'numeric' ,'exists:accounts,id'],
        ],[
            'account_user_id'   =>  'this Account User id Does not exists!!!',
            'account_id'        =>  'this Account id Does not exists!!!',
        ]);

        if($validation->fails())
        {
            return $this->sendErrorResponse($validation);
        }

        $transaction->update($request->only('type' ,'category' ,'amount' ,'account_user_id' ,'account_id'));
        return $this->sendSuccessResponse(true,"Your Transaction Updated Sucessfully.");
    }

    //get transaction
    public function get($transaction){
        try{
            $transaction = Transaction::with(['accountUser','account','user'])->findOrFail($transaction);
            return $this->sendSuccessResponse(true,"transction Data get Successfully",$transaction);
        }catch(Exception $ex){
            return $this->sendExecptionMessage($ex);
            return $this->sendFailureResponse('Data Not Found!!!');
        }  
        return $this->sendSuccessResponse(true ,"data get Successfully" ,$transaction);
    }
    
   //delete transaction
    public function destroy($transaction){
        try{
            $transactionData = Transaction::findOrFail($transaction);
            $transactionData->delete();
            return $this->sendSuccessResponse(true,"transction has been Deleted Sucessfully.");
        }catch(Exception $ex){
            //return $this->sendExecptionMessage($ex);
            return $this->sendFailureResponse('Data Not Found!!!');
        }  
    }

    //get AccountUser data 
    public function getAccountUser($id){
        try{
            $accountUserData = Transaction::with('accountUser')->findOrFail($id);
            return $this->sendSuccessResponse(true,'Account User data get successfully',$accountUserData);
        }catch(Exception $ex){
            return $this->sendExecptionMessage($ex);
        }
    }

    //get Account
    public function getAccount($id){
        try{
            $accountData = Transaction::with('account')->findOrFail($id);
            return $this->sendSuccessResponse(true,'Account Data Get Successfullly',$accountData);
        }catch(Exception $ex){
            return $this->sendExecptionMessage($ex);
        }
    }
}
