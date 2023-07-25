<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Comment;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::table('comments')->insert([
        //     'content' => Str::random(50),
        // ]);
        // for ($i = 0; $i < 10; $i++) {
            $user = User::find(1);
            $post = Post::find(1);

            Comment::create([
                'content' => fake()->name(),
            ]);
            $user->comments()->save($comment);
            $post->comments()->save($comment);

        // }

        Comment::factory()
        ->count(10)
        ->create();
    }
}
