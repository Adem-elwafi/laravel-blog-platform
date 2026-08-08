// resources/js/components/Comments.jsx
import React, { useState } from 'react';

export default function Comments({ postId, user, initialComments, csrfToken }) {
    const [comments, setComments] = useState(initialComments || []);
    const [newComment, setNewComment] = useState('');
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);

    // Handle adding a new comment
    const handleAddComment = async (e) => {
        e.preventDefault();
        if (!newComment.trim()) return;

        const commentData = {
            body: newComment.trim()
        };

        // Optimistic update
        const tempComment = {
            id: Date.now(), // Temporary ID for optimistic UI
            body: newComment.trim(),
            user: user,
            created_at: new Date().toISOString(),
            user_id: user.id
        };

        const prevComments = [...comments];
        setComments([tempComment, ...comments]);
        setNewComment('');
        setLoading(true);
        setError(null);

        try {
            const response = await window.axios.post(`/api/posts/${postId}/comments`, commentData);
            // Replace temp comment with real one from API
            setComments([response.data.comment, ...prevComments]);
        } catch (err) {
            // Rollback on error
            setComments(prevComments);
            setError('Failed to add comment. Please try again.');
            console.error('Add comment error:', err);
        } finally {
            setLoading(false);
        }
    };

    // Handle deleting a comment
    const handleDeleteComment = async (commentId) => {
        const commentToDelete = comments.find(c => c.id === commentId);
        if (!commentToDelete) return;

        const prevComments = comments.filter(c => c.id !== commentId);
        setComments(prevComments);
        setLoading(true);
        setError(null);

        try {
            await window.axios.delete(`/api/comments/${commentId}`);
        } catch (err) {
            // Rollback on error
            setComments([...prevComments, commentToDelete]);
            setError('Failed to delete comment. Please try again.');
            console.error('Delete comment error:', err);
        } finally {
            setLoading(false);
        }
    };

    // Format timestamp like "2 hours ago"
    const formatTimeAgo = (dateString) => {
        const date = new Date(dateString);
        const now = new Date();
        const diffInSeconds = Math.floor((now - date) / 1000);

        if (diffInSeconds < 60) return 'Just now';
        if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} minutes ago`;
        if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} hours ago`;
        if (diffInSeconds < 2592000) return `${Math.floor(diffInSeconds / 86400)} days ago`;
        if (diffInSeconds < 31536000) return `${Math.floor(diffInSeconds / 2592000)} months ago`;
        return `${Math.floor(diffInSeconds / 31536000)} years ago`;
    };

    // Get user initial for avatar
    const getUserInitial = (name) => {
        return name ? name.charAt(0).toUpperCase() : '?';
    };

    return (
        <section className="mt-12">
            <h2 className="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                Comments ({comments.length})
            </h2>

            {/* Error message */}
            {error && (
                <div className="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                    {error}
                </div>
            )}

            {/* Comment form or sign-in prompt */}
            {user ? (
                <form onSubmit={handleAddComment} className="mb-8">
                    <div className="bg-white dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                        <textarea
                            value={newComment}
                            onChange={(e) => setNewComment(e.target.value)}
                            placeholder="Write a comment..."
                            className="w-full p-3 mb-4 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            rows="3"
                            disabled={loading}
                        />
                        <button
                            type="submit"
                            disabled={!newComment.trim() || loading}
                            className="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {loading ? 'Posting...' : 'Post Comment'}
                        </button>
                    </div>
                </form>
            ) : (
                <div className="mb-8 p-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                    <p className="text-gray-700 dark:text-gray-300">
                        <a href="/login" className="text-purple-600 hover:underline dark:text-purple-400">
                            Sign in
                        </a> to comment
                    </p>
                </div>
            )}

            {/* Comments list */}
            <div className="space-y-6">
                {comments.length === 0 ? (
                    <p className="text-gray-600 dark:text-gray-400 italic">
                        No comments yet. Be the first to comment!
                    </p>
                ) : (
                    comments.map((comment) => (
                        <div
                            key={comment.id}
                            className="bg-white dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700"
                        >
                            <div className="flex items-start space-x-4">
                                {/* User avatar */}
                                <div className="flex-shrink-0">
                                    <div className="h-10 w-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                                        {getUserInitial(comment.user?.name)}
                                    </div>
                                </div>

                                {/* Comment content */}
                                <div className="flex-1 min-w-0">
                                    <div className="flex items-center space-x-2 mb-2">
                                        <span className="font-semibold text-gray-900 dark:text-white">
                                            {comment.user?.name}
                                        </span>
                                        <span className="text-sm text-gray-500 dark:text-gray-400">
                                            {formatTimeAgo(comment.created_at)}
                                        </span>
                                    </div>
                                    <p className="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">
                                        {comment.body}
                                    </p>
                                </div>

                                {/* Delete button (only for owner or admin) */}
                                {(user && (user.id === comment.user_id || user.role === 'admin')) && (
                                    <button
                                        onClick={() => handleDeleteComment(comment.id)}
                                        disabled={loading}
                                        className="text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition"
                                        aria-label="Delete comment"
                                    >
                                        Delete
                                    </button>
                                )}
                            </div>
                        </div>
                    ))
                )}
            </div>
        </section>
    );
}