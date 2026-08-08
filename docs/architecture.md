# blog-platform — Architecture

Status: Living document. Update when SSO/CORS/Sanctum/session config or the shared-DB ownership split changes.

---

## 1. Two apps, one shared database

`blog-platform` and `realtime-chat` are two independent Laravel 12 apps in separate git
repositories. They share a single MySQL database (`shared_app_db`) and a single login
(session-based SSO, see below).

### Table ownership split

Each app owns its domain tables. Because both apps point at the same DB, each table is
created and maintained by exactly one app's migrations.

| Table | Owner | Notes |
|---|---|---|
| `users` | realtime-chat | Base `users` migration lives in chat. blog adds the `role` column via its own `add_role_to_users_table` migration. |
| `sessions`, `cache`, `jobs` | realtime-chat | Framework base tables, created by chat's base migrations. `sessions` is the shared SSO session store. |
| `conversations`, `messages`, `friendships` | realtime-chat | Chat domain. Friend-request logic lives here only (see decisions). |
| `posts`, `comments`, `likes` | blog-platform | Blog domain. |
| `personal_access_tokens` | shared infra | Both apps ship a Sanctum migration for it; the table is effectively shared infrastructure since it lives in the same DB. |

### The hard rule — no `migrate:fresh`

**Never run `php artisan migrate:fresh` (or `migrate:fresh --seed`) against `shared_app_db`.**
It would drop the *other* app's tables too. A decision to retire `migrate:fresh` is
recorded in `docs/decisions.md`; there is no code-level guard yet (open item).

---

## 2. SSO — how login works across the two apps

Both apps share:

- **The same `APP_KEY`** (same encryption key).
- **The same `SESSION_COOKIE` name** — `blog_chat_session`.
- **`SESSION_DRIVER=database`** → sessions live in the shared `sessions` table.
- **`SANCTUM_STATEFUL_DOMAINS`** — `localhost:8000,localhost:8001,localhost:5173,localhost:5174` (both apps).
- The **`web` guard** configured as Sanctum's `guard` (so session cookies authenticate API routes).

Flow:

1. User logs in via blog-platform (`POST /login` on `:8001`) → blog writes the session row
   and sets the `blog_chat_session` cookie.
2. Browser navigates to chat (or blog's React islands call chat's API) → the same cookie is
   sent.
3. Chat's API middleware (`EnsureFrontendRequestsAreStateful` in the `api` group) sees a
   request from a stateful domain, attaches the request cookies, and — because the session
   cookie/APP_KEY/DB are shared — reads the session started by blog.
4. `auth:sanctum` on chat's protected routes authenticates the user from that session.
5. Blog reads chat's JSON responses; its React islands send the shared cookie with
   `credentials: 'include'`.

This means logging into one app signs you into both, with no token exchange and no
cross-app API calls at login time.

---

## 3. CORS — asymmetric on purpose

CORS config is deliberately **not** symmetric:

| App | `allowed_origins` |
|---|---|
| blog-platform | `http://localhost:5173`, `http://localhost:5174` |
| realtime-chat | `http://localhost:5173`, `http://localhost:5174`, `http://localhost:8001` |

Why: only **one direction of cross-origin API traffic exists**. blog's frontend calls
chat's API (`/api/friend-requests`, `/api/friends`, `/broadcasting/auth`) cross-origin with
credentials, so chat must allow blog's origin (`:8001`). Chat never calls blog's API, so
blog does not need to allow chat's origin — keeping it out is defense-in-depth.

Both allow the Vite dev origins (`:5173`/`:5174`) because the React islands run from Vite
in development.

---

## 4. CSRF — fresh token required for cross-origin writes

Because blog's frontend performs cross-origin **writes** to chat with credentials, it must
send a fresh CSRF token on every POST/PUT/DELETE. The pattern lives in
`resources/js/utils/chatApi.js`:

1. `GET {chat}/sanctum/csrf-cookie` (with `credentials: 'include'`) → chat sets/refreshes
   the `XSRF-TOKEN` cookie.
2. Read the `XSRF-TOKEN` cookie value.
3. Send it as the `X-XSRF-TOKEN` header on the actual request.

This is the "CSRF-fresh-fetch" requirement — the same single fetch won't carry a valid
token reliably, so the helper always refreshes first. Note this helper is duplicated
(blog's `chatApi.js` and the chat app's own `auth-forms.js`) — see `current-state.md`.

---

## 5. Reverb — chat owns it, blog connects as a client

- **realtime-chat owns the Reverb server** (config in chat's `.env`; `BROADCAST_CONNECTION=reverb`) and is the only app that *broadcasts* server-side.
- **blog-platform is only a client**: it never broadcasts. Its `resources/js/echo.js`
  connects an Echo client (`broadcaster: 'reverb'`) to chat's Reverb port (`:8081`), uses
  chat's `/broadcasting/auth` as the auth endpoint, and listens on `friends.{userId}` to
  drive the friend-request UI. Blog's own `BROADCAST_CONNECTION` is `log`.

So real-time friend-request updates shown in blog actually arrive via chat's Reverb server.

---

## 6. Hard rules (do not violate without human review)

- No `migrate:fresh` / `migrate:fresh --seed` against `shared_app_db`.
- Respect ownership boundaries: blog writes only `posts`/`comments`/`likes` (and the `role`
  column); chat writes `conversations`/`messages`/`friendships`/`users` base fields. Blog
  must not create/modify chat-owned tables.
- Do not alter these config files without human review of the SSO implications:
  `bootstrap/app.php`, `config/cors.php`, `config/sanctum.php`, `.env` (especially
  `APP_KEY`, `SESSION_COOKIE`, `SESSION_DRIVER`, `SANCTUM_STATEFUL_DOMAINS`, `DB_DATABASE`).
- No cross-repo agent write access to `bootstrap/app.php` / `config/cors.php` /
  `config/sanctum.php` / `.env` without an explicit human sign-off.
