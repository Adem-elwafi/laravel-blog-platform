export function getCsrfToken() {
  return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
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
