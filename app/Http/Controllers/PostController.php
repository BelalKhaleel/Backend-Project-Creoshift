<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Exports\PostsExport;
use App\Imports\PostsImport;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class PostController extends Controller
{
    /**
     * Display a listing of the posts.
     */
    public function index(Request $request)
    {
        $cacheKey = 'posts';
        $seconds = 10;

        Log::info('Fetching data from the database with cache expiration of ' . $seconds . ' seconds');
        $posts = Cache::remember($cacheKey, $seconds, function () use ($request) {
            return QueryBuilder::for(Post::class)
                ->with(['comments'])
                ->allowedFilters(['title', 'content', 'user_id', AllowedFilter::exact('id')])
                ->defaultSort('-updated_at')
                ->allowedSorts(['title', 'content', 'user_id', '-updated_at'])
                ->paginate($request->input('per_page', 100))
                ->appends($request->query());
        });

        if (Cache::has($cacheKey)) {
            Log::info('Data fetched from cache');
        } else {
            Log::info('Data generated and cached');
        }
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
        return response(['success' => true, 'data' => $post->load('comments')]);
    }

    /**
     * Update the post in storage.
     */
    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:255',
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

    /**
     * Export posts into an excel sheet.
     */
    public function exportPosts()
    {
        return (new PostsExport)->download('posts.xlsx');
    }

    /**
     * Import uploaded excel sheet into the database.
     */
    public function importPosts(Request $request)
    {
        $file = $request->file('file');

        Excel::import(new PostsImport, $file, null, \Maatwebsite\Excel\Excel::XLSX);

    return redirect('/')->with('success', 'File imported successfully!');
    }
}
