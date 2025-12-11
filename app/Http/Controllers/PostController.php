<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('activity.log')->only(['store', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Cache::remember('posts.index', 3600, function () {
            return Post::with(['user'])
                ->withCount('comments')
                ->latest()
                ->paginate(10);
        });

        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post = Auth::user()->posts()->create($validated);

        // Clear cache
        Cache::forget('posts.index');
        Cache::forget('posts.show.' . $post->id);

        return redirect()->route('posts.show', $post)
            ->with('success', 'Post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post = Cache::remember("posts.show.{$post->id}", 1800, function () use ($post) {
            return $post->load(['user', 'comments.user']);
        });

        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        if (!$post->canEdit(Auth::user())) {
            abort(403, 'Unauthorized action.');
        }

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        if (!$post->canEdit(Auth::user())) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post->update($validated);

        // Clear cache
        Cache::forget('posts.index');
        Cache::forget('posts.show.' . $post->id);

        return redirect()->route('posts.show', $post)
            ->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if (!$post->canEdit(Auth::user())) {
            abort(403, 'Unauthorized action.');
        }

        $post->delete();

        // Clear cache
        Cache::forget('posts.index');
        Cache::forget('posts.show.' . $post->id);

        return redirect()->route('posts.index')
            ->with('success', 'Post deleted successfully.');
    }
}
