<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable =[
        'city',
        'working_days',
        'join_date',
        'user_id',
        'created_by',
        'updated_by',
    ];

    //teacher Profile
    public function teacherProfile()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    //Teacher school relation
    public function schools()
    {
        return $this->belongsToMany(School::class,'school_teachers');
    }
}
