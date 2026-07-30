<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Profile Header --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 mb-8">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                <div class="h-24 w-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-3xl font-bold shrink-0">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div class="text-center sm:text-left flex-1">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h1>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">
                        Member since {{ $user->created_at->format('F Y') }}
                    </p>
                    <div class="flex flex-wrap justify-center sm:justify-start gap-6 mt-4">
                        <div class="text-center">
                            <span class="block text-2xl font-bold text-gray-900 dark:text-white">{{ $posts->total() }}</span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Posts</span>
                        </div>
                    </div>
                </div>

                {{-- Mount point for Add Friend / Message React island --}}
                <div
                    @auth
                        data-component="AddFriendButton"
                    @endauth
                    data-profile-user-id="{{ $user->id }}"
                    data-is-own-profile="{{ auth()->id() === $user->id ? 'true' : 'false' }}"
                ></div>
            </div>
        </div>

        {{-- User's Posts --}}
        @if($posts->count() > 0)
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Posts</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($posts as $post)
                    <article class="post-card bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                        @if($post->image)
                            <div class="h-48 overflow-hidden">
                                <img src="{{ asset('storage/' . $post->image) }}"
                                     alt="{{ $post->title }}"
                                     class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="h-48 bg-gradient-to-br from-blue-400 to-purple-500"></div>
                        @endif

                        <div class="p-5">
                            <h3 class="text-lg font-bold mb-2 text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                <a href="{{ route('posts.show', $post) }}">{{ Str::limit($post->title, 60) }}</a>
                            </h3>

                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-3">
                                {{ Str::limit(strip_tags($post->content), 120) }}
                            </p>

                            <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                                <span>{{ $post->created_at->diffForHumans() }}</span>
                                <div class="flex items-center gap-3">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                                        </svg>
                                        {{ $post->likes_count }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $post->comments_count }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        @else
            <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
                <p class="text-xl text-gray-500 dark:text-gray-400">No posts yet</p>
            </div>
        @endif
    </div>

    <script>
        window.isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
        window.authUserId = {{ auth()->id() ?? 'null' }};
        window.userRole = '{{ auth()->user()->role ?? '' }}';
    </script>
</x-app-layout>
