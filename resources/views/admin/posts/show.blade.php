@extends('layouts.admin')

@section('title', 'View Post')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">View Post</h1>
    <div>
        <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h2>{{ $post->title }}</h2>
        <p class="text-muted">
            By <strong>{{ $post->user->name }}</strong> on 
            {{ $post->created_at->format('F d, Y') }}
        </p>
        <hr>
        <div>
            {!! nl2br(e($post->content)) !!}
        </div>
        <hr>
        <h5>Comments ({{ $post->comments->count() }})</h5>
        @forelse($post->comments as $comment)
            <div class="mb-3 p-3 bg-light rounded">
                <strong>{{ $comment->user->name }}</strong>
                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                <p class="mt-2 mb-0">{{ nl2br(e($comment->content)) }}</p>
            </div>
        @empty
            <p class="text-muted">No comments yet.</p>
        @endforelse
    </div>
</div>
@endsection

