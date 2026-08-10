import React, { useRef, useState } from 'react';
import { createPost } from '../../services/postApi';
import {
  dispatchPostCreated,
  dispatchPostReplaced,
  dispatchPostCreateFailed,
} from '../../services/appEvents';

export default function PostComposer({ userName = '' }) {
  const [expanded, setExpanded] = useState(false);
  const [title, setTitle] = useState('');
  const [content, setContent] = useState('');
  const [posting, setPosting] = useState(false);
  const [error, setError] = useState('');
  const composerRef = useRef(null);
  const contentRef = useRef(null);

  const userId = Number(window.authUserId);
  const canPost = title.trim().length > 0 && content.trim().length > 0 && !posting;

  const openComposer = () => {
    setExpanded(true);
    requestAnimationFrame(() => contentRef.current?.focus());
  };

  const closeComposer = () => {
    setExpanded(false);
    setTitle('');
    setContent('');
    setError('');
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!canPost) return;

    const clientId = `local-${Date.now()}-${Math.random().toString(36).slice(2, 8)}`;

    // Optimistic: show the post immediately
    dispatchPostCreated({
      clientId,
      id: null,
      title: title.trim(),
      content: content.trim(),
      user_id: userId,
      user: { id: userId, name: userName || 'You' },
      created_at: new Date().toISOString(),
      likes_count: 0,
      comments_count: 0,
      liked: false,
      pending: true,
    });

    setPosting(true);
    setError('');

    try {
      const response = await createPost({ title: title.trim(), content: content.trim() });

      if (response.ok) {
        const post = await response.json();
        dispatchPostReplaced(clientId, post);
        setTitle('');
        setContent('');
        setExpanded(false);
      } else {
        let message = `Could not publish your post (${response.status}).`;
        try {
          const data = await response.json();
          if (data.message) message = data.message;
        } catch {
          /* non-JSON error body — keep default message */
        }
        dispatchPostCreateFailed(clientId);
        setError(message);
      }
    } catch {
      dispatchPostCreateFailed(clientId);
      setError('Network error — your post was not published. Please try again.');
    } finally {
      setPosting(false);
    }
  };

  const avatarInitial = (userName || '?').charAt(0).toUpperCase();

  return (
    <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-brand-100 dark:border-gray-700">
      <form onSubmit={handleSubmit} ref={composerRef}>
        {!expanded ? (
          /* Collapsed prompt */
          <button
            type="button"
            onClick={openComposer}
            className="w-full flex items-center gap-avatar p-panel text-left group"
          >
            <div className="h-10 w-10 shrink-0 rounded-full bg-brand-900 text-white flex items-center justify-center font-bold">
              {avatarInitial}
            </div>
            <span className="flex-1 text-brand-400 group-hover:text-brand-600 dark:text-gray-500 transition-colors text-[15px]">
              What&apos;s on your mind?
            </span>
            <span className="px-4 py-2 bg-brand-900 text-white text-sm font-semibold rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
              Post
            </span>
          </button>
        ) : (
          /* Expanded composer */
          <div className="p-panel space-y-3">
            <div className="flex items-center gap-avatar">
              <div className="h-10 w-10 shrink-0 rounded-full bg-brand-900 text-white flex items-center justify-center font-bold">
                {avatarInitial}
              </div>
              <div className="min-w-0">
                <p className="text-sm font-semibold text-brand-900 dark:text-white truncate">{userName || 'You'}</p>
                <p className="text-xs text-brand-400 dark:text-gray-500">Posting to the community</p>
              </div>
            </div>

            <input
              type="text"
              value={title}
              onChange={(e) => setTitle(e.target.value)}
              placeholder="Title"
              maxLength={120}
              className="w-full px-4 py-3 bg-brand-50 dark:bg-gray-700 border border-brand-100 dark:border-gray-600 rounded-xl text-base font-semibold text-brand-900 dark:text-white placeholder-brand-400 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-900 focus:border-brand-900 transition-all"
            />

            <textarea
              ref={contentRef}
              value={content}
              onChange={(e) => setContent(e.target.value)}
              placeholder="What's on your mind?"
              rows={3}
              maxLength={5000}
              className="w-full px-4 py-3 bg-brand-50 dark:bg-gray-700 border border-brand-100 dark:border-gray-600 rounded-xl text-[15px] text-brand-900 dark:text-white placeholder-brand-400 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-900 focus:border-brand-900 transition-all resize-none"
            />

            {error && (
              <p className="text-sm text-red-600 dark:text-red-400" role="alert">
                {error}
              </p>
            )}

            <div className="flex items-center justify-end gap-3 pt-2 border-t border-brand-100 dark:border-gray-700">
              <button
                type="button"
                onClick={closeComposer}
                className="px-4 py-2 text-sm font-semibold text-brand-500 hover:text-brand-700 dark:text-gray-400 transition-colors"
              >
                Cancel
              </button>
              <button
                type="submit"
                disabled={!canPost}
                className={`px-6 py-2 rounded-full text-sm font-semibold transition ${
                  canPost
                    ? 'bg-brand-900 hover:bg-brand-800 text-white'
                    : 'bg-brand-200 dark:bg-gray-700 text-brand-400 dark:text-gray-500 cursor-not-allowed'
                }`}
              >
                {posting ? 'Posting…' : 'Post'}
              </button>
            </div>
          </div>
        )}
      </form>
    </div>
  );
}
