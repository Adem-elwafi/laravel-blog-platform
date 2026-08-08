import React, { useState } from 'react';

/**
 * LikeButton Component
 * 
 * A React island that handles liking/unliking posts with instant UI updates
 * Communicates with Laravel API endpoint
 */
export default function LikeButton({ postId, initialLiked, initialLikesCount, isAuthenticated }) {
  const [liked, setLiked] = useState(initialLiked);
  const [likesCount, setLikesCount] = useState(initialLikesCount);
  const [isLoading, setIsLoading] = useState(false);

  /**
   * Get CSRF token from meta tag
   */
  const getCsrfToken = () => {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
  };

  /**
   * Handle like/unlike toggle
   */
  const handleToggleLike = async () => {
    if (!isAuthenticated) return;

    setIsLoading(true);

    try {
      const response = await fetch(`/posts/${postId}/like`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': getCsrfToken(),
        },
      });

      if (!response.ok) {
        throw new Error('Failed to toggle like');
      }

      const data = await response.json();

      // Update UI with response data
      setLiked(data.liked);
      setLikesCount(data.likes_count);
    } catch (error) {
      console.error('Error toggling like:', error);
      // Revert optimistic update on error
      setLiked(!liked);
    } finally {
      setIsLoading(false);
    }
  };

  if (!isAuthenticated) {
    // Guests see disabled button
    return (
      <div className="flex items-center space-x-2 px-6 py-3 bg-gray-200 dark:bg-gray-700 rounded-lg text-gray-900 dark:text-white cursor-not-allowed opacity-60">
        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
        <span>{likesCount} Likes</span>
        <span className="text-xs text-gray-600 dark:text-gray-500">(Sign in to like)</span>
      </div>
    );
  }

  // Authenticated users see interactive button
  return (
    <button
      onClick={handleToggleLike}
      disabled={isLoading}
      className={`flex items-center space-x-2 px-6 py-3 rounded-lg font-semibold transition ${
        liked
          ? 'bg-red-500 hover:bg-red-600 text-white'
          : 'bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-900 dark:text-white'
      } ${isLoading ? 'opacity-50 cursor-not-allowed' : ''}`}
      aria-label={liked ? 'Unlike this post' : 'Like this post'}
    >
      <svg
        className={`w-5 h-5 transition ${isLoading ? 'animate-pulse' : ''}`}
        fill="currentColor"
        viewBox="0 0 20 20"
      >
        <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" />
      </svg>
      <span>{liked ? 'Unlike' : 'Like'}</span>
      <span className="text-sm bg-opacity-20 px-2 py-1 rounded">{likesCount}</span>
    </button>
  );
}
