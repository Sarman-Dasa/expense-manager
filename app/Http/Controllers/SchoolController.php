<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTraits;
use App\Models\School;
use Dotenv\Validator;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    use ResponseTraits;
    public function list()
    {
        $id = auth()->user()->id;
        $schools = School::where('user_id','=',$id)->get();
        return $this->sendSuccessResponse(true,'School(s) data get successfully.',$schools);
    }

    public function create(Request $request)
    {
       
        $validation = validator($request->all(),[
            'school_name'       =>  'required|max:200',
            'email'             =>  'required|email|unique:users,email',
            'phone'             =>  'required|regex:/[6-9]{1}[0-9]{9}/|unique:users,phone',
            'city'              =>  'required|max:100',
            'address'           =>  'required|max:200',
            'website_link'      =>  'url',
        ]);

        if($validation->fails())
        {
            return $this->sendErrorResponse($validation);
        }

       $school = School::create($request->only(['school_name','email','phone','city','address','website_link'])
        +[
            'user_id'   =>  auth()->user()->id,
        ]);
        return $this->sendSuccessResponse(true,'School Add Successfully',$school);
    }
}
