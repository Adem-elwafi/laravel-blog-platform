# blog-platform — Decisions & ADRs

Each entry records a decision, its rationale, and its trade-offs. Add new entries at the
top when a meaningful choice is made.

---

## ADR-010 — Friend-gated messaging is a hard backend rule (2026)

**Decision:** Messaging requires an accepted friendship between the two users, enforced
server-side in realtime-chat's `ChatController`: `ensureCanChatWith()` on `chat.show`, an
inline `areFriends()` guard in `chat.store`, and a friend-only conversation list
(`conversationsFor`). A removed or blocked friendship turns an existing thread read-only.

**Rationale:** Friend gating is a security/business rule, not a UI nicety — a direct API
call must not be able to message a stranger. Enforcing it at the server choke point (and
at `store`) makes it unbypassable regardless of what any frontend renders.

**Trade-offs:** Strangers can no longer open a thread or auto-create a conversation;
non-friend conversations disappear from the list. Chat owns messaging, so the rule lives
in one place (ADR-003), and blog surfaces the result only via the UI. Covered by
`ChatFriendshipGuardTest` (8 tests).

---

## ADR-009 — Dashboard replaced with a dedicated `/friends` page (2026)

**Decision:** blog-platform's `/dashboard` route now redirects to a dedicated, auth-only
`/friends` page (Facebook-style: `FriendRequests` + `FriendList` islands); the old
dashboard view is gone. `/dashboard` is kept only as a named route so auth redirects
continue to work.

**Rationale:** After SSO, the natural first destination for a signed-in user is the friend
management page, not a Breeze stub dashboard. The named route stays because Laravel auth
middleware redirects unauthenticated users to `route('dashboard')`.

**Trade-offs:** The `/dashboard` name is now a redirect shim; landing content lives on
`/friends`.

---

## ADR-008 — `/posts/create` kept as a fallback route (2026)

**Decision:** Despite shipping the in-feed composer (Phase 4), the classic
`GET /posts/create` route, `PostController@create`, and `posts/create.blade.php` view are
kept and still linked from the nav rail ("Image post").

**Rationale:** The classic composer has capabilities the in-feed composer does not yet
replace (image upload, character counters, draft checkbox) and provides a reliable,
linkable fallback while the in-feed composer matures.

**Trade-offs:** Two post-creation surfaces to keep consistent. Future work may converge
them; do not delete the fallback until the in-feed composer fully supersedes it.

---

## ADR-007 — Near-black + green identity/accent color system (2026)

**Decision:** `brand-900` (`#111111`) is the single identity color, supported by the full
`brand` neutral scale (50–950); `accent` green (`#22C55E`) is reserved strictly for status
indicators — badges, notification counts, online dots. Both apps define the identical
token set in `tailwind.config.js`.

**Rationale:** X-style single-color identity: one strong color carries the brand moments
(primary buttons, active nav, focus rings) while a single accent color always reads as
"state," never as decoration.

**Trade-offs:** Requires review discipline — accent green must not leak into CTAs or
decorative elements, and Breeze-era hardcoded blues/greens in older views must migrate to
tokens over time. Documented in `docs/design-system.md`.

---

## ADR-006 — Custom cross-origin channel authorizer for Echo (2026)

**Decision:** blog's Echo client does not use pusher-js's default channel authorizer. It
configures `channelAuthorization.customHandler` to POST to chat's `/broadcasting/auth`
with `credentials: 'include'` and a freshly refreshed `X-XSRF-TOKEN` header.

**Rationale:** pusher-js sends channel-authorization requests with
`credentials: 'same-origin'`. For blog's cross-origin auth call (blog :8001 → chat :8000)
that means the shared session cookie is never sent, and chat rejects the auth request with
403 — the Phase 3.5 real-time regression. The custom handler sends the cookie and a fresh
CSRF token.

**Trade-offs:** blog owns the auth plumbing instead of relying on the built-in authorizer.
Keeping it working requires chat's CORS to allow blog's origin on the `broadcasting/auth`
path. Do not revert to the default authorizer without re-testing the cross-origin flow.

---

## ADR-005 — `FriendshipStatus` enum is duplicated across apps (2026)

**Decision:** `FriendshipStatus` exists in both realtime-chat (`app/Enums/FriendshipStatus.php`) and blog-platform. realtime-chat is the canonical copy; blog's copy carries a sync-note comment and is kept identical by convention.

**Rationale:** The two apps are separate repositories with no shared package. blog needs the enum only to reason about friendship state locally (e.g., `User::friends()`), while the authoritative statuses live in chat.

**Trade-offs:** Duplication risks drift. Any change to cases/values must be applied to both copies. A shared package or extracted library would remove this, but is out of scope for now.

---

## ADR-004 — Shared-database table ownership split (2026)

**Decision:** `shared_app_db` is split by table ownership: blog owns `posts`, `comments`,
`likes`, and the `role` column on `users`; chat owns `users` (base), `sessions`, `cache`,
`jobs`, `conversations`, `messages`, `friendships`.

**Rationale:** Two Laravel apps share one login and one DB. Keeping domain tables owned by
exactly one app's migrations avoids migration-order conflicts and prevents one app from
silently corrupting the other's schema.

**Trade-offs:** `migrate:fresh` is effectively unusable (would drop the other app's tables);
schema evolution must be coordinated. A code-level guard does not exist yet (open item).

## ADR-003 — Friend-request domain lives only in realtime-chat (2026)

**Decision:** Friendship status + friend-request endpoints live in realtime-chat only
(`friendships` table, `FriendshipController`, `FriendshipService`, `FriendshipStatus`
enum). blog calls those endpoints cross-origin; it does not duplicate the domain.

**Rationale:** Single source of truth for relationship state; no drift between two copies.

**Trade-offs:** blog depends on chat's API being up for friend features to work.

## ADR-002 — `migrate:fresh` retired against `shared_app_db` (2026)

**Decision:** Do not run `php artisan migrate:fresh` (or `--seed`) in either app. The
command drops tables owned by the other app.

**Rationale:** Shared DB means a single `fresh` destroys both apps' schema and data.

**Trade-offs:** Must write targeted migrations, never re-seed from scratch. No guard
enforced in code yet.

## ADR-001 — Port and domain assignments (2026)

**Decision:** blog-platform serves on `:8001` (`php artisan serve`), realtime-chat on
`:8000`, Vite dev on `:5173`/`:5174`, Reverb on `:8081`.

**Rationale:** Fixed, documented ports make CORS, Sanctum stateful domains, and cookie
scope predictable in local dev. `SANCTUM_STATEFUL_DOMAINS` lists all four HTTP ports in
both apps.
