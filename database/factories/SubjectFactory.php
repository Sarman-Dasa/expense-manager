<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subject>
 */
class SubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'subject_name'  => fake()->randomElement(['Java','Python','Cpp','C','C#','Php','Laravel','Html','Aws']),
            'subject_code'  => rand(1000,9999),
        ];
    }
}
