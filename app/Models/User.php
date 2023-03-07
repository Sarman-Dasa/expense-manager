<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'is_onborded',
        'password',
        'role',
        'email_verify_token',
        'email_verified_at',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_default'        => 'boolean',
    ];
    
     //Account and User Relationship
    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
    
    //AccountUser and user Relationship
    public function accountUsers()
    {
        return $this->hasManyThrough(AccountUser::class,Account::class,'user_id','account_id');
    }
    //Get All Transaction of User
    public function transactions()
    {
        return $this->hasManyThrough(Transaction::class,Account::class,'user_id','account_id');
    }

    //User School

    public function schools()
    {
        return $this->hasMany(School::class,'user_id','id');
    }

    //User and Teacher Relationship
    public function teachers()
    {
        return $this->hasMany(Teacher::class,'created_by','id');
    }

    //Teacher Profile
    public function teacherProfile()
    {
        return $this->hasOne(Teacher::class,'user_id','id');
    }

    //Student profile
    public function studentProfile()
     {
         return $this->hasOne(User::class,'user_id','id');
     }
}
