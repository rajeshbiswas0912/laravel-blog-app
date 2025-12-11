@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<h1 class="h3 mb-4">Dashboard</h1>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Total Posts</h5>
                <h2 class="card-text">{{ $stats['total_posts'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Total Users</h5>
                <h2 class="card-text">{{ $stats['total_users'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">Total Comments</h5>
                <h2 class="card-text">{{ $stats['total_comments'] }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Recent Posts</h5>
            </div>
            <div class="card-body">
                @forelse($stats['recent_posts'] as $post)
                    <div class="mb-2">
                        <a href="{{ route('admin.posts.show', $post) }}">{{ $post->title }}</a>
                        <small class="text-muted d-block">By {{ $post->user->name }}</small>
                    </div>
                @empty
                    <p class="text-muted">No posts yet.</p>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Recent Users</h5>
            </div>
            <div class="card-body">
                @forelse($stats['recent_users'] as $user)
                    <div class="mb-2">
                        <strong>{{ $user->name }}</strong>
                        <small class="text-muted d-block">{{ $user->email }} ({{ $user->role }})</small>
                    </div>
                @empty
                    <p class="text-muted">No users yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

