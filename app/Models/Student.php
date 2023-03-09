<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable =[
        'city',
        'birth_date',
        'join_date',
        'school_id',
        'user_id',
        'standard',
        'created_by',
        'updated_by',
    ];

    //studetn teacher relation
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class,'teacher_students');
    }

    //Student Profile
     public function user()
     {
         return $this->belongsTo(User::class,'user_id');
     }


     //Student and school relation 
     public function school(){
        return $this->belongsTo(School::class,'school_id','id');
     }

     //Student and Subject relation
     public function subjects()
     {
        return $this->morphToMany(Subject::class,'courseable');
     }
}
