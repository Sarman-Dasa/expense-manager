<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_name',
        'account_number',
        'is_default',
        'user_id'
    ];

    //Account and User Relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //Account and AccountUser Relationship
    public function accountUsers()
    {
        return $this->hasMany(AccountUser::class,'account_id','id');
    }

    //Account and Transanction Relationship
    public function transactions()
    {
        return $this->hasMany(Transaction::class,'account_id','id')->orderBy('created_at','DESC');
    }
}
