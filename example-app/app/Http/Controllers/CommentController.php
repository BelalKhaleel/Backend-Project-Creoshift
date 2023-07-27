<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the comments.
     */
    public function index()
    {
        $comments = Comment::all();
        return response(['success' => true, 'data' => $comments]);
    }

    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'content' => 'required|content,string|max:255',
        ]);

        $comment = Comment::create([
            'content' => $content,
        ]);

        return response(['success' => true, 'data' => $comment]);
    }

    /**
     * Display the specified comment.
     */
    public function show(Comment $comment)
    {
        return response(['success' => true, 'data' => $comment]);
    }

    /**
     * Update the specified comment in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        $data = $request->validate([
            'content' => 'required|content,string|max:255',
        ]);

        $comment = update($data);

        return response(['success' => true, 'data' => $comment]);
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response(['data' => $comment], Response::HTTP_NO_CONTENT);
    }
}
