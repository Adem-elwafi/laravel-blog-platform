export const CHAT_APP_URL = import.meta.env.VITE_CHAT_APP_URL || 'http://localhost:8000';

export function getCookie(name) {
  const match = document.cookie.match(new RegExp(`(?:^|;\\s*)${encodeURIComponent(name)}=([^;]*)`));
  return match ? decodeURIComponent(match[1]) : '';
}

export async function refreshCsrfToken() {
  await fetch(`${CHAT_APP_URL}/sanctum/csrf-cookie`, {
    method: 'GET',
    credentials: 'include',
    headers: { 'Accept': 'application/json' },
  });

  return getCookie('XSRF-TOKEN');
}

export async function chatGet(path) {
  return fetch(`${CHAT_APP_URL}${path}`, {
    credentials: 'include',
    headers: { 'Accept': 'application/json' },
  });
}

export async function chatPost(path, body) {
  const xsrfToken = await refreshCsrfToken();

  return fetch(`${CHAT_APP_URL}${path}`, {
    method: 'POST',
    credentials: 'include',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-XSRF-TOKEN': xsrfToken,
    },
    body: JSON.stringify(body),
  });
}
