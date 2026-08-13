<x-app-layout>
    @php
        // App-wide theme is a dark canvas → light text for elements sitting on it.
        $hasAppTheme = auth()->user()->theme_background ?? null;
        $railBase = $hasAppTheme
            ? 'text-brand-100 hover:bg-white/10 dark:text-gray-400 dark:hover:bg-gray-800'
            : 'text-brand-600 hover:bg-brand-100 dark:text-gray-400 dark:hover:bg-gray-800';
        $railActive = $hasAppTheme
            ? 'bg-white/15 text-white dark:bg-gray-700 dark:text-white'
            : 'bg-brand-900 text-white';
    @endphp
    <div class="min-h-screen">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-6 flex gap-6">

            <!-- Left Nav Rail -->
            <aside class="hidden lg:block w-52 shrink-0 sticky top-24 self-start">
                <nav class="flex flex-col gap-1" aria-label="Main">
                    <a href="{{ url('/') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-[15px] font-medium transition {{ request()->is('/') ? $railActive : $railBase }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V10"/>
                        </svg>
                        Home
                    </a>
                    <a href="{{ route('posts.index') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-[15px] font-medium transition {{ request()->routeIs('posts.*') ? $railActive : $railBase }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                        Posts
                    </a>
                    @auth
                        <a href="{{ route('friends.index') }}"
                           class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-[15px] font-medium transition {{ request()->routeIs('friends.*') ? $railActive : $railBase }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Friends
                        </a>
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}"
                               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-[15px] font-medium transition {{ request()->routeIs('admin.*') ? $railActive : $railBase }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                Admin
                            </a>
                        @endif
                    @endauth
                </nav>

                <div class="mt-8 pt-4 border-t border-brand-100 dark:border-gray-800">
                    <a href="{{ route('posts.create') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-[15px] font-medium {{ $hasAppTheme ? 'text-brand-100 hover:bg-white/10 dark:text-gray-400 dark:hover:bg-gray-800' : 'text-brand-600 hover:bg-brand-100 dark:text-gray-400 dark:hover:bg-gray-800' }} transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Image post
                    </a>
                </div>
            </aside>

            <!-- Center Feed (locked ~600px) -->
            <main class="flex-1 min-w-0 max-w-[600px] mx-auto w-full">
                @auth
                    <div class="mb-4">
                        <div
                            data-component="PostComposer"
                            data-user-name="{{ Auth::user()->name }}"
                        ></div>
                    </div>
                @endauth

                <!-- Filters (React Component) -->
                <div class="mb-4">
                    <div
                        data-component="PostFilters"
                        data-authors='@json($authors)'
                        data-initial-search="{{ request('search', '') }}"
                        data-initial-author="{{ request('author', '') }}"
                        data-initial-sort="{{ request('sort', 'newest') }}"
                    ></div>
                </div>

                <!-- Infinite Scroll Posts (React Component) -->
                <div
                    data-component="InfiniteScrollPosts"
                    data-initial-posts='@json($initialPosts)'
                    data-current-page="{{ $posts->currentPage() }}"
                    data-last-page="{{ $posts->lastPage() }}"
                    data-filters="{{ json_encode(['search' => request('search', ''), 'author' => request('author', ''), 'sort' => request('sort', 'newest')]) }}"
                ></div>
            </main>

            <!-- Right Context Panel -->
            <aside class="hidden xl:block w-72 shrink-0 space-y-4 sticky top-24 self-start">
                @auth
                    <div data-component="FriendRequests"></div>
                    <div data-component="FriendList"></div>
                @endauth

                @if($topPosts->isNotEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-brand-100 dark:border-gray-700 overflow-hidden">
                        <h3 class="px-panel py-3 text-sm font-bold text-brand-900 dark:text-white border-b border-brand-100 dark:border-gray-700">
                            Trending
                        </h3>
                        <ul class="divide-y divide-brand-100 dark:divide-gray-700">
                            @foreach($topPosts as $top)
                                <li>
                                    <a href="{{ route('posts.show', $top) }}" class="flex items-start gap-avatar px-panel py-3 hover:bg-brand-50 dark:hover:bg-gray-700/50 transition">
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-semibold text-brand-900 dark:text-white truncate">{{ $top->title }}</p>
                                            <p class="text-xs text-brand-400 dark:text-gray-500 mt-0.5">{{ $top->user->name }}</p>
                                        </div>
                                        <span class="flex items-center gap-1 text-xs font-medium text-brand-500 dark:text-gray-400 shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                                            </svg>
                                            {{ $top->likes_count }}
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </aside>
        </div>
    </div>

    <!-- GSAP Scroll Animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const animateCards = () => {
                const cards = document.querySelectorAll('.post-card:not([data-animated])');
                cards.forEach(card => {
                    gsap.from(card, {
                        duration: 0.6,
                        y: 40,
                        opacity: 0,
                        ease: 'power2.out'
                    });
                    card.dataset.animated = 'true';
                });
            };

            animateCards();
            window.addEventListener('feed:rendered', animateCards);
        });
    </script>
</x-app-layout>
