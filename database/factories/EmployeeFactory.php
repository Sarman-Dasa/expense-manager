<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name'              =>  fake()->name(),
            'email'             =>  fake()->unique()->safeEmail(),
            'mobile_number'     =>  fake()->regexify('[6-9]{2}[0-9]{8}'),
            'department_name'   =>  fake()->randomElement(['DEVELOMENT','BA','TESTER','HR','WEB DEVELOPER','SOFTWARE DEVELOPER','ANDROID DEVELOPER']),
            'hiredate'          =>  fake()->date(),
            'city'              =>  fake()->randomElement(['GANDHINAGAR','RAJKOT','SURAT','PORBANDAR','AHMEDABAD','JAMNGAR','JUNAGADH','SOMNATH','DWARKA']),
            'gender'            =>  fake()->randomElement(['Male','Female','Other']),
            'salary'            =>  rand(10000,100000),
        ];
    }
}
