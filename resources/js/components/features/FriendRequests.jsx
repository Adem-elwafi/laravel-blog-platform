import React, { useState } from 'react';
import { useFriendRequests } from '../../hooks/useFriendship';
import { respondToFriendRequest } from '../../services/friendshipApi';
import { dispatchFriendshipUpdated } from '../../services/appEvents';

export default function FriendRequests() {
  const { data, isLoading, error, reload } = useFriendRequests();
  const [actionError, setActionError] = useState(null);
  const [busyId, setBusyId] = useState(null);

  const incoming = data?.incoming || [];
  const outgoing = data?.outgoing || [];

  const respond = async (id, action) => {
    setBusyId(id);
    setActionError(null);
    try {
      const response = await respondToFriendRequest(id, action);

      if (response.ok) {
        await reload();
        dispatchFriendshipUpdated();
      } else {
        const errorData = await response.json().catch(() => ({}));
        setActionError(errorData.message || `Could not ${action} request`);
      }
    } catch {
      setActionError('Network error — could not reach chat server');
    } finally {
      setBusyId(null);
    }
  };

  const errorBanner = actionError || error;

  if (!window.isAuthenticated) {
    return null;
  }

  if (isLoading) {
    return (
      <div className="p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h3 className="font-semibold text-gray-900 dark:text-white mb-3">Friend Requests</h3>
        <div className="space-y-3">
          {[1, 2, 3].map((i) => (
            <div key={i} className="h-4 bg-gray-300 dark:bg-gray-600 rounded w-3/4 animate-pulse" />
          ))}
        </div>
      </div>
    );
  }

  if (errorBanner && incoming.length === 0 && outgoing.length === 0) {
    return (
      <div className="p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h3 className="font-semibold text-gray-900 dark:text-white mb-3">Friend Requests</h3>
        <p className="text-sm text-red-600 dark:text-red-400">Could not load requests</p>
      </div>
    );
  }

  if (incoming.length === 0 && outgoing.length === 0) {
    return (
      <div className="p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h3 className="font-semibold text-gray-900 dark:text-white mb-3">Friend Requests</h3>
        <p className="text-sm text-gray-500 dark:text-gray-400">No pending requests</p>
      </div>
    );
  }

  return (
    <div className="p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
      <h3 className="font-semibold text-gray-900 dark:text-white mb-3">Friend Requests</h3>

      {errorBanner && (
        <p className="mb-3 text-sm text-red-600 dark:text-red-400">{errorBanner}</p>
      )}

      {incoming.length > 0 && (
        <div className="mb-4">
          <h4 className="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500 mb-2">
            Incoming ({incoming.length})
          </h4>
          <ul className="space-y-3">
            {incoming.map((req) => (
              <li key={req.id} className="flex items-center justify-between">
                <div className="flex items-center space-x-3 min-w-0">
                  <div className="w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm shrink-0">
                    {req.user?.name ? req.user.name.charAt(0).toUpperCase() : '?'}
                  </div>
                  <span className="text-sm font-medium text-gray-900 dark:text-white truncate">
                    {req.user?.name}
                  </span>
                </div>
                <div className="flex items-center space-x-2 shrink-0">
                  <button
                    onClick={() => respond(req.id, 'accept')}
                    disabled={busyId === req.id}
                    className="px-3 py-1.5 text-sm bg-green-500 hover:bg-green-600 text-white rounded-lg font-semibold disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    Accept
                  </button>
                  <button
                    onClick={() => respond(req.id, 'decline')}
                    disabled={busyId === req.id}
                    className="px-3 py-1.5 text-sm bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg font-semibold disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    Decline
                  </button>
                </div>
              </li>
            ))}
          </ul>
        </div>
      )}

      {outgoing.length > 0 && (
        <div>
          <h4 className="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500 mb-2">
            Sent ({outgoing.length})
          </h4>
          <ul className="space-y-3">
            {outgoing.map((req) => (
              <li key={req.id} className="flex items-center justify-between">
                <div className="flex items-center space-x-3 min-w-0">
                  <div className="w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm shrink-0">
                    {req.user?.name ? req.user.name.charAt(0).toUpperCase() : '?'}
                  </div>
                  <span className="text-sm font-medium text-gray-900 dark:text-white truncate">
                    {req.user?.name}
                  </span>
                </div>
                <span className="text-xs text-gray-400 dark:text-gray-500 shrink-0">Pending</span>
              </li>
            ))}
          </ul>
        </div>
      )}
    </div>
  );
}
