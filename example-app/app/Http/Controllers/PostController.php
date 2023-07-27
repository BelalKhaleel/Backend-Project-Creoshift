<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the posts.
     */
    public function index()
    {
        $posts = Post::all();
       return response(['success' => true, 'data' => $posts]);
    }

    /**
     * Store a newly created post in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ]);

        $post = Post::create($data);

        return response(['success' => true, 'data' => $post]);
    }

    /**
     * Display the post.
     */
    public function show(Post $post)
    {
        return response(['success' => true, 'data' => $post]);
    }

    /**
     * Update the post in storage.
     */
    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string|max:255',
        ]);

        $post->update($data);

        return response(['success' => true, 'data' => $post]);
    }

    /**
     * Remove the post from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return response(['data' => $post], Response::HTTP_NO_CONTENT);
    }
}