import React, { useState } from 'react';
import { chatPost } from '../utils/chatApi';

export default function AddFriendButton({ profileUserId, isOwnProfile }) {
  const [status, setStatus] = useState('idle');
  const [message, setMessage] = useState('');

  if (isOwnProfile || !window.isAuthenticated) {
    return null;
  }

  const handleAddFriend = async () => {
    setStatus('loading');
    setMessage('');

    try {
      let response = await chatPost('/api/friend-requests', { addressee_id: profileUserId });

      if (response.status === 419) {
        response = await chatPost('/api/friend-requests', { addressee_id: profileUserId });
      }

      if (response.status === 201) {
        setStatus('sent');
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

  if (status === 'sent') {
    return (
      <button
        disabled
        className="flex items-center space-x-2 px-6 py-3 bg-green-500 text-white rounded-lg font-semibold opacity-75 cursor-not-allowed"
      >
        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        className={`flex items-center space-x-2 px-6 py-3 rounded-lg font-semibold transition ${
          status === 'loading'
            ? 'bg-blue-400 text-white opacity-50 cursor-not-allowed animate-pulse'
            : 'bg-blue-500 hover:bg-blue-600 text-white'
        }`}
      >
        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
