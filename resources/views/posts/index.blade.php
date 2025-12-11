@extends('layouts.app')

@section('title', 'All Posts')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">All Posts</h1>
        
        @forelse($posts as $post)
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <a href="{{ route('posts.show', $post) }}" class="text-decoration-none">
                            {{ $post->title }}
                        </a>
                    </h5>
                    <p class="card-text">{{ Str::limit($post->content, 200) }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            By <strong>{{ $post->user->name }}</strong> on 
                            {{ $post->created_at->format('M d, Y') }}
                        </small>
                        <small class="text-muted">
                            {{ $post->comments_count }} comments
                        </small>
                    </div>
                    <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-primary mt-2">
                        Read More
                    </a>
                </div>
            </div>
        @empty
            <div class="alert alert-info">No posts available yet.</div>
        @endforelse

        <div class="d-flex justify-content-center">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection

