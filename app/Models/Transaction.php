<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'category',
        'amount',
        'account_user_id',
        'account_id'
    ];

    //Transaction and AccountUser Relationship
    public function accountUser()
    {
        return $this->belongsTo(AccountUser::class);
    }

    //transacation and Account Relationship
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
