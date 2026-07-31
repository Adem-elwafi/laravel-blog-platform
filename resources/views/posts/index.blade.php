<x-app-layout>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
        <div class="max-w-2xl mx-auto px-4">
            <!-- Page Header -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                    Discover Stories
                </h1>
                <p class="text-gray-600 dark:text-gray-400">
                    Explore amazing content from our community
                </p>
            </div>

            <!-- Create Post Button (Floating) -->
            @auth
                <div class="mb-6">
                    <a href="{{ route('posts.create') }}" 
                       class="flex items-center justify-center w-full bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white rounded-xl py-4 px-6 font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create New Post
                    </a>
                </div>
            @endauth

            <!-- Filters Section (React Component) -->
            <div class="mb-6">
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
                data-initial-posts='@json($posts->items())'
                data-current-page="{{ $posts->currentPage() }}"
                data-last-page="{{ $posts->lastPage() }}"
                data-filters="{{ json_encode(['search' => request('search', ''), 'author' => request('author', ''), 'sort' => request('sort', 'newest')]) }}"
            ></div>

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