<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;

class PostController extends Controller
{
   // Get all posts
public function index()
{
    return response([
        'posts' => Post::orderBy('created_at', 'desc')
            ->with('user:id,name,image') // Memuat data user
            ->withCount('comments', 'likes') // Menghitung jumlah komentar dan likes
            ->with(['likes' => function ($query) {
                // Menyaring likes untuk user yang sedang login
                $query->select('id', 'user_id', 'post_id');
            }])
            ->get()
            ->map(function ($post) {
                // Menambahkan flag is_liked berdasarkan hasil eager loading likes
                $post->is_liked = $post->likes->where('user_id', auth()->user()->id)->isNotEmpty();
                return $post;
            })
    ], 200);
}


    // Get single post
    public function show($id)
    {
        return response([
            'post' => Post::where('id', $id)
                ->withCount('comments', 'likes')
                ->firstOrFail()
        ], 200);
    }

    // Create a post
    public function store(Request $request)
    {
        // Validate fields
        $attrs = $request->validate([
            'body' => 'required|string'
        ]);

        $image = $this->saveImage($request->input('image'), 'posts');

        $post = Post::create([
            'body' => $attrs['body'],
            'user_id' => auth()->user()->id,
            'image' => $image
        ]);


        // For now, skip for post image
        return response([
            'message' => 'Post created.',
            'post' => $post
        ], 201);
    }

    // Update a post
    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response([
                'message' => 'Post not found.'
            ], 404);
        }

        if ($post->user_id != auth()->user()->id) {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }

        // Validate fields
        $attrs = $request->validate([
            'body' => 'required|string'
        ]);

        $post->update([
            'body' => $attrs['body']
        ]);

        // For now, skip for post image
        return response([
            'message' => 'Post updated.',
            'post' => $post
        ], 200);
    }

    // Delete post
    public function destroy($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response([
                'message' => 'Post not found.'
            ], 404);
        }

        if ($post->user_id != auth()->user()->id) {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }

        $post->comments()->delete();
        $post->likes()->delete();
        $post->delete();

        return response([
            'message' => 'Post deleted.'
        ], 200);
    }
}
