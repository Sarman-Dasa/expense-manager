<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTraits;
use App\Mail\SendWelcomeMail;
use App\Models\School;
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
    public function list()
    {
        $id = auth()->user()->id;
        $teachers = Teacher::with('teacherProfile')->where('created_by','=',$id)->get();
        if(count($teachers)>0)
            return $this->sendSuccessResponse(true,'Teacher(s) data get successfully.',$teachers);
        return $this->sendFailureResponse('No data found!!!');
    }

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
        ],[
            'join_date.date_format' =>  'The join date does not match the format Please enter yyyy-mm-dd(2023-03-03)',
            'school_id.exists'             =>  'The selected school id is does not exists',
        ]); 

        if($validation->fails())
        {
            return $this->sendErrorResponse($validation);
        } 
        
        $user = auth()->user();
        $schoolsId = School::where('user_id','=',$user->id)->pluck('user_id')->toArray();
        $id = School::where('id',$request->school_id)->whereIn('user_id',$schoolsId)->first();

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

            Mail::to($teacheruser->email)->send(new SendWelcomeMail($teacheruser));
            $teacheruser->notify(new sendVerifyAcccount($teacheruser));
            return $this->sendSuccessResponse(true,'Teacher Added Successfully.',$teacheruser);
        }
        return $this->sendFailureResponse('Selected School id is invalid!!!');
     
    }

    public function update(Request $request ,$id)
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
        ],[
            'join_date.date_format' =>  'The join date does not match the format Please enter yyyy-mm-dd(2023-03-03)',
            'school_id.exists'             =>  'The selected school id is does not exists',
        ]); 

        if($validation->fails())
        {
            return $this->sendErrorResponse($validation);
        }

             
        $user = auth()->user();
        $schoolsId = School::where('user_id','=',$user->id)->pluck('user_id')->toArray();
        $id = School::where('id',$request->school_id)->whereIn('user_id',$schoolsId)->first();

        if($id){
            $teacheruser = User::findOrFail($id)->update($request->only(['first_name' ,'last_name' ,'email' ,'phone'])
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
        }
        return $this->sendFailureResponse('Selected School id is invalid!!!');
    }

    public function get()
    {
        $teacher = Teacher::with('teacherProfile','schools','students')->where('user_id','=',auth()->user()->id)->first();
        return $this->sendSuccessResponse(true,'Teacher Profile.',$teacher);
    }

    public function destroy($id)
    {
        $user =  User::where('role','Teacher')->findOrFail($id);
        $user->delete();
        return $this->sendSuccessResponse(true,'Teacher data deleted');
    }
}
