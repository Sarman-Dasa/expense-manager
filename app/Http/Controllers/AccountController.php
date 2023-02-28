<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTraits;
use App\Models\Account;
use App\Models\User;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    use ResponseTraits;

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        $data = Account::all();
        return $this->sendSuccessResponse(true,"Data Get Successfully",$data);
    }

    public function addAccount(Request $request)
    {
        $validation = validator($request->all(),[
            'account_name'      => ['required'],      
            'account_number'    => ['required','numeric','digits:12'],
            'user_id'           => ['required','numeric'],
        ]);

        if($validation->fails())
        {
            return $this->sendErrorResponse($validation);
        } 

        $account = Account::create([
            'account_number' => $request->account_number,
            'account_name'  => $request->account_name,
            'user_id'       => $request->user_id,
        ]);

        // $user = User::where('email','=',$request->email);
        // $account =  Account::create([
        //     'account_name'      =>  $user->account_name,
        //     'account_number'    =>  $user->account_number,
        //     'is_default'        =>  false,
        //     'email'             =>  $request->email,
        //     'user_id'           =>  $user->id, 
        // ]);
        return $this->sendSuccessResponse(true,$request->account_name." Your Account Created Suucessfully.",$account);
    }

    public function updateAccount(Request $request,Account $id)
    {
        $validation = validator($request->all(),[
            'account_name'      => ['required'],      
            'account_number'    => ['required','numeric','digits:12'],
        ]);
       
        if($validation->fails())
        {
            return $this->sendErrorResponse($validation);
        } 

        $id->update($request->all());
        return $this->sendSuccessResponse(true,$request->account_name." Your Account Updated Suucessfully.",$id);
    }

    public function deleteAccount(Account $id)
    {
        $id->delete();
        return $this->sendSuccessResponse(true,$id->account_name." Your Account has been Deleted Suucessfully.");
    }

    public function show(Account $id)
    {
        return $this->sendSuccessResponse(true,"data get Successfully",$id);
    }


}
