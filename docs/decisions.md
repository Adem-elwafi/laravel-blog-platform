# blog-platform — Decisions & ADRs

Each entry records a decision, its rationale, and its trade-offs. Add new entries at the
top when a meaningful choice is made.

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
