<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTraits;
use App\Mail\SendWelcomeMail;
use App\Models\School;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use App\Notifications\sendVerifyAcccount;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class TeacherController extends Controller
{
    use ResponseTraits;

    //list
    public function list()
    {
        $id = auth()->user()->id;
        $teachers = Teacher::with('user')->where('created_by','=',$id)->get();
        if(count($teachers)>0)
            return $this->sendSuccessResponse(true,'Teacher(s) data get successfully.',$teachers);
        return $this->sendFailureResponse('No data found!!!');
    }

    //create
    public function create(Request $request)
    {
        $validation = validator($request->all(),[
            'first_name'            =>  'required|string|alpha|max:30',
            'last_name'             =>  'required|string|alpha|max:30',
            'email'                 =>  'required|email|unique:users,email',
            'phone'                 =>  'required|digits:10|unique:users,phone',
            'password'              =>  'required|min:8|confirmed',
            'password_confirmation'  =>  'required',
            'gender'                =>  'required|in:Male,Female',
            'city'                  =>  'required|max:100',
            'working_days'          =>  'required|numeric|min:1|max:6',
            'join_date'             =>  'required|date_format:Y-m-d|before_or_equal:'.now(),
            'school_id'             =>  'required|exists:schools,id',
            'subject_id'            =>  'required',
        ],[
            'join_date.date_format' =>  'The join date does not match the format Please enter yyyy-mm-dd(2023-03-03)',
            'school_id.exists'             =>  'The selected school id is does not exists',
        ]); 

        if($validation->fails())
        {
            return $this->sendErrorResponse($validation);
        } 
        
        $user   = auth()->user();
        $query  = School::query();

        // $schoolsId = School::where('user_id','=',$user->id)->pluck('user_id')->toArray();
        // $id = School::where('id',$request->school_id)->whereIn('user_id',$schoolsId)->first();

        $schoolsId  = $query->where('user_id','=',$user->id)->pluck('user_id')->toArray();
        $id         = $query->where('id','=',$request->school_id)->whereIn('user_id',$schoolsId)->first('id');

        if($id){
            $teacheruser = User::create($request->only(['first_name' ,'last_name' ,'email' ,'phone'])
            +[
                'password'              =>  Hash::make($request->password),
                'email_verify_token'    =>  Str::random(64),
                'role'                  =>  'Teacher',
            ]);
        
            $teacher =  Teacher::create($request->only(['city','working_days','join_date'])
            +[
                'user_id'       =>  $teacheruser->id,
                'created_by'    =>  $user->id,
                'updated_by'    =>  $user->id,
            ]);

            $teacher->schools()->attach($request->school_id);

            $subject = Subject::pluck('id')->toArray();
            $subjectId = array_intersect($request->subject_id,$subject);
            $teacher->subjects()->attach($subjectId);
            
            Mail::to($teacheruser->email)->send(new SendWelcomeMail($teacheruser));
            $teacheruser->notify(new sendVerifyAcccount($teacheruser));
            return $this->sendSuccessResponse(true,'Teacher Added Successfully.',$teacheruser);
        }
        return $this->sendFailureResponse('Selected School id is invalid!!!');
     
    }

    //update
    public function update(Request $request ,$id)
    {
       
        $validation = validator($request->all(),[
            'first_name'            =>  'required|string|alpha|max:30',
            'last_name'             =>  'required|string|alpha|max:30',
            'email'                 =>  'required|email',
            'phone'                 =>  'required|digits:10',
            'current_password'      =>  'required|current_password',
            'password'              =>  'required|min:8|confirmed',
            'password_confirmation'  =>  'required',
            'gender'                =>  'required|in:Male,Female',
            'city'                  =>  'required|max:100',
            'working_days'          =>  'required|numeric|min:1|max:6',
            'join_date'             =>  'required|date_format:Y-m-d|before_or_equal:'.now(),
            'school_id'             =>  'required|exists:schools,id',
            'subject_id'            =>  'required',
        ],[
            'join_date.date_format'                 =>  'The join date does not match the format Please enter yyyy-mm-dd(2023-03-03)',
            'school_id.exists'                      =>  'The selected school id is does not exists',
            'current_password.current_password'     =>  'The current password does not match with old password!!!'
        ]); 

        if($validation->fails())
        {
            return $this->sendErrorResponse($validation);
        }

             
        $user = auth()->user();
        $schoolsId = School::where('user_id','=',$user->id)->pluck('user_id')->toArray();
        $checkId = School::where('id',$request->school_id)->whereIn('user_id',$schoolsId)->first();

        if($checkId){
            $teacher = Teacher::findOrFail($id);
            $teacher->update($request->only(['city','working_days','join_date']));

            $user = User::findOrFail($teacher->user_id)->update($request->only(['first_name' ,'last_name' ,'email' ,'phone'])
            +[
                'password'              =>  Hash::make($request->password),
            ]);
            $teacher->schools()->sync($request->school_id);

            $subject = Subject::pluck('id')->toArray();
            $subjectId = array_intersect($request->subject_id,$subject);
            $teacher->subjects()->sync($subjectId);

            return $this->sendSuccessResponse(true,'Teacher data Updated successfully.');
        }
        return $this->sendFailureResponse('Selected School id is invalid!!!');
    }

    //get
    public function get()
    {
        $teacher = Teacher::with('user','schools','students','subjects')->where('user_id','=',auth()->user()->id)->first();
        return $this->sendSuccessResponse(true,'Teacher Profile.',$teacher);
    }

    //delete
    public function destroy($id)
    {
        $user =  User::where('role','Teacher')->findOrFail($id);
        $teacher = Teacher::where('user_id',$user->id)->first();
        $teacher->subjects()->detach();
        $user->delete();
        return $this->sendSuccessResponse(true,'Teacher data deleted');
    }
}

