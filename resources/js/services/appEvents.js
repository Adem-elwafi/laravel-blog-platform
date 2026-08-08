export const FRIENDSHIP_UPDATED = 'friendship:updated';

export function dispatchFriendshipUpdated() {
  window.dispatchEvent(new CustomEvent(FRIENDSHIP_UPDATED));
}
