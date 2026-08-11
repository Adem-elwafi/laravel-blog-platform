# blog-platform — Design System

Status: Living document. Both apps draw from the same token set — update
`tailwind.config.js` in **both** repos when a token changes.

## Baseline

- **Stack:** Tailwind CSS v3. All custom tokens are defined in `tailwind.config.js`
  (`theme.extend`).
- **Font:** Figtree (Breeze default), self-hosted by Laravel.
- **Dark mode:** `'class'` strategy — toggled by adding/removing a class on `<html>`.

## Color tokens

### `brand` — the single identity color (near-black)

| Shade | Hex |
|---|---|
| 50 | `#F7F7F7` |
| 100 | `#EDEDED` |
| 200 | `#E2E2E2` |
| 300 | `#D4D4D4` |
| 400 | `#A3A3A3` |
| 500 | `#737373` |
| 600 | `#525252` |
| 700 | `#404040` |
| 800 | `#262626` |
| **900** | **`#111111`** |
| 950 | `#0A0A0A` |

**`brand-900` (`#111111`) is the hero color** (ADR-007). It carries all brand moments:

- Primary buttons: `bg-brand-900 hover:bg-brand-800` (e.g. Add Friend, publish, active nav).
- Focus rings on inputs: `focus:ring-brand-900 focus:border-brand-900`.
- Dark-mode surfaces and hover states: `dark:bg-brand-900/…`, `dark:hover:bg-brand-800`.

Light support shades: `brand-50` (tinted fills like `file:bg-brand-50`), `brand-100`
(hairline card borders `border-brand-100`), `brand-200` (section borders). Dark side uses
`brand-700`/`brand-800` borders and `brand-900/20` fills.

### `accent` — status-only green

| Shade | Hex |
|---|---|
| 50 | `#F0FDF4` |
| 100 | `#DCFCE7` |
| **500** | **`#22C55E`** |
| 600 | `#16A34A` |
| 700 | `#15803D` |
| DEFAULT | `#22C55E` |

**`accent` is restricted to status indicators only** (ADR-007): unread badges,
notification counts, online dots, friend-request count badges. It is never used for
CTAs or decoration. If a green is doing work that isn't "state", it's a bug — use
`brand-900` instead.

## Density & spacing tokens

Shared spacing tokens (Phase 4a) live under `theme.extend.spacing` in both apps'
`tailwind.config.js`:

| Token | Value | Typical use |
|---|---|---|
| `hairline` | `1px` | 1px borders/hairlines (`border-hairline`) |
| `card` | `14px` | tight card interiors (`p-card`) |
| `panel` | `16px` | standard card gutter (`p-panel`, `px-panel`) |
| `avatar` | `12px` | avatar-to-text gap, tight inline gaps (`gap-avatar`) |

## Radii

- **Cards / panels:** `rounded-2xl` (16px) — feed cards, request panels, profile cards.
- **Media inside cards:** `rounded-xl` (12px) — post images/thumbnails.
- Inputs and buttons: `rounded-xl` (12px) where a radius is needed.

## Type

- Default Tailwind type scale; headings inherit the Figtree stack.

## Component conventions

- `resources/js/components/features/` — domain islands (PostComposer, InfiniteScrollPosts,
  Comments, FriendRequests, FriendList, AddFriendButton, …).
- `resources/js/components/ui/` — reserved for shared presentational primitives (empty so
  far).
- Blade provides the SSR shell; both Blade and JSX use the same tokens inline.

## Shared-token rule (applies to both apps)

Both apps' `tailwind.config.js` ship the **identical** brand/accent/density token set.
Any new UI work in either app must use these tokens — no new hardcoded hex values or px
gaps. If a value isn't in the set, extend the shared set in **both** apps'
`tailwind.config.js` rather than hardcoding a one-off.
