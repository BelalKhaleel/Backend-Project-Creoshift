<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\QueryBuilder;

class CommentController extends Controller
{
    /**
     * Display a listing of the comments.
     */
    public function index()
    {
        $comments = Comment::paginate(5);

        $comments = QueryBuilder::for(Comment::class)
            ->allowedFilters(['content', 'user_id', 'post_id'])
            ->get();

        return response(['success' => true, 'data' => $comments]);
    }

    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'content' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id',
        ]);

        $comment = Comment::create($data);

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
            'content' => 'required|string|max:255',
        ]);

        $comment->update($data);

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
