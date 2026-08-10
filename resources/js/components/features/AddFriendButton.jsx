import React, { useEffect, useState } from 'react';
import { sendFriendRequest } from '../../services/friendshipApi';
import { useFriends, useFriendRequests } from '../../hooks/useFriendship';
import { dispatchFriendshipUpdated } from '../../services/appEvents';
import { CHAT_APP_URL } from '../../services/csrfFetch';

export default function AddFriendButton({ profileUserId, isOwnProfile }) {
  const [status, setStatus] = useState('idle');
  const [message, setMessage] = useState('');
  const { data: friends } = useFriends();
  const { data: requests } = useFriendRequests();

  const profileId = Number(profileUserId);
  const isFriend = (friends || []).some((friend) => Number(friend.id) === profileId);
  const outgoingPending = (requests?.outgoing || []).some(
    (req) => Number(req.user?.id) === profileId
  );

  // Clear the optimistic "Request Sent" state once fresh data arrives and shows
  // the request is no longer pending (e.g. the other user declined it in real time).
  useEffect(() => {
    if (status === 'sent' && !outgoingPending && !isFriend) {
      setStatus('idle');
      setMessage('');
    }
  }, [status, outgoingPending, isFriend]);

  if (isOwnProfile || !window.isAuthenticated) {
    return null;
  }

  const handleAddFriend = async () => {
    setStatus('loading');
    setMessage('');

    try {
      const response = await sendFriendRequest(profileUserId);

      if (response.status === 201) {
        setStatus('sent');
        dispatchFriendshipUpdated();
      } else if (response.status === 422) {
        const data = await response.json();
        setStatus('error');
        setMessage(data.message || 'Request could not be processed');
      } else {
        const data = await response.json().catch(() => ({}));
        setStatus('error');
        setMessage(data.message || `Unexpected error (${response.status})`);
      }
    } catch (error) {
      setStatus('error');
      setMessage('Network error — could not reach chat server');
    }
  };

  if (isFriend) {
    return (
      <div className="flex items-center gap-3">
        <a
          href={`${CHAT_APP_URL}/chat/${profileId}`}
          className="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-brand-900 hover:bg-brand-800 text-white text-sm font-semibold transition"
        >
          <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
          </svg>
          <span>Message</span>
        </a>
        <span className="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-accent text-white text-sm font-semibold opacity-80">
          <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
          </svg>
          <span>Friends</span>
        </span>
      </div>
    );
  }

  if (outgoingPending || status === 'sent') {
    return (
      <button
        disabled
        className="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-accent text-white text-sm font-semibold opacity-80 cursor-not-allowed"
      >
        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
        </svg>
        <span>Request Sent</span>
      </button>
    );
  }

  return (
    <div>
      <button
        onClick={handleAddFriend}
        disabled={status === 'loading'}
        className={`inline-flex items-center gap-2 px-6 py-2.5 rounded-full text-sm font-semibold transition ${
          status === 'loading'
            ? 'bg-brand-800 text-white opacity-50 cursor-not-allowed animate-pulse'
            : 'bg-brand-900 hover:bg-brand-800 text-white'
        }`}
      >
        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
        </svg>
        <span>{status === 'loading' ? 'Sending...' : 'Add Friend'}</span>
      </button>
      {message && (
        <p className="mt-2 text-sm text-red-600 dark:text-red-400">{message}</p>
      )}
    </div>
  );
}
