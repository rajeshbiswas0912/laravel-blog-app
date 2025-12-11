@extends('layouts.admin')

@section('title', 'View User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">View User</h1>
    <div>
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h4>{{ $user->name }}</h4>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Role:</strong> 
            <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }}">
                {{ ucfirst($user->role) }}
            </span>
        </p>
        <p><strong>Registered:</strong> {{ $user->created_at->format('F d, Y') }}</p>
        <p><strong>Total Posts:</strong> {{ $user->posts->count() }}</p>
        <p><strong>Total Comments:</strong> {{ $user->comments->count() }}</p>

        <hr>

        <h5>Posts by this user</h5>
        @forelse($user->posts as $post)
            <div class="mb-2">
                <a href="{{ route('admin.posts.show', $post) }}">{{ $post->title }}</a>
                <small class="text-muted d-block">{{ $post->created_at->format('M d, Y') }}</small>
            </div>
        @empty
            <p class="text-muted">No posts yet.</p>
        @endforelse
    </div>
</div>
@endsection

