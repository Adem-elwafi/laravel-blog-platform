<x-app-layout>
    @php
        // H4: the cover/banner behind the avatar uses the profile's own preset.
        $profileCoverClass = $user->profile_background
            ? "bg-backgrounds-{$user->profile_background}"
            : 'bg-gradient-to-br from-brand-900 via-brand-800 to-brand-700';
    @endphp
    <div class="min-h-screen">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Main column: profile header + tabbed content --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Profile header card --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-brand-100 dark:border-gray-700 overflow-hidden">
                        {{-- Cover --}}
                        <div class="h-40 sm:h-56 {{ $profileCoverClass }} relative">
                            <svg class="absolute inset-0 w-full h-full opacity-10" fill="none" viewBox="0 0 600 200" preserveAspectRatio="none">
                                <path d="M0 140 Q 100 60 220 110 T 460 80 T 600 130 V 200 H 0 Z" fill="white"/>
                            </svg>
                        </div>

                        {{-- Avatar overlapping cover bottom edge --}}
                        <div class="flex flex-col items-center px-panel pb-panel -mt-16">
                            <div class="h-32 w-32 rounded-full bg-brand-900 text-white flex items-center justify-center text-4xl font-bold ring-4 ring-white dark:ring-gray-800">
                                {{ substr($user->name, 0, 1) }}
                            </div>

                            {{-- Identity --}}
                            <h1 class="mt-4 text-2xl font-bold text-brand-900 dark:text-white">{{ $user->name }}</h1>
                            <p class="mt-1 text-sm text-brand-400 dark:text-gray-500">Member since {{ $user->created_at->format('F Y') }}</p>

                            {{-- Stats row (real data) --}}
                            <div class="mt-4 flex items-center gap-8">
                                <div class="text-center">
                                    <span class="block text-2xl font-bold text-brand-900 dark:text-white">{{ $postCount }}</span>
                                    <span class="text-sm text-brand-400 dark:text-gray-500">Posts</span>
                                </div>
                                <div class="text-center">
                                    <span class="block text-2xl font-bold text-brand-900 dark:text-white">{{ $likesReceived }}</span>
                                    <span class="text-sm text-brand-400 dark:text-gray-500">Likes</span>
                                </div>
                                <div class="text-center">
                                    <span class="block text-2xl font-bold text-brand-900 dark:text-white">{{ $commentsReceived }}</span>
                                    <span class="text-sm text-brand-400 dark:text-gray-500">Comments</span>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="mt-5 flex flex-wrap items-center justify-center gap-3">
                                @auth
                                    @if(auth()->id() === $user->id)
                                        <a href="{{ route('profile.edit') }}"
                                           class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-brand-900 hover:bg-brand-800 text-white text-sm font-semibold transition">
                                            Edit profile
                                        </a>
                                    @else
                                        <div
                                            data-component="AddFriendButton"
                                            data-profile-user-id="{{ $user->id }}"
                                            data-is-own-profile="false"
                                        ></div>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>

                    {{-- Tabs bar (structured so more tabs can be added later) --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-brand-100 dark:border-gray-700 px-panel">
                        <nav class="flex gap-6 -mb-px" aria-label="Profile sections">
                            <a href="#posts"
                               class="py-3 border-b-2 border-brand-900 text-sm font-semibold text-brand-900 dark:text-white">
                                Posts
                                <span class="ml-1 text-brand-400 dark:text-gray-500">{{ $postCount }}</span>
                            </a>
                        </nav>
                    </div>

                    {{-- Posts feed --}}
                    @if($posts->count() > 0)
                        <div id="posts" class="space-y-5">
                            @foreach($posts as $post)
                                <article class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-brand-100 dark:border-gray-700 divide-y divide-brand-100 dark:divide-gray-700 overflow-hidden">
                                    <a href="{{ route('posts.show', $post) }}" class="flex items-center gap-avatar px-panel py-3">
                                        <div class="h-10 w-10 rounded-full bg-brand-900 text-white flex items-center justify-center font-bold">
                                            {{ substr($post->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-brand-900 dark:text-white text-sm">{{ $post->user->name }}</p>
                                            <p class="text-xs text-brand-400 dark:text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                                        </div>
                                    </a>

                                    @if($post->image)
                                        <div class="px-panel py-3">
                                            <img src="{{ asset('storage/' . $post->image) }}"
                                                 alt="{{ $post->title }}"
                                                 class="w-full h-auto max-h-[600px] object-cover rounded-xl"
                                                 loading="lazy">
                                        </div>
                                    @endif

                                    <div class="px-panel py-3">
                                        <a href="{{ route('posts.show', $post) }}" class="block text-lg font-bold text-brand-900 dark:text-white hover:text-brand-600 dark:hover:text-brand-300 transition">
                                            {{ $post->title }}
                                        </a>
                                        <div class="mt-3 flex items-center gap-5 text-sm text-brand-500 dark:text-gray-400">
                                            <span class="flex items-center gap-1.5">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                                                </svg>
                                                {{ $post->likes_count }}
                                            </span>
                                            <span class="flex items-center gap-1.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                                </svg>
                                                {{ $post->comments_count }}
                                            </span>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <div class="pt-2">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-brand-100 dark:border-gray-700 px-panel py-10 text-center">
                            <p class="text-lg font-semibold text-brand-900 dark:text-white">No posts yet</p>
                            <p class="mt-1 text-sm text-brand-400 dark:text-gray-500">Posts shared by this user will appear here.</p>
                        </div>
                    @endif
                </div>

                {{-- Sidebar: about --}}
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-brand-100 dark:border-gray-700 p-panel">
                        <h2 class="text-sm font-bold text-brand-900 dark:text-white uppercase tracking-wide">About</h2>
                        <dl class="mt-4 space-y-3 text-sm">
                            <div>
                                <dt class="text-xs font-semibold text-brand-400 dark:text-gray-500">Email</dt>
                                <dd class="mt-0.5 text-brand-900 dark:text-gray-200 break-all">{{ $user->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-semibold text-brand-400 dark:text-gray-500">Member since</dt>
                                <dd class="mt-0.5 text-brand-900 dark:text-gray-200">{{ $user->created_at->format('M j, Y') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
