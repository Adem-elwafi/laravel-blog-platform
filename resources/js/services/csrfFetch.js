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

export async function csrfFetch(path, { method = 'GET', body, headers = {} } = {}) {
  const isStateChanging = method.toUpperCase() !== 'GET';
  const requestHeaders = { 'Accept': 'application/json', ...headers };

  if (isStateChanging) {
    requestHeaders['Content-Type'] = 'application/json';
    requestHeaders['X-XSRF-TOKEN'] = await refreshCsrfToken();
  }

  const requestOptions = {
    method,
    credentials: 'include',
    headers: requestHeaders,
    body: body !== undefined ? JSON.stringify(body) : undefined,
  };

  let response = await fetch(`${CHAT_APP_URL}${path}`, requestOptions);

  if (isStateChanging && response.status === 419) {
    requestHeaders['X-XSRF-TOKEN'] = await refreshCsrfToken();
    response = await fetch(`${CHAT_APP_URL}${path}`, requestOptions);
  }

  return response;
}
