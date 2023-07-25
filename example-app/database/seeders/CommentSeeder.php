<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\User;
use App\Models\Post;


class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::find(1);
        $post = Post::find(1);

        Comment::create([
            'content' => fake()->sentence(),
            'user_id' => 1,
            'post_id' => 1,
        ]);
            
    }
}
