<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Post;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::table('posts')->insert([
        //     'title' => Str::random(10),
        //     'content' => Str::random(50),
        // ]);

        // for ($i = 0; $i < 10; $i++) {
            Post::create([
                'title' => fake()->sentence(),
                'content' => fake()->paragraph(),
                'user_id'=>1,
            ]);
        // }
        // Post::factory()
        // ->count(10)
        // ->hasComments(1)
        // ->create();
    }
}
