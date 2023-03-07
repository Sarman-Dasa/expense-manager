<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTraits;
use App\Mail\SendWelcomeMail;
use App\Models\School;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Notifications\sendVerifyAcccount;
use Illuminate\Http\Request;
use Illuminate\Http\ResponseTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    use ResponseTraits;

    public function list()
    {
        $id = auth()->user()->id;
        $students = Student::with('studentProfile')->where('created_by','=',$id)->get();
        if(count($students)>0)
            return $this->sendSuccessResponse(true,'Student(s) data get successfully.',$students);
        return $this->sendFailureResponse('No data found!!!');
    }

    public function create(Request $request)
    {
        $date = now()->subYears( 5 + $request->standard);
       
        $validation = validator($request->all(),[
            'first_name'            =>  'required|string|alpha|max:30',
            'last_name'             =>  'required|string|alpha|max:30',
            'email'                 =>  'required|email|unique:users,email',
            'phone'                 =>  'required|digits:10|unique:users,phone',
            'password'              =>  'required|min:8|confirmed',
            'password_confirmation' =>  'required',
            'gender'                =>  'required|in:Male,Female',
            'city'                  =>  'required|max:100',
            'join_date'             =>  'required|date_format:Y-m-d|before_or_equal:'.now(),
            'school_id'             =>  'required|exists:schools,id',
            'standard'              =>  'required|numeric|min:1|max:12',
            'birth_date'            =>  'required|date_format:Y-m-d|before_or_equal:'.$date,
        ],[
            'join_date.date_format'         =>  'The join date does not match the format Please enter yyyy-mm-dd(2023-03-03)',
            'school_id.exists'              =>  'The selected school id is does not exists',
            'birth_date.before_or_equal'    =>  'Your standard does not match with your age!!!',       
        ]); 

        if($validation->fails())
        {
            return $this->sendErrorResponse($validation);
        }

        $user = auth()->user();
        $teacher = Teacher::with('schools')->where('user_id',auth()->user()->id)->first();
        $school_id = $teacher->schools->pluck('id')->toArray();

        if(in_array($request->school_id,$school_id)){
            $studentUser = User::create($request->only(['first_name' ,'last_name' ,'email' ,'phone'])
            +[
                'password'              =>  Hash::make($request->password),
                'email_verify_token'    =>  Str::random(64),
                'role'                  =>  'Student',
            ]);
        
            $student =  Student::create($request->only(['city','join_date','birth_date','standard'])
            +[
                'school_id'     =>  $request->school_id,
                'user_id'       =>  $studentUser->id,
                'created_by'    =>  $user->id,
                'updated_by'    =>  $user->id,
            ]);

            $student->teachers()->attach($teacher->id);
            Mail::to($studentUser->email)->send(new SendWelcomeMail($studentUser));
            $studentUser->notify(new sendVerifyAcccount($studentUser));
            return $this->sendSuccessResponse(true,'Student Added Successfully.',$studentUser);
        } 

        return $this->sendFailureResponse('Selected School id is invalid!!!');
    }

    public function get()
    {
        $id = auth()->user()->id;
        $student = Student::with('studentProfile','teachers','school')->where('user_id',$id)->first();
        return $this->sendSuccessResponse(true,'Student profile',$student);
    }
}
