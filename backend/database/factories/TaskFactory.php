<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $titles = ['Task A', 'Task B', 'Task C', 'Task D'];
        $descriptions = [
            'Random description ABC', 
            'Random description DEF',  
            'Random description GHI',
            'Random description JKL', 
        ];
        return [
            'title' => $this->faker->randomElement($titles),
            'description' => $this->faker->randomElement($descriptions),
            'completed' => $this->faker->boolean,
            'due_date' => $this->faker->optional(0.7)->dateTimeBetween('-2 month', '+3 month'),
        ];
    }
}
