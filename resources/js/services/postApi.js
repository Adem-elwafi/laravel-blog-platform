export function getCsrfToken() {
  return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

export function createPost(payload) {
  return fetch('/posts', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': getCsrfToken(),
    },
    body: JSON.stringify(payload),
  });
}

export function toggleLike(postId) {
  return fetch(`/posts/${postId}/like`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': getCsrfToken(),
    },
  });
}

export function addComment(postId, body) {
  return window.axios.post(`/api/posts/${postId}/comments`, { body });
}

export function deleteComment(commentId) {
  return window.axios.delete(`/api/comments/${commentId}`);
}
