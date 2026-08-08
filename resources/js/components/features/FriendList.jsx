import React from 'react';
import { CHAT_APP_URL } from '../../services/csrfFetch';
import { useFriends } from '../../hooks/useFriendship';

export default function FriendList() {
  const { data, isLoading, error } = useFriends();

  const friends = data || [];

  if (!window.isAuthenticated) {
    return null;
  }

  if (isLoading) {
    return (
      <div className="p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h3 className="font-semibold text-gray-900 dark:text-white mb-3">Friends</h3>
        <div className="space-y-3">
          {[1, 2, 3].map((i) => (
            <div key={i} className="flex items-center space-x-3 animate-pulse">
              <div className="w-10 h-10 bg-gray-300 dark:bg-gray-600 rounded-full" />
              <div className="h-4 bg-gray-300 dark:bg-gray-600 rounded w-24" />
            </div>
          ))}
        </div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h3 className="font-semibold text-gray-900 dark:text-white mb-3">Friends</h3>
        <p className="text-sm text-red-600 dark:text-red-400">Could not load friends</p>
      </div>
    );
  }

  if (friends.length === 0) {
    return (
      <div className="p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h3 className="font-semibold text-gray-900 dark:text-white mb-3">Friends</h3>
        <p className="text-sm text-gray-500 dark:text-gray-400">No friends yet</p>
      </div>
    );
  }

  return (
    <div className="p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
      <h3 className="font-semibold text-gray-900 dark:text-white mb-3">
        Friends ({friends.length})
      </h3>
      <ul className="space-y-2">
        {friends.map((friend) => (
          <li key={friend.id} className="flex items-center justify-between">
            <div className="flex items-center space-x-3">
              <div className="relative">
                <div className="w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                  {friend.name ? friend.name.charAt(0).toUpperCase() : '?'}
                </div>
                {friend.is_online && (
                  <span className="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full" />
                )}
              </div>
              <span className="text-sm font-medium text-gray-900 dark:text-white">
                {friend.name}
              </span>
            </div>
            <a
              href={`${CHAT_APP_URL}/chat/${friend.id}`}
              className="text-sm text-blue-600 dark:text-blue-400 hover:underline"
            >
              Message
            </a>
          </li>
        ))}
      </ul>
    </div>
  );
}
