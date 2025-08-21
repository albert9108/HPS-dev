@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Posts & Articles</h1>
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('posts.create') }}" class="btn btn-primary">Create New Post</a>
                @endif
            </div>
        </div>
    </div>

    @if($posts->count() > 0)
        <div class="row">
            @foreach($posts as $post)
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    @if($post->featured_image)
                        <img src="{{ asset('storage/' . $post->featured_image) }}" class="card-img-top" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            <a href="{{ route('posts.show', $post) }}" class="text-decoration-none">
                                {{ $post->title }}
                            </a>
                        </h5>
                        <p class="card-text text-muted">{{ $post->excerpt }}</p>
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    {{ $post->published_at->diffForHumans() }}
                                    @if($post->category)
                                        <span class="badge bg-secondary">{{ $post->category }}</span>
                                    @endif
                                </small>
                                <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-outline-primary">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $posts->links() }}
        </div>
    @else
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle fa-2x mb-3"></i>
            <h4>No Posts Available</h4>
            <p>There are currently no published posts or articles.</p>
            @if(Auth::user()->isAdmin())
                <a href="{{ route('posts.create') }}" class="btn btn-primary">Create First Post</a>
            @endif
        </div>
    @endif
</div>
@endsection
