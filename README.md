# Blog Platform

A modern, animated blog and social platform built with **Laravel 12**, **React islands**, **Tailwind CSS**, **GSAP**, and **Laravel Echo**. Posts, comments, likes, roles, dark mode, public profiles, and a full friendship system — all in one place.

The blog app pairs with a companion **realtime-chat** app: both share a single database and a single session, so a user logged in on either app is automatically logged in on the other (SSO), and the friendship/chat features live on the chat app's API.

## 🌟 Features

- **React islands**: like buttons, comments, post filters, infinite-scroll feed, Add Friend, friend list, and friend-request inbox — mounted via `data-component` hooks
- **Social / friendship system**: public profile pages with **Add Friend**, a **Friend Requests** inbox (accept / decline / sent), and a **Friends** list with online status
- **Real-time**: Laravel Echo listens for `FriendshipUpdated` events over Reverb and refreshes friends/requests live
- **Posts & media**: CRUD with optional images, rich excerpts, and author context
- **Engagement**: comments and likes with auth guards and live counts
- **Infinite scroll feed**: React-powered `/api/posts/feed` with an Intersection Observer
- **Roles & auth**: admin vs. user permissions, email/password auth (Breeze), Sanctum SPA auth
- **Animated UX**: GSAP hero, stat counters, scroll progress, parallax cards, smooth entrances
- **Theming**: dark/light support and responsive layouts

## 🛠️ Tech Stack

| Layer | Choice |
|---|---|
| Backend | Laravel 12 (PHP 8.2+) |
| Frontend | Blade + Tailwind CSS + React 19 islands (Vite) |
| Animations | GSAP + ScrollTrigger |
| Real-time | Laravel Echo + Pusher protocol (Reverb, hosted by the chat app) |
| Database | MySQL (`shared_app_db`) — SQLite via `.env.example` for a quick local run |
| Auth | Laravel Breeze + Sanctum (shared-session SSO with realtime-chat) |
| Testing | Pest PHP |

## 🧩 Architecture — how the two apps fit together

`blog-platform` (this repo) and `realtime-chat` form one system that shares:

- A **single MySQL database** (`shared_app_db`) — `users`, `sessions`, and `friendships` tables are owned by the chat app (see commit history); `posts`, `comments`, and `likes` are owned by this app.
- A **single shared session** — same session cookie name (`blog_chat_session`), same `APP_KEY`, same DB session driver. Logging in on either app authenticates both (SSO).
- The **friendship & chat API** — served by the chat app on port **8000**. This app calls it for Add Friend, the requests inbox, and the friends list, fetching a fresh CSRF token from the chat app's `/sanctum/csrf-cookie` before every state-changing POST.

| App | Folder | URL | Server |
|---|---|---|---|
| Blog platform | `blog-platform` | `http://localhost:8001` | `php artisan serve --port=8001` |
| Real-time chat | `realtime-chat` | `http://localhost:8000` | `php -S 127.0.0.1:8000 -t public public/server.php` |

## 📋 Requirements

- PHP 8.2+
- Composer
- Node.js 18+ / npm
- MySQL (or SQLite via `.env.example`)
- The `realtime-chat` app running on port 8000 (for the social/chat features)

## 🚀 Installation

1) Clone and enter the project
```bash
git clone <repository-url>
cd blog-platform
```

2) Install dependencies
```bash
composer install
npm install
```

3) Environment and key
```bash
cp .env.example .env
php artisan key:generate
```

> **Important for SSO:** `APP_KEY`, `SESSION_COOKIE`, `SESSION_DRIVER`, and `DB_*` must match `realtime-chat`'s `.env` so both apps share the same session and database. `VITE_CHAT_APP_URL` should point at the chat app (default `http://localhost:8000`).

## 🗄️ Database Setup

Run the migrations owned by this app, then seed demo data:

```bash
php artisan migrate
php artisan db:seed
```

- Admin user: `admin@example.com` / `password` (role: `admin`)
- Test user: `test@example.com` / `password`
- Plus seeded posts, comments, and likes

> The shared `users`, `sessions`, and `friendships` tables are migrated by **realtime-chat** — run `php artisan migrate` there first, then here.

## 📦 Running the Application

Use the combined dev script (server on **8001**, Vite on **5174**, queue worker, and logs):

```bash
composer run dev
```

Or run pieces manually:

```bash
php artisan serve --port=8001   # backend
npm run dev                     # Vite dev server on 5174
```

Production build:

```bash
npm run build
```

Open `http://localhost:8001`. The Vite dev server is `http://localhost:5174`.

## 🤝 Social / Friendship Flow

- On any user's public profile (`/users/{id}`), authenticated users can click **Add Friend**.
- The request is created against the chat app's `POST /api/friend-requests` (with a fresh CSRF token fetched right before the POST).
- The recipient sees it in the **Friend Requests** panel on their dashboard (`/dashboard`) — **Incoming** with Accept/Decline buttons and **Sent** for unanswered requests.
- Accepting adds the user to the **Friends** list (with online indicator); the list refreshes automatically via the real-time `friendship:updated` event.
- Chat messages happen in the realtime-chat app.

## 🎯 Routes & Touchpoints

| Route | Purpose |
|---|---|
| `GET /` | Welcome page with GSAP hero and stats |
| `GET /posts` | Posts index with React infinite-scroll feed |
| `GET /posts/{post}` | Post detail with React comments and like button |
| `GET /posts/create` | Create post (auth) |
| `GET /users/{user}` | Public profile with Add Friend mount point |
| `GET /dashboard` | Dashboard with Friend Requests inbox + Friends list |
| `GET /admin/dashboard` | Admin dashboard (admin role) |
| `POST /posts/{post}/like` | Toggle like (auth, rate-limited) |
| `POST /posts/{post}/comments` | Add comment (auth, rate-limited) |
| `GET /api/posts/feed` | JSON feed for infinite scroll |
| `GET/POST/DELETE /api/posts/{post}/like`, `/comments` | Sanctum API for React islands |

Chat app API consumed by this app: `GET /sanctum/csrf-cookie`, `GET|POST /api/friend-requests`, `POST /api/friend-requests/{id}/accept|decline`, `GET /api/friends`.

## 🔐 Authorization

- **Posts**: create (auth), edit/delete (owner or admin)
- **Comments**: create (auth), delete (owner or admin)
- **Likes**: toggle (auth)
- **Add Friend**: auth; self-requests rejected server-side
- **Admin**: `role = admin` via `/admin/dashboard`

## 🧩 React Islands

Components live in `resources/js/components` and mount via `data-component="ComponentName"` attributes in Blade:

- `LikeButton`, `Comments`, `PostFilters`, `InfiniteScrollPosts`
- `AddFriendButton`, `FriendList`, `FriendRequests`

Mount helper: `resources/js/utils/mountComponents.js`. Shared chat-API/CSRF helpers: `resources/js/utils/chatApi.js`.

## 🎨 Customization

- **Tailwind**: design tokens in `tailwind.config.js`
- **GSAP**: hero/stats/scroll animations in `resources/views/welcome.blade.php`
- **React feed**: infinite-scroll logic in `resources/js/components/InfiniteScrollPosts.jsx`

## 🧪 Testing

```bash
php artisan test
```

## 📸 Screenshots

- Welcome / hero
	![Welcome](./screenshots/welcome.png)
- Posts feed (infinite scroll + likes)
	![Posts Feed](./screenshots/posts-feed.png)

## 🚨 Troubleshooting

- **Caches**: `php artisan config:clear && php artisan cache:clear && php artisan view:clear`
- **Assets**: `npm install && npm run dev` (or `npm run build` for production)
- **SSO / 419 on Add Friend**: confirm the chat app is running on port 8000 and that `APP_KEY`/`SESSION_COOKIE`/`DB_*` match between both apps. The Add Friend flow now fetches a fresh CSRF token from the chat app before each POST.
- **DB issues**: verify `.env` and rerun `php artisan migrate:fresh --seed` (run the chat app's migrations first).

## 📚 Useful Links

- [Laravel Docs](https://laravel.com/docs)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [GSAP](https://greensock.com/gsap/)
- [Laravel Sanctum](https://laravel.com/docs/sanctum)
- [Laravel Reverb](https://reverb.laravel.com/)

---

Happy blogging! 🎉
