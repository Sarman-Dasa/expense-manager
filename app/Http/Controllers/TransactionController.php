<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTraits;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Validation\Rules\Enum;

class TransactionController extends Controller
{
    use ResponseTraits;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Transaction::all();
        return $this->sendSuccessResponse(true,"Data Get Successfully",$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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

        Transaction::create($request->all());
        $msg = "";
        if($request->type == 'income')
            $msg = "money received successfully";
        else
            $msg = "money Send successfully";
        return $this->sendSuccessResponse(true,$msg,$request->amount." Rs.");

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transction)
    {
        return $this->sendSuccessResponse(true,"data get Successfully",$transction);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transction)
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

        $transction->update($request->all());
        return $this->sendSuccessResponse(true,"Your Transaction Updated Sucessfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transction)
    {
        $transction->delete();
        return $this->sendSuccessResponse(true,"transction has been Deleted Sucessfully.");
    }
}
