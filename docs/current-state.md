# blog-platform — Current State

Snapshot of what works, what's open, and what's deferred. Update as things change.

## Working today

**SSO end-to-end**
- Login on blog `:8001` writes the shared session (`blog_chat_session` cookie → shared
  `sessions` table); the same cookie authenticates chat's stateful API. Logging into one
  app signs you into both.

**Friend requests, from the blog UI**
- Dedicated `/friends` page (Facebook-style, 3-column) renders the `FriendRequests` and
  `FriendList` islands; profile pages render `AddFriendButton`.
- All friendship traffic goes cross-origin to chat's API (`friendshipApi` → `csrfFetch`
  with CSRF refresh + 419 retry): send / accept / decline work against the shared DB.

**Live real-time updates**
- blog's Echo client receives `friendship:updated` on the private `friends.{id}` channel
  (served by chat's Reverb) and refetches — request lists and buttons update without a
  reload. Works because of the custom cross-origin channel authorizer (ADR-006).

**Posts & feed**
- In-feed composer (`PostComposer`) with optimistic insert: dispatches `post:created`
  (pending card), server confirm swaps in the real `PostResource` (`post:replaced`), and
  failures roll back (`post:create-failed`).
- `PostFilters`, `InfiniteScrollPosts` (via `GET /api/posts/feed`), JSON paths for
  like/comment actions. Form Requests (`StorePostRequest`, `UpdatePostRequest`,
  `StoreCommentRequest`) + API Resources (`PostResource`, `CommentResource`) in place.
- `/posts/create` is kept as a fallback classic composer (image upload, character
  counters, draft) — still linked from the nav rail (ADR-008).

**Profile redesign**
- `profile/show.blade.php`: cover/avatar header, real stats (posts / likes / comments —
  no fake follower system), tabs + paginated feed, About sidebar, `AddFriendButton` when
  viewing someone else's profile.

**Navigation**
- `/dashboard` redirects to `/friends` (ADR-009); the Breeze dashboard stub is gone.

## Test coverage

- Only Breeze default tests (auth + profile). No feature tests yet for the feed or friend
  UI on the blog side — friendship behavior is covered server-side by chat's
  `ChatFriendshipGuardTest`.

## Open / deferred

- **Unified app name + logo** across both apps — explicitly held for a direct user
  decision. Not to be chosen by any agent.
- **Feature backlog** (brainstorm only, not committed): background customization, chat
  image uploads.
- **Block-user UI:** the backend action exists in chat (`BlockUserAction`,
  `POST /api/friend-requests/{friendship}/block`) but there is no UI anywhere yet.
- **No `migrate:fresh` guard:** convention only (ADR-002); no code-level guard.
- **`components/ui/` is empty** — shared presentational primitives not yet extracted.

## Data hygiene (Phase 1, still current)

- Meaningful accounts: **1** (Adem lwafi — owner), **3** (Yahya Elwafi), **4** (jawed
  Elwafi), **5** (yahya lwafi), **10** (med Wafi). Test/duplicate users (11, 17–20) and
  orphaned sessions were deleted.
- `role` is now in `User::$fillable` (the Phase 1 gap is closed).
