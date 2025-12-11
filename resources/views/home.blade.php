@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">My Dashboard</h1>
        
        <div class="mb-4">
            <a href="{{ route('posts.create') }}" class="btn btn-primary">Create New Post</a>
        </div>

        <h2 class="mb-3">My Posts</h2>
        
        @forelse($posts as $post)
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <a href="{{ route('posts.show', $post) }}" class="text-decoration-none">
                            {{ $post->title }}
                        </a>
                    </h5>
                    <p class="card-text">{{ Str::limit($post->content, 200) }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            Created on {{ $post->created_at->format('M d, Y') }}
                        </small>
                        <div>
                            <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form method="POST" action="{{ route('posts.destroy', $post) }}" 
                                  style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info">
                You haven't created any posts yet. <a href="{{ route('posts.create') }}">Create your first post</a>!
            </div>
        @endforelse

        <div class="d-flex justify-content-center">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection
