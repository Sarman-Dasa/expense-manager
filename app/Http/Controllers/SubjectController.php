<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTraits;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    use ResponseTraits;

    //list
    public function list()
    {
        $subjects = Subject::all();
        return $this->sendSuccessResponse(true,'Subject(s) Data get Successfull',$subjects);
    }

    //create
    public function create(Request $request)
    {
       $validation = validator($request->all(),[
            'subject_code'      =>  'required|numeric|min_digits:4|max_digits:10|unique:subjects,subject_code',
            'subject_name'      =>  'required|alpha_dash|min:1|max:100|unique:subjects,subject_name',
       ]);

       if($validation->fails())
       {
           return $this->sendErrorResponse($validation);
       }

       $subject = Subject::create($request->only(['subject_code','subject_name']));
       return $this->sendSuccessResponse(true,'Subject added successfully',$subject);
    }

    //update
    public function update(Request $request ,$id)
    {
        $validation = validator($request->all(),[
            'subject_code'      =>  'required|numeric|min_digits:4|max_digits:10',
            'subject_name'      =>  'required|alpha_dash|min:1|max:100',
       ]);

       if($validation->fails())
       {
           return $this->sendErrorResponse($validation);
       }

       $subject = Subject::findOrFail($id)->update($request->only(['subject_code','subject_name']));
       return $this->sendSuccessResponse(true,'Subject Updated successfully');
    }

    //get
    public function get($id)
    {
        $subject = Subject::findOrFail($id);
        return $this->sendSuccessResponse(true,'Subject get successfully',$subject);
    }

    //delete
    public function destroy($id)
    {
        Subject::findOrFail($id)->delete();
        return $this->sendSuccessResponse(true,'Subject Deleted successfully');
    }
}
