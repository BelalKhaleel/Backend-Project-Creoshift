<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class CommentController extends Controller
{
    /**
     * Display a listing of the comments.
     */
    public function index(Request $request)
    {
        $comments = QueryBuilder::for(Comment::class)
            ->allowedFilters(['content', 'user_id', 'post_id', AllowedFilter::exact('id')])
            ->defaultSort('-updated_at')
            ->allowedSorts(['content', 'user_id', 'post_id', '-updated_at'])
            ->paginate($request->input('per_page', 100))
            ->appends($request->query());

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
        if ($comment->trashed()) {
            return response(['success' => false, 'message' => 'Cannot update a soft-deleted comment.']);
        }

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
        if ($comment->trashed()) {
            return response(['success' => false, 'message' => 'This comment has already been soft deleted.']);
        }
        $comment->delete();
        return response(['data' => $comment], Response::HTTP_NO_CONTENT);
    }
}
