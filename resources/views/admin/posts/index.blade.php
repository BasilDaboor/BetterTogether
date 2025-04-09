@extends('admin.layout')

@section('title', 'Posts Management')

@section('page-title', 'Posts Management')

@section('content')
<div class="container my-5 card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Posts List</h5>
        <!-- Add Post button removed -->
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Activity</th>
                        <th>Comments</th>
                        <th>Likes</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($post->image)
                                <img src="{{ asset('storage/'.$post->image) }}" width="50" height="50" class="rounded-circle shadow-sm">
                            @else
                                <img src="{{ asset('images/default-post.jpg') }}" width="50" height="50" class="rounded-circle shadow-sm">
                            @endif
                        </td>
                        <td>{{ Str::limit($post->title, 30) }}</td>
                        <td>{{ $post->user->full_name ?? 'Unknown' }}</td>
                        <td>
                            @if($post->activity)
                                <span class="badge bg-info text-white">{{ $post->activity->title }}</span>
                            @else
                                <span class="badge bg-secondary">None</span>
                            @endif
                        </td>
                        <td><span class="badge bg-primary">{{ $post->comments_count }}</span></td>
                        <td><span class="badge bg-danger">{{ $post->likes_count }}</span></td>
                        <td>{{ $post->created_at->format('Y-m-d') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-info text-white">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <!-- Edit button removed -->
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deletePostModal{{ $post->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            
                            <!-- Delete Post Modal -->
                            <div class="modal fade" id="deletePostModal{{ $post->id }}" tabindex="-1" aria-labelledby="deletePostModalLabel{{ $post->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deletePostModalLabel{{ $post->id }}">Confirm Deletion</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete the post "{{ $post->title }}"?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <form action="{{ route('posts.destroy', $post) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No posts found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        <div class="d-flex justify-content-center">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection