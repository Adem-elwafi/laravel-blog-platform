import React, { useCallback, useEffect, useRef, useState } from 'react';
import axios from 'axios';
import LikeButton from './LikeButton';

export default function InfiniteScrollPosts({ initialPosts = [], currentPage = 1, lastPage = 1, filters = {} }) {
  const [posts, setPosts] = useState(initialPosts);
  const [page, setPage] = useState(Number(currentPage) || 1);
  const [hasMore, setHasMore] = useState((Number(currentPage) || 1) < (Number(lastPage) || 1));
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const observerTarget = useRef(null);

  // Reset state when new initial data arrives (e.g., after filters change via full page reload)
  useEffect(() => {
    setPosts(initialPosts);
    const startingPage = Number(currentPage) || 1;
    const last = Number(lastPage) || 1;
    setPage(startingPage);
    setHasMore(startingPage < last);
  }, [initialPosts, currentPage, lastPage]);

  // Notify GSAP hooks to animate newly rendered cards
  useEffect(() => {
    window.dispatchEvent(new Event('feed:rendered'));
  }, [posts]);

  const loadMorePosts = useCallback(async () => {
    if (loading || !hasMore) return;

    setLoading(true);
    setError('');

    try {
      const response = await axios.get('/api/posts/feed', {
        params: {
          ...filters,
          page: page + 1,
        },
      });

      const { posts: newPosts = [], current_page, has_more } = response.data;

      if (Array.isArray(newPosts) && newPosts.length > 0) {
        setPosts(prev => [...prev, ...newPosts]);
        setPage(current_page || page + 1);
        setHasMore(Boolean(has_more));
      } else {
        setHasMore(false);
      }
    } catch (err) {
      console.error('Error loading more posts:', err);
      setError('Could not load more posts. Please try again.');
    } finally {
      setLoading(false);
    }
  }, [filters, hasMore, loading, page]);

  // Intersection Observer to trigger loading when sentinel enters viewport
  useEffect(() => {
    const target = observerTarget.current;
    if (!target) return;

    const observer = new IntersectionObserver(
      entries => {
        if (entries[0]?.isIntersecting) {
          loadMorePosts();
        }
      },
      { threshold: 0.2 }
    );

    observer.observe(target);
    return () => {
      observer.disconnect();
    };
  }, [loadMorePosts]);

  const formatDate = (dateString) => {
    const date = new Date(dateString);
    if (Number.isNaN(date.getTime())) return '';

    const now = new Date();
    const diffMs = now - date;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMs / 3600000);
    const diffDays = Math.floor(diffMs / 86400000);

    if (diffMins < 1) return 'Just now';
    if (diffMins < 60) return `${diffMins}m ago`;
    if (diffHours < 24) return `${diffHours}h ago`;
    if (diffDays < 7) return `${diffDays}d ago`;
    return date.toLocaleDateString();
  };

  const truncate = (str = '', length = 150) => {
    if (!str) return '';
    return str.length <= length ? str : `${str.substring(0, length)}...`;
  };

  return (
    <div className="space-y-6" id="posts-feed">
      {posts.map(post => {
        const userInitial = post.user?.name?.charAt(0)?.toUpperCase() || '?';
        const imageUrl = post.image ? `/storage/${post.image}` : null;
        const isOwner = Number(window.authUserId) === Number(post.user_id) || window.userRole === 'admin';

        return (
          <article key={post.id} className="post-card bg-white dark:bg-gray-800 rounded-2xl shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden">
            {/* Post Header */}
            <div className="flex items-center justify-between p-4">
              <div className="flex items-center space-x-3">
                <div className="h-10 w-10 rounded-full bg-gradient-to-br from-purple-400 via-pink-500 to-red-500 flex items-center justify-center text-white font-bold ring-2 ring-white dark:ring-gray-800">
                  {userInitial}
                </div>
                <div>
                  <p className="font-semibold text-gray-900 dark:text-white text-sm">{post.user?.name}</p>
                  <p className="text-xs text-gray-500 dark:text-gray-400">{formatDate(post.created_at)}</p>
                </div>
              </div>

              {isOwner && (
                <div className="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                  <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                  </svg>
                </div>
              )}
            </div>

            {/* Post Image */}
            {imageUrl && (
              <div className="relative">
                <a href={`/posts/${post.id}`}>
                  <img
                    src={imageUrl}
                    alt={post.title}
                    className="w-full h-auto max-h-[600px] object-cover cursor-pointer hover:opacity-95 transition-opacity"
                    loading="lazy"
                  />
                </a>
              </div>
            )}

            {/* Engagement Bar */}
            <div className="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
              <div className="flex items-center justify-between">
                <div className="flex items-center space-x-4">
                  <LikeButton
                    postId={post.id}
                    initialLiked={Boolean(post.liked)}
                    initialLikesCount={post.likes_count || 0}
                    isAuthenticated={Boolean(window.isAuthenticated)}
                  />

                  <a
                    href={`/posts/${post.id}#comments`}
                    className="flex items-center space-x-1 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition"
                  >
                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        strokeWidth="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                      ></path>
                    </svg>
                    <span className="text-sm font-medium">{post.comments_count || 0}</span>
                  </a>
                </div>

                <button className="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                  <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                  </svg>
                </button>
              </div>
            </div>

            {/* Post Content */}
            <div className="p-4">
              <a href={`/posts/${post.id}`}>
                <h2 className="text-xl font-bold text-gray-900 dark:text-white mb-2 hover:text-blue-600 dark:hover:text-blue-400 transition">
                  {post.title}
                </h2>
              </a>

              <p className="text-gray-700 dark:text-gray-300 leading-relaxed mb-3">
                {truncate(post.content, 150)}
              </p>

              <a
                href={`/posts/${post.id}`}
                className="inline-flex items-center text-blue-600 dark:text-blue-400 font-semibold hover:text-blue-700 dark:hover:text-blue-300 transition"
              >
                Read More
                <svg className="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5l7 7-7 7"></path>
                </svg>
              </a>
            </div>
          </article>
        );
      })}

      {loading && (
        <div className="flex justify-center py-8">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        </div>
      )}

      {error && (
        <div className="text-center text-sm text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/30 rounded-lg py-3 px-4">
          {error}
        </div>
      )}

      {hasMore && !loading && (
        <div ref={observerTarget} className="h-16"></div>
      )}

      {!hasMore && posts.length > 0 && (
        <div className="text-center py-12 bg-white dark:bg-gray-800 rounded-2xl">
          <svg className="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <p className="text-gray-600 dark:text-gray-400 font-medium">You've reached the end! 🎉</p>
          <p className="text-sm text-gray-500 dark:text-gray-500 mt-2">That's all the posts we have for now.</p>
        </div>
      )}

      {posts.length === 0 && !loading && (
        <div className="text-center py-16 bg-white dark:bg-gray-800 rounded-2xl">
          <svg className="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
          </svg>
          <h3 className="text-xl font-semibold text-gray-900 dark:text-white mb-2">No posts found</h3>
          <p className="text-gray-600 dark:text-gray-400">Try adjusting your filters</p>
        </div>
      )}
    </div>
  );
}
