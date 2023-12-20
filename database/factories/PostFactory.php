<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'=>fake()->sentence(),
            'content'=>fake()->paragraph(),
        ];
    }

    // Define a state that associates a random user_id to the post
    public function withUser()
    {
        return $this->state(function (array $attributes) {
            return [
                'user_id' => User::inRandomOrder()->first()->id,
            ];
        });
    }
}
