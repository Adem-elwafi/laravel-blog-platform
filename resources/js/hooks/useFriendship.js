import { useCallback, useEffect, useState } from 'react';
import useAppEvent from './useAppEvent';
import { FRIENDSHIP_UPDATED } from '../services/appEvents';
import { getFriendRequests, getFriends } from '../services/friendshipApi';

function useFriendshipLoader(enabled, fetchData) {
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState(null);
  const [data, setData] = useState(null);

  const load = useCallback(async () => {
    try {
      setData(await fetchData());
    } catch (err) {
      setError(err.message);
    } finally {
      setIsLoading(false);
    }
  }, [fetchData]);

  useEffect(() => {
    if (!enabled) {
      setIsLoading(false);
      return;
    }

    load();
  }, [enabled, load]);

  useAppEvent(FRIENDSHIP_UPDATED, load);

  return { data, isLoading, error, reload: load };
}

export function useFriends() {
  return useFriendshipLoader(Boolean(window.isAuthenticated), getFriends);
}

export function useFriendRequests() {
  return useFriendshipLoader(Boolean(window.isAuthenticated), getFriendRequests);
}
