import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import { CHAT_APP_URL, refreshCsrfToken } from './services/csrfFetch';
import { dispatchFriendshipUpdated } from './services/appEvents';

window.Pusher = Pusher;

// pusher-js sends channel-authorization requests with `credentials: 'same-origin'`,
// so a cross-origin auth call (blog -> chat app) would never carry the session cookie
// and the server would reject it with 403. Provide a custom authorizer that uses
// `credentials: 'include'` and always sends a fresh XSRF token.
function buildChannelAuthorizer() {
  return ({ channelName, socketId }, callback) => {
    refreshCsrfToken()
      .then((xsrfToken) =>
        fetch(`${CHAT_APP_URL}/broadcasting/auth`, {
          method: 'POST',
          credentials: 'include',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'Accept': 'application/json',
            'X-XSRF-TOKEN': xsrfToken,
          },
          body: new URLSearchParams({ socket_id: socketId, channel_name: channelName }).toString(),
        })
      )
      .then((response) => {
        if (!response.ok) {
          throw new Error(`Channel auth failed (${response.status})`);
        }
        return response.json();
      })
      .then((data) => callback(null, data))
      .catch((error) => callback(error, null));
  };
}

async function initEcho() {
  try {
    const xsrfToken = await refreshCsrfToken();

    window.Echo = new Echo({
      broadcaster: 'reverb',
      key: 'vjx0qs18inxhengngths',
      wsHost: 'localhost',
      wsPort: 8081,
      wssPort: 8081,
      forceTLS: false,
      enabledTransports: ['ws'],
      authEndpoint: `${CHAT_APP_URL}/broadcasting/auth`,
      auth: {
        headers: {
          'X-XSRF-TOKEN': xsrfToken,
        },
      },
      channelAuthorization: {
        customHandler: buildChannelAuthorizer(),
      },
    });

    const userId = window.authUserId;
    if (userId) {
      window.Echo.private(`friends.${userId}`)
        .listen('.FriendshipUpdated', (e) => {
          console.log('FriendshipUpdated event received', e);
          dispatchFriendshipUpdated();
        });
    }
  } catch (error) {
    console.error('Echo initialization failed:', error);
  }
}

if (window.isAuthenticated) {
  initEcho();
}
