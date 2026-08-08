<?php

namespace App\Http\Controllers;

use App\Models\Post;            // your Post model
use App\Models\User;            // for fetching authors
use App\Models\Comment;         // for stats
use App\Models\Like;            // for stats
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;      // for handling form requests
use Illuminate\Support\Facades\Auth; // if you check current user
use Illuminate\Support\Facades\Storage; // for handling image storage
use Illuminate\Support\Facades\Cache; // for caching stats

class PostController extends Controller
{
    /**
     * Display the welcome page with featured posts and stats.
     */
    public function welcome()
    {
        // Fetch 6 most recent posts with relationships for featured section
        $featuredPosts = Post::with(['user', 'likes', 'comments'])
            ->latest()
            ->take(6)
            ->get();
        
        // Calculate platform statistics with 5-minute cache for performance
        // Cache is cleared automatically when new content is created
        $stats = Cache::remember('homepage_stats', 300, function () {
            return [
                'posts' => Post::count(),
                'users' => User::count(),
                'comments' => Comment::count(),
                'likes' => Like::count(),
            ];
        });
        
        return view('welcome', compact('featuredPosts', 'stats'));
    }

    /**
     * Display a listing of posts in the social feed style with filters and sorting.
     */
    public function index(Request $request)
    {
        $posts = $this->buildFeedQuery($request)->paginate(10);

        $initialPosts = PostResource::collection($posts->items());

        // Authors for filter dropdown
        $authors = User::has('posts')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('posts.index', compact('posts', 'authors', 'initialPosts'));
    }

    /**
     * JSON feed for infinite scroll.
     */
    public function feed(Request $request)
    {
        $posts = $this->buildFeedQuery($request)->paginate(10);

        return response()->json([
            'posts' => PostResource::collection($posts->items()),
            'current_page' => $posts->currentPage(),
            'last_page' => $posts->lastPage(),
            'has_more' => $posts->hasMorePages(),
        ]);
    }

    /**
     * Base query for feed/index with shared filters, counts, and sorting.
     */
    private function buildFeedQuery(Request $request): Builder
    {
        $query = Post::with(['user'])
            ->withCount(['likes', 'comments']);

        if ($request->user()) {
            $query->withExists([
                'likes as liked_by_auth' => fn ($q) => $q->where('user_id', $request->user()->id),
            ]);
        }

        // Apply search filter
        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Filter by author
        if ($author = $request->author) {
            $query->where('user_id', $author);
        }

        // Sorting options
        $sort = $request->sort ?? 'newest';
        switch ($sort) {
            case 'popular':
                $query->orderBy('likes_count', 'desc');
                break;
            case 'most_commented':
                $query->orderBy('comments_count', 'desc');
                break;
            default:
                $query->latest();
        }

        return $query;
    }

public function create()
{
    return view('posts.create');
}

public function show(Post $post)
{
    return view('posts.show', compact('post'));
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        // Handle image upload if provided
        $validated = $request->validated();
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
            $validated['image'] = $imagePath;
        }

        // Add authenticated user ID
        $validated['user_id'] = Auth::id();

        // Create post with validated data
        $post = Post::create($validated);

        return redirect()->route('posts.show', $post)->with('success', 'Post created successfully.');
    }
    /**
     * Show the form for editing the specified resource.
     */
        public function edit(Post $post)
        {
            // Authorization check
            if (Auth::id() !== $post->user_id && Auth::user()->role !== 'admin') {
                abort(403);
            }
            return view('posts.edit', compact('post'));
        }

        public function update(UpdatePostRequest $request, Post $post)
        {
            // Authorization check
            if (Auth::id() !== $post->user_id && Auth::user()->role !== 'admin') {
                abort(403);
            }

            // Handle new image upload if provided
            $validated = $request->validated();
            if ($request->hasFile('image')) {
                // Delete old image if it exists
                if ($post->image) {
                    Storage::disk('public')->delete($post->image);
                }
                
                // Store new image
                $imagePath = $request->file('image')->store('posts', 'public');
                $validated['image'] = $imagePath;
            }

            // Update post with validated data
            $post->update($validated);

            return redirect()->route('posts.show', $post)->with('success', 'Post updated successfully.');
        }

        public function destroy(Post $post)
        {
            // Authorization check
            if (Auth::id() !== $post->user_id && Auth::user()->role !== 'admin') {
                abort(403);
            }

            // Delete associated image if it exists
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }

            $post->delete();

            return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
        }
        

}
