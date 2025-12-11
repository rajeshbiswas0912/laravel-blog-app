@extends('layouts.admin')

@section('title', 'Manage Posts')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Manage Posts</h1>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Created</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                    <tr>
                        <td>{{ $post->id }}</td>
                        <td>{{ Str::limit($post->title, 50) }}</td>
                        <td>{{ $post->user->name }}</td>
                        <td>{{ $post->created_at->format('M d, Y') }}</td>
                        <td>
                            @if($post->trashed())
                                <span class="badge bg-danger">Deleted</span>
                            @else
                                <span class="badge bg-success">Active</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.posts.show', $post) }}" class="btn btn-sm btn-primary">View</a>
                            <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm btn-warning">Edit</a>
                            @if($post->trashed())
                                <form method="POST" action="{{ route('admin.posts.restore', $post->id) }}" 
                                      style="display: inline;" onsubmit="return confirm('Restore this post?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">Restore</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" 
                                  style="display: inline;" onsubmit="return confirm('Permanently delete this post?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No posts found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection

