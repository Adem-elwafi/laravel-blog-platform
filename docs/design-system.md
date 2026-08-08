# blog-platform — Design System

## Baseline

- **Stack:** Tailwind CSS v3 + Laravel Breeze default (Starter Kit UI).
- **Font:** Figtree (Breeze default), self-hosted by Laravel.
- **Dark mode:** enabled in the app; toggled on the public pages.

## Visual language

- **Backgrounds:** dark gradient panel on the home/landing page (`hero` + feature cards
  with `bg-gradient-to-*`, glassy overlays) over a light app shell.
- **Color usage:** brand accent used for primary CTAs; semantic colors for like/comment
  actions and admin actions.
- **Type scale:** default Tailwind; headings inherit the Figtree stack.

## Components (source of truth)

- Live React islands under `resources/js/components/`:
  - `InfiniteScrollPosts` — paginated feed card list (uses `data-initial-posts`).
  - `Comments` — comment thread + composer.
  - `FriendRequests`, `AddFriendButton`, `FriendList` — chat-driven social UI.
- Blade views under `resources/views/` (Breeze + posts/feed) provide the SSR shell the
  islands mount into.

## Motion & interaction

- Subtle entrance animations on landing/hero sections (GSAP is available in the frontend
  bundle; see `package.json`).
- Infinite scroll triggers on intersection; optimistic UI on like toggles.

## Ownership & conventions

- Global tokens/layout live in Blade + `resources/css/app.css`.
- Component styling is scoped to each JSX component (Tailwind utilities inline).
- Follow Breeze naming (`bg-primary`, `text-sm text-gray-600`, etc.) where classes are
  repeated.

## Future (Phase 4 — open)

- A shared, tokenized design system (CSS custom properties, semantic color names) is
  planned but not yet implemented. blog is currently the more customized app; realtime-chat
  still ships near-stock Breeze.
