<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Post;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content'=>fake()->sentence(),
        ];
    }

    // Define a state that associates a random user_id to the comment
    public function withUser()
    {
        return $this->state(function (array $attributes) {
            return [
                'user_id' => User::inRandomOrder()->first()->id,
            ];
        });
    }

    // Define a state that associates a random post_id to the comment
    public function withPost()
    {
        return $this->state(function (array $attributes) {
            return [
                'post_id' => Post::inRandomOrder()->first()->id,
            ];
        });
    }
}
