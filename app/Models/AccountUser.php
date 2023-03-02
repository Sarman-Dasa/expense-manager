<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountUser extends Model
{
    use HasFactory;
    protected $fillable =[
        'email',
        'first_name',
        'last_name',
        'account_id',
    ];

    //Account and AccountUser Relationship
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    //transaction and AccountUser Relationship
    public function transactions()
    {
        return $this->hasMany(Transaction::class,'acount_user_id','id');
    }
}
