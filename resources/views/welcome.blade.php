<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Blog Platform') }} - Share Your Stories</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        
        <!-- GSAP Animation Library -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollToPlugin.min.js"></script>
        
        @vite(['resources/css/app.css'])
        
        <style>
            /* Custom Gradient Text Effect */
            .gradient-text {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            
            /* Glassmorphism Effect */
            .glass {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }
            
            .glass-dark {
                background: rgba(0, 0, 0, 0.3);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
            
            /* Scroll Progress Bar */
            .scroll-progress {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 4px;
                background: linear-gradient(90deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
                z-index: 9999;
                transform-origin: left;
                transform: scaleX(0);
            }
            
            /* Back to Top Button */
            .back-to-top {
                position: fixed;
                bottom: 30px;
                right: 30px;
                width: 50px;
                height: 50px;
                border-radius: 50%;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                display: flex;
                align-items: center;
                justify-center: center;
                cursor: pointer;
                box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
                opacity: 0;
                pointer-events: none;
                transition: all 0.3s ease;
                z-index: 999;
            }
            
            .back-to-top.visible {
                opacity: 1;
                pointer-events: all;
            }
            
            .back-to-top:hover {
                transform: translateY(-5px);
                box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
            }
            
            /* Animated Scroll Indicator */
            .scroll-indicator {
                animation: bounce 2s infinite;
            }
            
            @keyframes bounce {
                0%, 20%, 50%, 80%, 100% {
                    transform: translateY(0);
                }
                40% {
                    transform: translateY(-10px);
                }
                60% {
                    transform: translateY(-5px);
                }
            }
            
            /* Particle Background Animation */
            @keyframes float {
                0%, 100% {
                    transform: translateY(0) rotate(0deg);
                }
                50% {
                    transform: translateY(-20px) rotate(180deg);
                }
            }
            
            .particle {
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.1);
                animation: float 6s infinite ease-in-out;
            }
            
            /* Performance optimization for animations */
            .will-animate {
                will-change: transform, opacity;
            }
            
            /* Post Card Hover Effect */
            .post-card {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            /* Gradient Background for Hero */
            .hero-gradient {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
                position: relative;
                overflow: hidden;
            }
            
            /* Loading Overlay */
            .loading-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 10000;
            }
            
            .loading-spinner {
                width: 50px;
                height: 50px;
                border: 4px solid rgba(255, 255, 255, 0.3);
                border-top-color: white;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }
            
            @keyframes spin {
                to { transform: rotate(360deg); }
            }
            
            /* Responsive breakpoints adjustments */
            @media (prefers-reduced-motion: reduce) {
                * {
                    animation-duration: 0.01ms !important;
                    animation-iteration-count: 1 !important;
                    transition-duration: 0.01ms !important;
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
        
        <!-- Loading Overlay -->
        <div class="loading-overlay" id="loadingOverlay">
            <div class="loading-spinner"></div>
        </div>
        
        <!-- Scroll Progress Bar -->
        <div class="scroll-progress" id="scrollProgress"></div>
        
        <!-- Navigation Bar -->
        <nav class="fixed top-0 left-0 right-0 z-50 glass-dark">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <a href="{{ route('welcome') }}" class="text-2xl font-bold text-white hover:text-purple-300 transition-colors">
                        {{ config('app.name', 'Blog Platform') }}
                    </a>
                    
                    <!-- Navigation Links -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="#featured" class="text-white hover:text-purple-300 transition-colors nav-link">Featured</a>
                        <a href="#stats" class="text-white hover:text-purple-300 transition-colors nav-link">Stats</a>
                        <a href="{{ route('posts.index') }}" class="text-white hover:text-purple-300 transition-colors nav-link">All Posts</a>
                        
                        @auth
                            <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg bg-white/20 hover:bg-white/30 text-white transition-all">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-white hover:text-purple-300 transition-colors">Login</a>
                            <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg bg-white/20 hover:bg-white/30 text-white transition-all">Sign Up</a>
                        @endauth
                    </div>
                    
                    <!-- Mobile Menu Button -->
                    <button id="mobileMenuBtn" class="md:hidden text-white hover:text-purple-300 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Menu -->
            <div id="mobileMenu" class="md:hidden hidden bg-black/30 backdrop-blur-lg">
                <div class="px-4 pt-2 pb-4 space-y-3">
                    <a href="#featured" class="block text-white hover:text-purple-300 transition-colors py-2">Featured</a>
                    <a href="#stats" class="block text-white hover:text-purple-300 transition-colors py-2">Stats</a>
                    <a href="{{ route('posts.index') }}" class="block text-white hover:text-purple-300 transition-colors py-2">All Posts</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="block text-white hover:text-purple-300 transition-colors py-2">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="block text-white hover:text-purple-300 transition-colors py-2">Login</a>
                        <a href="{{ route('register') }}" class="block text-white hover:text-purple-300 transition-colors py-2">Sign Up</a>
                    @endauth
                </div>
            </div>
        </nav>
        
        <!-- Hero Section -->
        <section class="hero-gradient min-h-screen flex items-center justify-center relative overflow-hidden">
            <!-- Floating Particles -->
            <div class="particle" style="width: 80px; height: 80px; top: 10%; left: 10%; animation-delay: 0s;"></div>
            <div class="particle" style="width: 60px; height: 60px; top: 20%; right: 15%; animation-delay: 2s;"></div>
            <div class="particle" style="width: 100px; height: 100px; bottom: 15%; left: 15%; animation-delay: 4s;"></div>
            <div class="particle" style="width: 70px; height: 70px; bottom: 20%; right: 10%; animation-delay: 3s;"></div>
            
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 pt-16">
                <!-- Hero Title -->
                <h1 class="hero-title text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-extrabold text-white mb-6 will-animate">
                    Welcome to Our Blog
                </h1>
                
                <!-- Hero Subtitle -->
                <p class="hero-subtitle text-xl sm:text-2xl md:text-3xl text-white/90 mb-8 max-w-3xl mx-auto will-animate">
                    Discover amazing stories, insights, and ideas from our community of writers
                </p>
                
                <!-- CTA Buttons -->
                <div class="hero-cta flex flex-col sm:flex-row gap-4 justify-center items-center mb-12 will-animate">
                    <a href="{{ route('posts.index') }}" class="px-8 py-4 bg-white text-purple-600 rounded-full font-semibold text-lg hover:bg-gray-100 hover:scale-105 transition-all shadow-lg hover:shadow-2xl">
                        Explore Posts
                    </a>
                    @guest
                        <a href="{{ route('register') }}" class="px-8 py-4 bg-transparent border-2 border-white text-white rounded-full font-semibold text-lg hover:bg-white hover:text-purple-600 transition-all shadow-lg hover:shadow-2xl">
                            Join Community
                        </a>
                    @else
                        <a href="{{ route('posts.create') }}" class="px-8 py-4 bg-transparent border-2 border-white text-white rounded-full font-semibold text-lg hover:bg-white hover:text-purple-600 transition-all shadow-lg hover:shadow-2xl">
                            Create Post
                        </a>
                    @endguest
                </div>
                
                <!-- Mini Stats Preview -->
                <div class="hero-stats grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto mb-16 will-animate">
                    <div class="glass rounded-lg p-4">
                        <div class="text-3xl font-bold text-white">{{ $stats['posts'] }}+</div>
                        <div class="text-sm text-white/80">Posts</div>
                    </div>
                    <div class="glass rounded-lg p-4">
                        <div class="text-3xl font-bold text-white">{{ $stats['users'] }}+</div>
                        <div class="text-sm text-white/80">Writers</div>
                    </div>
                    <div class="glass rounded-lg p-4">
                        <div class="text-3xl font-bold text-white">{{ $stats['comments'] }}+</div>
                        <div class="text-sm text-white/80">Comments</div>
                    </div>
                    <div class="glass rounded-lg p-4">
                        <div class="text-3xl font-bold text-white">{{ $stats['likes'] }}+</div>
                        <div class="text-sm text-white/80">Likes</div>
                    </div>
                </div>
                
                <!-- Scroll Indicator -->
                <a href="#featured" class="scroll-indicator inline-block text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                </a>
            </div>
        </section>
        
        <!-- Featured Posts Section -->
        <section id="featured" class="featured-posts py-20 bg-gray-50 dark:bg-gray-900">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Section Header -->
                <div class="text-center mb-16">
                    <h2 class="section-title text-4xl md:text-5xl font-bold mb-4 will-animate">
                        <span class="gradient-text">Latest Posts</span>
                    </h2>
                    <p class="section-subtitle text-xl text-gray-600 dark:text-gray-400 will-animate">
                        Explore the freshest content from our community
                    </p>
                </div>
                
                <!-- Posts Grid -->
                @if($featuredPosts->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($featuredPosts as $post)
                            <article class="post-card bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden will-animate">
                                <!-- Post Image Placeholder -->
                                <div class="post-image h-48 bg-gradient-to-br from-purple-400 to-pink-400 relative overflow-hidden">
                                    <div class="absolute inset-0 flex items-center justify-center text-white">
                                        <svg class="w-16 h-16 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 6a2 2 0 012-2h12a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zm2 0v8h12V6H4zm2 2h8v4H6V8z"/>
                                        </svg>
                                    </div>
                                </div>
                                
                                <!-- Post Content -->
                                <div class="p-6">
                                    <!-- Post Title -->
                                    <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
                                        <a href="{{ route('posts.show', $post) }}">{{ Str::limit($post->title, 60) }}</a>
                                    </h3>
                                    
                                    <!-- Post Excerpt -->
                                    <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">
                                        {{ Str::limit(strip_tags($post->content), 120) }}
                                    </p>
                                    
                                    <!-- Post Meta -->
                                    <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-500">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center text-white font-semibold">
                                                {{ substr(optional($post->user)->name ?? '?', 0, 1) }}
                                            </div>
                                            <span>{{ optional($post->user)->name ?? 'Unknown author' }}</span>
                                        </div>
                                        <span>{{ $post->created_at->diffForHumans() }}</span>
                                    </div>
                                    
                                    <!-- Post Stats -->
                                    <div class="flex items-center gap-4 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                        <div class="flex items-center gap-1 text-gray-600 dark:text-gray-400">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                                            </svg>
                                            <span>{{ $post->likes->count() }}</span>
                                        </div>
                                        <div class="flex items-center gap-1 text-gray-600 dark:text-gray-400">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>{{ $post->comments->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                    
                    <!-- View All Posts Button -->
                    <div class="text-center mt-12">
                        <a href="{{ route('posts.index') }}" class="inline-block px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-full font-semibold text-lg hover:shadow-2xl hover:scale-105 transition-all">
                            View All Posts
                        </a>
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-xl text-gray-600 dark:text-gray-400">No posts yet. Be the first to create one!</p>
                        @auth
                            <a href="{{ route('posts.create') }}" class="inline-block mt-6 px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-full font-semibold text-lg hover:shadow-2xl hover:scale-105 transition-all">
                                Create First Post
                            </a>
                        @endauth
                    </div>
                @endif
            </div>
        </section>
        
        <!-- Stats Section with Animated Counters -->
        <section id="stats" class="stats-section py-20 bg-gradient-to-br from-blue-600 via-purple-600 to-pink-500 relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;1&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            </div>
            
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">
                        Platform Statistics
                    </h2>
                    <p class="text-xl text-white/90">
                        Join our growing community of writers and readers
                    </p>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <!-- Posts Counter -->
                    <div class="stat-item text-center glass rounded-2xl p-8">
                        <div class="stat-number text-5xl md:text-6xl font-bold text-white mb-2" data-target="{{ $stats['posts'] }}">0</div>
                        <div class="stat-label text-lg text-white/90">Blog Posts</div>
                        <svg class="w-12 h-12 mx-auto mt-4 text-white/50" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                        </svg>
                    </div>
                    
                    <!-- Users Counter -->
                    <div class="stat-item text-center glass rounded-2xl p-8">
                        <div class="stat-number text-5xl md:text-6xl font-bold text-white mb-2" data-target="{{ $stats['users'] }}">0</div>
                        <div class="stat-label text-lg text-white/90">Writers</div>
                        <svg class="w-12 h-12 mx-auto mt-4 text-white/50" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                    </div>
                    
                    <!-- Comments Counter -->
                    <div class="stat-item text-center glass rounded-2xl p-8">
                        <div class="stat-number text-5xl md:text-6xl font-bold text-white mb-2" data-target="{{ $stats['comments'] }}">0</div>
                        <div class="stat-label text-lg text-white/90">Comments</div>
                        <svg class="w-12 h-12 mx-auto mt-4 text-white/50" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    
                    <!-- Likes Counter -->
                    <div class="stat-item text-center glass rounded-2xl p-8">
                        <div class="stat-number text-5xl md:text-6xl font-bold text-white mb-2" data-target="{{ $stats['likes'] }}">0</div>
                        <div class="stat-label text-lg text-white/90">Total Likes</div>
                        <svg class="w-12 h-12 mx-auto mt-4 text-white/50" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Call-to-Action Section -->
        <section class="cta-section py-20 bg-gray-50 dark:bg-gray-800">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="cta-content max-w-4xl mx-auto text-center bg-gradient-to-r from-purple-600 to-pink-600 rounded-3xl p-12 shadow-2xl will-animate">
                    <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                        Ready to Share Your Story?
                    </h2>
                    <p class="text-xl text-white/90 mb-8">
                        Join our community of passionate writers and start creating amazing content today!
                    </p>
                    @guest
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-purple-600 rounded-full font-semibold text-lg hover:bg-gray-100 hover:scale-105 transition-all shadow-lg">
                                Get Started Free
                            </a>
                            <a href="{{ route('posts.index') }}" class="px-8 py-4 bg-transparent border-2 border-white text-white rounded-full font-semibold text-lg hover:bg-white hover:text-purple-600 transition-all">
                                Browse Posts
                            </a>
                        </div>
                    @else
                        <a href="{{ route('posts.create') }}" class="inline-block px-8 py-4 bg-white text-purple-600 rounded-full font-semibold text-lg hover:bg-gray-100 hover:scale-105 transition-all shadow-lg">
                            Create Your First Post
                        </a>
                    @endauth
                </div>
            </div>
        </section>
        
        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-12">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                    <!-- About -->
                    <div>
                        <h3 class="text-xl font-bold mb-4">{{ config('app.name', 'Blog Platform') }}</h3>
                        <p class="text-gray-400">A modern platform for writers to share their stories and connect with readers.</p>
                    </div>
                    
                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-xl font-bold mb-4">Quick Links</h3>
                        <ul class="space-y-2">
                            <li><a href="{{ route('posts.index') }}" class="text-gray-400 hover:text-white transition-colors">All Posts</a></li>
                            @auth
                                <li><a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-white transition-colors">Dashboard</a></li>
                                <li><a href="{{ route('posts.create') }}" class="text-gray-400 hover:text-white transition-colors">Create Post</a></li>
                            @else
                                <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition-colors">Login</a></li>
                                <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-white transition-colors">Register</a></li>
                            @endauth
                        </ul>
                    </div>
                    
                    <!-- Community -->
                    <div>
                        <h3 class="text-xl font-bold mb-4">Community</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors">About Us</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Guidelines</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Contact</a></li>
                        </ul>
                    </div>
                    
                    <!-- Social -->
                    <div>
                        <h3 class="text-xl font-bold mb-4">Connect</h3>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.374 0 0 5.373 0 12c0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0112 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Copyright -->
                <div class="border-t border-gray-800 pt-8 text-center text-gray-400">
                    <p>&copy; {{ date('Y') }} {{ config('app.name', 'Blog Platform') }}. All rights reserved.</p>
                </div>
            </div>
        </footer>
        
        <!-- Back to Top Button -->
        <button class="back-to-top" id="backToTop" aria-label="Back to top">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
            </svg>
        </button>
        
        <!-- GSAP Animations Script -->
        <script>
            // Debug: Log stats data from server
            console.log('Stats from server:', {
                posts: {{ $stats['posts'] ?? 0 }},
                users: {{ $stats['users'] ?? 0 }},
                comments: {{ $stats['comments'] ?? 0 }},
                likes: {{ $stats['likes'] ?? 0 }}
            });
            
            // Wait for DOM to be ready
            document.addEventListener('DOMContentLoaded', function() {
                
                // Register ScrollTrigger plugin
                gsap.registerPlugin(ScrollTrigger, ScrollToPlugin);
                
                // ============================================
                // LOADING ANIMATION
                // ============================================
                // Hide loading overlay after page loads with safety fallback
                const loadingOverlay = document.getElementById('loadingOverlay');
                
                if (loadingOverlay) {
                    gsap.to(loadingOverlay, {
                        duration: 0.5,
                        opacity: 0,
                        delay: 0.3,
                        onComplete: function() {
                            loadingOverlay.style.display = 'none';
                            // Start hero animations after loading completes
                            initHeroAnimations();
                        }
                    });
                } else {
                    // If loading overlay doesn't exist, start animations immediately
                    initHeroAnimations();
                }
                
                // Safety fallback: ensure loading overlay is hidden after max 2 seconds
                setTimeout(() => {
                    if (loadingOverlay && loadingOverlay.style.display !== 'none') {
                        loadingOverlay.style.display = 'none';
                        initHeroAnimations();
                    }
                }, 2000);
                
                // ============================================
                // HERO SECTION ANIMATIONS (On Page Load)
                // ============================================
                function initHeroAnimations() {
                    // Ensure elements are visible first, then animate
                    // Hero title slides up and fades in
                    const heroTitle = document.querySelector('.hero-title');
                    if (heroTitle) {
                        gsap.fromTo(heroTitle, 
                            { y: 60, opacity: 0 },
                            { 
                                duration: 1.2,
                                y: 0,
                                opacity: 1,
                                ease: 'power3.out'
                            }
                        );
                    }
                    
                    // Subtitle follows with slight delay
                    const heroSubtitle = document.querySelector('.hero-subtitle');
                    if (heroSubtitle) {
                        gsap.fromTo(heroSubtitle,
                            { y: 40, opacity: 0 },
                            {
                                duration: 1,
                                y: 0,
                                opacity: 1,
                                delay: 0.3,
                                ease: 'power3.out'
                            }
                        );
                    }
                    
                    // CTA buttons with stagger effect (appear one after another)
                    const heroButtons = document.querySelectorAll('.hero-cta a');
                    if (heroButtons.length > 0) {
                        gsap.fromTo(heroButtons,
                            { y: 30, opacity: 0 },
                            {
                                duration: 0.8,
                                y: 0,
                                opacity: 1,
                                delay: 0.6,
                                stagger: 0.2, // 0.2s delay between each button
                                ease: 'power2.out'
                            }
                        );
                    }
                    
                    // Hero stats cards with stagger
                    const heroStats = document.querySelectorAll('.hero-stats > div');
                    if (heroStats.length > 0) {
                        gsap.fromTo(heroStats,
                            { y: 30, opacity: 0 },
                            {
                                duration: 0.8,
                                y: 0,
                                opacity: 1,
                                delay: 0.9,
                                stagger: 0.1,
                                ease: 'power2.out'
                            }
                        );
                    }
                    
                    // Scroll indicator
                    const scrollIndicator = document.querySelector('.scroll-indicator');
                    if (scrollIndicator) {
                        gsap.fromTo(scrollIndicator,
                            { opacity: 0 },
                            {
                                duration: 0.8,
                                opacity: 1,
                                delay: 1.2,
                                ease: 'power2.out'
                            }
                        );
                    }
                }
                
                // ============================================
                // SCROLL-TRIGGERED ANIMATIONS
                // ============================================
                
                // Section titles animation - safer approach
                const sectionTitles = document.querySelectorAll('.section-title, .section-subtitle');
                if (sectionTitles.length > 0) {
                    gsap.utils.toArray('.section-title, .section-subtitle').forEach(element => {
                        gsap.fromTo(element,
                            { y: 40, opacity: 0 },
                            {
                                scrollTrigger: {
                                    trigger: element,
                                    start: 'top 90%',
                                    end: 'bottom 20%',
                                    toggleActions: 'play none none none',
                                    once: true
                                },
                                duration: 0.8,
                                y: 0,
                                opacity: 1,
                                ease: 'power2.out'
                            }
                        );
                    });
                }
                
                // Featured posts cards - stagger animation with individual triggers
                // Check if post cards exist before animating
                const postCards = document.querySelectorAll('.post-card');
                if (postCards.length > 0) {
                    // Animate each card individually when it enters viewport
                    postCards.forEach((card, index) => {
                        gsap.from(card, {
                            scrollTrigger: {
                                trigger: card,
                                start: 'top 90%', // Start earlier to ensure animation triggers
                                end: 'bottom 20%',
                                toggleActions: 'play none none none',
                                once: true // Only animate once
                            },
                            duration: 0.8,
                            y: 60,
                            opacity: 0,
                            delay: index * 0.1, // Stagger effect
                            ease: 'power2.out'
                        });
                    });
                }
                
                // Stats section animation - safer approach
                const statItems = document.querySelectorAll('.stat-item');
                if (statItems.length > 0) {
                    gsap.fromTo('.stat-item',
                        { scale: 0.8, opacity: 0 },
                        {
                            scrollTrigger: {
                                trigger: '.stats-section',
                                start: 'top 85%',
                                toggleActions: 'play none none none',
                                once: true
                            },
                            duration: 0.8,
                            scale: 1,
                            opacity: 1,
                            stagger: 0.1,
                            ease: 'back.out(1.7)'
                        }
                    );
                }
                
                // Animated counter for stats (fast count-up from 0 to target)
                document.querySelectorAll('.stat-number').forEach(stat => {
                    const target = parseInt(stat.getAttribute('data-target'), 10);
                    if (!target || Number.isNaN(target)) {
                        return; // Skip invalid targets
                    }

                    // Ensure starting state is 0 before the tween runs
                    stat.textContent = '0';

                    gsap.fromTo(stat,
                        { innerText: 0 },
                        {
                            scrollTrigger: {
                                trigger: '.stats-section',
                                start: 'top 75%',
                                toggleActions: 'play none none none',
                                once: true
                            },
                            duration: 1.2,
                            innerText: target,
                            ease: 'power1.out',
                            snap: { innerText: 1 },
                            onUpdate: () => {
                                // Keep the number clean and fast-updating
                                stat.textContent = Math.round(stat.innerText);
                            },
                            onComplete: () => {
                                stat.textContent = target.toString();
                            }
                        }
                    );
                });
                
                // CTA section animation - safer approach
                const ctaContent = document.querySelector('.cta-content');
                if (ctaContent) {
                    gsap.fromTo(ctaContent,
                        { scale: 0.9, opacity: 0 },
                        {
                            scrollTrigger: {
                                trigger: '.cta-section',
                                start: 'top 85%',
                                toggleActions: 'play none none none',
                                once: true
                            },
                            duration: 1,
                            scale: 1,
                            opacity: 1,
                            ease: 'back.out(1.4)'
                        }
                    );
                }
                
                // ============================================
                // POST CARD HOVER EFFECTS
                // ============================================
                // Enhanced hover animation for post cards using GSAP
                // Wait a bit to ensure cards are rendered
                setTimeout(() => {
                    document.querySelectorAll('.post-card').forEach(card => {
                        card.addEventListener('mouseenter', () => {
                            gsap.to(card, {
                                y: -12,
                                scale: 1.03,
                                boxShadow: '0 25px 50px rgba(102, 126, 234, 0.25)',
                                duration: 0.3,
                                ease: 'power2.out'
                            });
                        });
                        
                        card.addEventListener('mouseleave', () => {
                            gsap.to(card, {
                                y: 0,
                                scale: 1,
                                boxShadow: '0 10px 30px rgba(0, 0, 0, 0.1)',
                                duration: 0.3,
                                ease: 'power2.out'
                            });
                        });
                    });
                }, 100);
                
                // ============================================
                // SCROLL PROGRESS BAR
                // ============================================
                // Updates progress bar as user scrolls down the page
                const scrollProgress = document.getElementById('scrollProgress');
                
                window.addEventListener('scroll', () => {
                    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                    const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                    const scrollPercentage = (scrollTop / scrollHeight);
                    
                    gsap.to(scrollProgress, {
                        scaleX: scrollPercentage,
                        duration: 0.1,
                        ease: 'none'
                    });
                });
                
                // ============================================
                // BACK TO TOP BUTTON
                // ============================================
                // Shows button after scrolling 500px, smooth scroll to top on click
                const backToTop = document.getElementById('backToTop');
                
                if (backToTop) {
                    window.addEventListener('scroll', () => {
                        if (window.pageYOffset > 500) {
                            backToTop.classList.add('visible');
                        } else {
                            backToTop.classList.remove('visible');
                        }
                    });
                    
                    backToTop.addEventListener('click', (e) => {
                        e.preventDefault();
                        gsap.to(window, {
                            duration: 1,
                            scrollTo: { y: 0, autoKill: true },
                            ease: 'power3.inOut'
                        });
                    });
                }
                
                // ============================================
                // MOBILE MENU TOGGLE
                // ============================================
                const mobileMenuBtn = document.getElementById('mobileMenuBtn');
                const mobileMenu = document.getElementById('mobileMenu');
                
                if (mobileMenuBtn && mobileMenu) {
                    mobileMenuBtn.addEventListener('click', () => {
                        mobileMenu.classList.toggle('hidden');
                        
                        if (!mobileMenu.classList.contains('hidden')) {
                            gsap.from(mobileMenu, {
                                duration: 0.3,
                                height: 0,
                                opacity: 0,
                                ease: 'power2.out'
                            });
                        }
                    });
                    
                    // Close mobile menu when clicking on links
                    document.querySelectorAll('#mobileMenu a').forEach(link => {
                        link.addEventListener('click', () => {
                            mobileMenu.classList.add('hidden');
                        });
                    });
                }
                
                // ============================================
                // SMOOTH SCROLL FOR ANCHOR LINKS
                // ============================================
                // Smooth scroll to sections when clicking navigation links (same-page anchors only)
                document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                    anchor.addEventListener('click', function(e) {
                        const targetId = this.getAttribute('href');
                        
                        // Skip if it's just "#" or empty
                        if (!targetId || targetId === '#') {
                            return;
                        }
                        
                        const target = document.querySelector(targetId);
                        
                        // Only prevent default and animate if target exists on this page
                        if (target) {
                            e.preventDefault();
                            gsap.to(window, {
                                duration: 1,
                                scrollTo: { y: target, offsetY: 80 },
                                ease: 'power3.inOut'
                            });
                        }
                    });
                });
                
                // ============================================
                // PARALLAX EFFECT ON POST IMAGES (Optional)
                // ============================================
                // Subtle parallax movement on post card images
                const postImages = document.querySelectorAll('.post-image');
                if (postImages.length > 0) {
                    gsap.utils.toArray('.post-image').forEach(image => {
                        gsap.to(image, {
                            scrollTrigger: {
                                trigger: image,
                                start: 'top bottom',
                                end: 'bottom top',
                                scrub: 1 // Smooth scrubbing effect
                            },
                            y: -30,
                            ease: 'none'
                        });
                    });
                }
                
                // ============================================
                // ACCESSIBILITY: RESPECT REDUCED MOTION
                // ============================================
                // Disable animations if user prefers reduced motion
                const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
                
                if (prefersReducedMotion.matches) {
                    // Kill all GSAP animations
                    gsap.globalTimeline.clear();
                    ScrollTrigger.getAll().forEach(trigger => trigger.kill());
                    
                    // Remove loading overlay immediately
                    document.getElementById('loadingOverlay').style.display = 'none';
                }
                
            });
        </script>
    </body>
</html>
