# blog-platform — Current State

Snapshot of what works, what doesn't, and what's rough. Update as things change.

## Verified working (SSO end-to-end)

- Login on blog `:8001` → shared session cookie (`blog_chat_session`) is written to the
  shared `sessions` table.
- The same cookie authenticates chat's stateful API: calling
  `GET http://localhost:8000/api/friends` from blog's origin with credentials returns
  `200 {"data": []}`; the same request **without** the blog `Origin` header returns `401`.
- Blog React islands hit chat's `/api/friend-requests`, `/api/friends`, and
  `/broadcasting/auth` cross-origin with `credentials: 'include'` and a refreshed
  `X-XSRF-TOKEN`.
- Friend-request flow (send → accept → list) works against the shared DB.

## Real-time

- blog's `resources/js/echo.js` connects a client to chat's Reverb server (`:8081`) and
  listens on `friends.{userId}` → dispatches `friendship:updated` to drive the UI.
- Broadcasting is server-owned by realtime-chat; blog never broadcasts.

## Data hygiene (Phase 1 cleanup)

- User accounts reduced to the meaningful set: **1** (Adem lwafi — owner), **3** (Yahya
  Elwafi), **4** (jawed Elwafi — kept; real conversation), **5** (yahya lwafi), **10**
  (med Wafi). Deleted: test/duplicate users 11, 17, 18, 19, 20 + orphaned sessions + one
  dangling message.
- Conversations: **2** (1↔3), **3** (1↔4, has messages), **4** (1↔5, empty).
- Blog dev tooling: `laravel/pail` removed from `require-dev`; `composer run dev` =
  server + queue + vite only. Both repos committed (blog `07c52d6`, chat `2a84e1a`).

## Known-incomplete / open

- **Messaging list scoping:** chat's conversation list is not yet restricted to friends
  (open design decision; not a bug — tracked in chat's docs).
- **No `migrate:fresh` guard:** only a convention protects `shared_app_db` (ADR-002).
- **`role` on User:** the `role` column exists on `users` (added by blog's migration) and
  `AdminUserSeeder` assigns it, but `User::$fillable` omits `role`, so mass assignment
  silently drops it. Flagged for Phase 2 fix.

## Structurally rough (Phase 2 targets)

- No API Resources yet — `PostController::transformPost()` and raw model arrays are
  returned instead.
- Inline `$request->validate()` in `PostController`, `CommentController` instead of Form
  Requests.
- Duplicated CSRF-fresh-fetch logic in `resources/js/utils/chatApi.js` (same pattern as
  chat's `auth-forms.js`).
- `FriendshipStatus` enum duplicated in both apps (chat is canonical) — noted in chat's
  decisions; blog copy is kept in sync by convention.
