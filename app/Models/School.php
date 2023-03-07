<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_name',
        'email',
        'phone',
        'city',
        'address',
        'website_link',
        'user_id',
    ];

    //school User relation 
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    
    //school teacher relation 
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class,'school_teachers');
    }

    //school and student relation
    public function students()
    {
        return $this->hasMany(Student::class,'school_id','id');
    }
}
