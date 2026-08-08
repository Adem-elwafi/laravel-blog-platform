import { csrfFetch } from './csrfFetch';

export async function getFriends() {
  const response = await csrfFetch('/api/friends');

  if (!response.ok) {
    throw new Error(`Failed to fetch friends (${response.status})`);
  }

  const data = await response.json();
  return data.data || data.friends || data;
}

export async function getFriendRequests() {
  const response = await csrfFetch('/api/friend-requests');

  if (!response.ok) {
    throw new Error(`Failed to load requests (${response.status})`);
  }

  return response.json();
}

export function sendFriendRequest(addresseeId) {
  return csrfFetch('/api/friend-requests', {
    method: 'POST',
    body: { addressee_id: addresseeId },
  });
}

export function respondToFriendRequest(id, action) {
  return csrfFetch(`/api/friend-requests/${id}/${action}`, { method: 'POST' });
}
