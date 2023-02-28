<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user  = DB::table('users')->inRandomOrder()->first();
        return [
            'account_name'      =>  $user->account_name,
            'account_number'    =>  $user->account_number,
            'email'             =>  $user->email,
            'is_default'        =>  true,
            'user_id'           =>  $user->id,
        ];
    }
}
