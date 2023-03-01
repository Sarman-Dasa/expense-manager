<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTraits;
use App\Models\Account;
use App\Models\AccountUser;
use Illuminate\Http\Request;

class AccountUserController extends Controller
{
    use ResponseTraits;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = AccountUser::all();
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
            'first_name'        =>  ['required','alpha'],
            'last_name'         =>  ['required','alpha'],
            'email'             =>  ['required','email'],
            'account_id'        =>  ['required','numeric'],
        ]);

        if($validation->fails())
        {
            return $this->sendErrorResponse($validation);
        }

        AccountUser::create($request->all());
        return $this->sendSuccessResponse(true,"Account user Created Sucessfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(AccountUser $accountUser)
    {
        return $accountUser;
        return $this->sendSuccessResponse(true,"data get Successfully",$accountUser);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AccountUser $accountUser)
    {
        $validation = validator($request->all(),[
            'first_name'        =>  ['required','alpha'],
            'last_name'         =>  ['required','alpha'],
            'email'             =>  ['required','email'],
        ]);

        if($validation->fails())
        {
            return $this->sendErrorResponse($validation);
        }

        $accountUser->update($request->all());
        return $this->sendSuccessResponse(true,"Your Account Updated Sucessfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccountUser $accountUser)
    {
       $accountUser->delete();
       return $this->sendSuccessResponse(true,"Your Account has been Deleted Sucessfully.");
    }
}
