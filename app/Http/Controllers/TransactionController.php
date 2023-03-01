<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTraits;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Validation\Rules\Enum;

class TransactionController extends Controller
{
    use ResponseTraits;

    //List Transaction
    public function list()
    {
        $data = Transaction::all();
        return $this->sendSuccessResponse(true,"Data Get Successfully",$data);
    }

    //Add Transaction Data
    public function create(Request $request)
    {
        $validation = validator($request->all(),[
            'type'      =>  ['required','alpha','in:income,expense'],
            'category'  =>  ['required','alpha_dash','max:30'],
            'amount'    =>  ['required','numeric'],
            'user_id'   =>  ['required','numeric','exists:account_users,id'],
            'account_id'=>  ['required','numeric','exists:accounts,id'],
        ]);

        if($validation->fails())
        {
            return $this->sendErrorResponse($validation);
        }

        Transaction::create($request->only('type','category','amount','user_id','account_id'));
        $msg = "";
        if($request->type == 'income')
            $msg = "money received successfully";
        else
            $msg = "money Send successfully";
        return $this->sendSuccessResponse(true,$msg,$request->amount." Rs.");

    }

    public function get(Transaction $transaction)
    {
        return $this->sendSuccessResponse(true,"data get Successfully",$transaction);
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validation = validator($request->all(),[
            'type'      =>  ['required','alpha','in:income,expense'],
            'category'  =>  ['required','alpha_dash'],
            'amount'    =>  ['required','numeric'],
            'user_id'   =>  ['required','numeric','exists:account_users,id'],
            'account_id'=>  ['required','numeric','exists:accounts,id'],
        ]);

        if($validation->fails())
        {
            return $this->sendErrorResponse($validation);
        }

        $transaction->update($request->all());
        return $this->sendSuccessResponse(true,"Your Transaction Updated Sucessfully.");
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return $this->sendSuccessResponse(true,"transction has been Deleted Sucessfully.");
    }
}
