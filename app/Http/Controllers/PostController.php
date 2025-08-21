<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('author')
                    ->published()
                    ->orderBy('published_at', 'desc')
                    ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category' => 'required|string|max:100',
            'tags' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'featured_image' => 'nullable|image|max:2048',
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->slug = Str::slug($request->title);
        $post->content = $request->content;
        $post->excerpt = $request->excerpt;
        $post->category = $request->category;
        $post->tags = $request->tags ? explode(',', $request->tags) : null;
        $post->status = $request->status;
        $post->author_id = Auth::id();

        if ($request->status === 'published') {
            $post->published_at = now();
        }

        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('posts', 'public');
            $post->featured_image = $path;
        }

        $post->save();

        return redirect()->route('posts.index')
                        ->with('success', 'Post created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        if ($post->status !== 'published' && !Auth::user()->isAdmin()) {
            abort(404);
        }

        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category' => 'required|string|max:100',
            'tags' => 'nullable|string',
            'status' => 'required|in:draft,published,archived',
            'featured_image' => 'nullable|image|max:2048',
        ]);

        $post->title = $request->title;
        $post->slug = Str::slug($request->title);
        $post->content = $request->content;
        $post->excerpt = $request->excerpt;
        $post->category = $request->category;
        $post->tags = $request->tags ? explode(',', $request->tags) : null;
        $post->status = $request->status;

        if ($request->status === 'published' && !$post->published_at) {
            $post->published_at = now();
        }

        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('posts', 'public');
            $post->featured_image = $path;
        }

        $post->save();

        return redirect()->route('posts.index')
                        ->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('posts.index')
                        ->with('success', 'Post deleted successfully!');
    }

    public function manage()
    {
        $posts = Post::with('author')
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);

        return view('posts.manage', compact('posts'));
    }
}
