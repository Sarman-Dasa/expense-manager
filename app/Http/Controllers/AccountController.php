<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTraits;
use App\Models\Account;
use App\Models\User;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    use ResponseTraits;

    //List All Account//
    public function list()
    {
        $data = Account::all();
        return $this->sendSuccessResponse(true,"Data Get Successfully",$data);
    }
    
    //Add Account Data
    public function create(Request $request)
    {
        $validation = validator($request->all(),[
            'account_name'      => ['required'],      
            'account_number'    => ['required' ,'numeric' ,'digits:12' ,'unique:accounts'],
        ]);

        if($validation->fails())
        {
            return $this->sendErrorResponse($validation);
        } 

        $request['user_id'] = Auth::user()->id;
        $account = Account::create($request->only(['account_name' ,'account_number' ,'user_id']));
        return $this->sendSuccessResponse(true," Your Account Created Suucessfully.",$account);
    }

    //Update Account Data//
    public function update(Request $request,Account $id)
    {
        $validation = validator($request->all(),[
            'account_name'      => ['required'],      
            'account_number'    => ['required' ,'numeric' ,'digits:12'],
        ]);
       
        if($validation->fails())
        {
            return $this->sendErrorResponse($validation);
        } 

        $id->update($request->all());
        return $this->sendSuccessResponse(true ,"Your Account Updated Suucessfully." ,$id);
    }

    //Delete Account Data//
    public function destroy($id)
    {
        $data = Account::find($id);
        if($data){
            $data->delete();
            return $this->sendSuccessResponse(true ,"Your Account has been Deleted Suucessfully.");
        }
        return $this->sendFailureResponse('Data Not Found!!!');
    }

    //Get Account Data//
    public function get(Account $id)
    {
        return $this->sendSuccessResponse(true ,"data get Successfully" ,$id);
    }


}
