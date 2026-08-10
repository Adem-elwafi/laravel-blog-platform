export const FRIENDSHIP_UPDATED = 'friendship:updated';
export const POST_CREATED = 'post:created';
export const POST_REPLACED = 'post:replaced';
export const POST_CREATE_FAILED = 'post:create-failed';

export function dispatchFriendshipUpdated() {
  window.dispatchEvent(new CustomEvent(FRIENDSHIP_UPDATED));
}

export function dispatchPostCreated(post) {
  window.dispatchEvent(new CustomEvent(POST_CREATED, { detail: post }));
}

export function dispatchPostReplaced(clientId, post) {
  window.dispatchEvent(new CustomEvent(POST_REPLACED, { detail: { clientId, post } }));
}

export function dispatchPostCreateFailed(clientId) {
  window.dispatchEvent(new CustomEvent(POST_CREATE_FAILED, { detail: { clientId } }));
}
