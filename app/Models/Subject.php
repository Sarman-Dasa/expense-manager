<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_code',
        'subject_name',
    ];

    //Subject and Student relation
    public function students()
    {
        return $this->morphedByMany(Student::class,'courseable');
    }

    //Subject and Teacher relation
    public function teachers()
    {
        return $this->morphedByMany(Teacher::class,'courseable');
    }
}
