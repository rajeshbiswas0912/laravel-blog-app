@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="row">
    <div class="col-md-8">
        <article class="card mb-4">
            <div class="card-body">
                <h1 class="card-title">{{ $post->title }}</h1>
                <p class="text-muted">
                    By <strong>{{ $post->user->name }}</strong> on 
                    {{ $post->created_at->format('F d, Y') }}
                </p>
                <hr>
                <div class="card-text">
                    {!! nl2br(e($post->content)) !!}
                </div>
                @auth
                    @if($post->canEdit(Auth::user()))
                        <hr>
                        <div class="d-flex gap-2">
                            <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form method="POST" action="{{ route('posts.destroy', $post) }}" 
                                  onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
        </article>

        <div class="card">
            <div class="card-header">Comments ({{ $post->comments->count() }})</div>
            <div class="card-body">
                @auth
                    <form method="POST" action="{{ route('comments.store', $post) }}" class="mb-4">
                        @csrf
                        <div class="mb-3">
                            <label for="content" class="form-label">Add Comment</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" name="content" rows="3" required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Post Comment</button>
                    </form>
                @else
                    <p class="text-muted">Please <a href="{{ route('login') }}">login</a> to comment.</p>
                @endauth

                <hr>

                @forelse($post->comments as $comment)
                    <div class="mb-3">
                        <strong>{{ $comment->user->name }}</strong>
                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                        @auth
                            @if($comment->canEdit(Auth::user()))
                                <div class="float-end">
                                    <a href="{{ route('comments.edit', $comment) }}" class="btn btn-sm btn-link">Edit</a>
                                    <form method="POST" action="{{ route('comments.destroy', $comment) }}" 
                                          style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-link text-danger">Delete</button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                        <p class="mt-1">{{ nl2br(e($comment->content)) }}</p>
                    </div>
                    @if(!$loop->last)
                        <hr>
                    @endif
                @empty
                    <p class="text-muted">No comments yet. Be the first to comment!</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

