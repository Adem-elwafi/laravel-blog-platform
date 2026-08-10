<x-app-layout>
    <article class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Post Header -->
        <header class="mb-8">
            <div class="mb-4 flex flex-wrap gap-2">
                <span class="inline-block px-3 py-1 bg-brand-100 dark:bg-brand-900 text-brand-800 dark:text-brand-200 text-xs font-semibold rounded-full">
                    Article
                </span>
            </div>

            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4 leading-tight">
                {{ $post->title }}
            </h1>

            <!-- Post Meta -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
                <div class="flex items-center space-x-4 mb-4 sm:mb-0">
                    <div class="h-12 w-12 bg-brand-900 rounded-full flex items-center justify-center text-white font-bold">
                        {{ substr($post->user->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $post->user->name }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $post->created_at->format('F j, Y') }}
                        </p>
                    </div>
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    <span class="block">Reading time: ~{{ ceil(str_word_count($post->content) / 200) }} min</span>
                </div>
            </div>
        </header>

        <!-- Post Image (if exists) -->
        @if($post->image)
            <div class="mb-8 rounded-xl overflow-hidden shadow-lg">
                <img src="{{ asset('storage/' . $post->image) }}" 
                     alt="{{ $post->title }}" 
                     class="w-full h-auto object-cover max-h-[600px]">
            </div>
        @endif

        <!-- Post Content -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 md:p-8 mb-8 prose dark:prose-invert max-w-none">
            <div class="text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap">
                {{ $post->content }}
            </div>
        </div>

        <!-- Post Actions -->
        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <!-- React Like Button Island -->
                <div>
                    <div 
                        id="react-like-button-{{ $post->id }}"
                        data-component="LikeButton"
                        data-post-id="{{ $post->id }}"
                        data-initial-liked="{{ auth()->check() && auth()->user()->likes()->where('post_id', $post->id)->exists() ? 'true' : 'false' }}"
                        data-initial-likes-count="{{ $post->likes()->count() }}"
                        data-is-authenticated="{{ auth()->check() ? 'true' : 'false' }}"
                    ></div>
                </div>

                <!-- Edit/Delete Actions -->
                @if(Auth::id() === $post->user_id || Auth::user()?->role === 'admin')
                    <div class="flex gap-2">
                        <a href="{{ route('posts.edit', $post) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg font-semibold transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </a>

                        <form method="POST" action="{{ route('posts.destroy', $post) }}" onsubmit="return confirm('Are you sure?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-semibold transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <!-- React Comments Section -->
        <section class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
            <div 
                data-component="Comments"
                data-post-id="{{ $post->id }}"
                data-user="{{ auth()->check() ? json_encode(auth()->user()->only(['id', 'name', 'email', 'role'])) : 'null' }}"
                data-initial-comments="{{ json_encode($post->comments()->with('user')->latest()->get()->map(fn($c) => [
                    'id' => $c->id,
                    'body' => $c->body,
                    'created_at' => $c->created_at->toISOString(),
                    'created_at_human' => $c->created_at->diffForHumans(),
                    'user' => [
                        'id' => $c->user->id,
                        'name' => $c->user->name,
                    ]
                ])) }}"
                data-csrf-token="{{ csrf_token() }}"
            ></div>
        </section>
    </article>
</x-app-layout>
