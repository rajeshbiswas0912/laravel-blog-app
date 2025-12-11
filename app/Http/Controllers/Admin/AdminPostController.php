<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminPostController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
        $this->middleware('activity.log')->only(['store', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with(['user'])
            ->withTrashed()
            ->latest()
            ->paginate(15);

        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->load(['user', 'comments.user']);
        
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post->update($validated);
        
        // Clear cache
        Cache::forget('posts.index');
        Cache::forget('posts.show.' . $post->id);
        Cache::forget('admin.stats');

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->forceDelete();
        
        // Clear cache
        Cache::forget('posts.index');
        Cache::forget('posts.show.' . $post->id);
        Cache::forget('admin.stats');

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post permanently deleted.');
    }

    /**
     * Restore a soft deleted post
     */
    public function restore($id)
    {
        $post = Post::withTrashed()->findOrFail($id);
        $post->restore();
        
        Cache::forget('posts.index');
        Cache::forget('admin.stats');

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post restored successfully.');
    }
}
