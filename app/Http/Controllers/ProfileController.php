<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Support\BackgroundPresets;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the authenticated user's profile edit form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the specified user's public profile.
     */
    public function show(User $user): View
    {
        $posts = $user->posts()
            ->with('user')
            ->withCount('likes', 'comments')
            ->latest()
            ->paginate(9);

        // Real stats only — totals across all of the user's posts.
        $postCount = $user->posts()->count();
        $likesReceived = $user->posts()->withCount('likes')->get()->sum('likes_count');
        $commentsReceived = $user->posts()->withCount('comments')->get()->sum('comments_count');

        return view('profile.show', compact(
            'user',
            'posts',
            'postCount',
            'likesReceived',
            'commentsReceived'
        ));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's background customization (app-wide theme + profile cover).
     *
     * Both fields live on the shared `users` table (owned by realtime-chat),
     * so saving here is visible to realtime-chat immediately.
     */
    public function updateAppearance(Request $request): RedirectResponse
    {
        $request->validate([
            'theme_background' => ['nullable', 'string', Rule::in(BackgroundPresets::keys())],
            'profile_background' => ['nullable', 'string', Rule::in(BackgroundPresets::keys())],
        ]);

        $request->user()->fill([
            'theme_background' => $request->input('theme_background') ?: null,
            'profile_background' => $request->input('profile_background') ?: null,
        ])->save();

        return Redirect::route('profile.edit')->with('status', 'appearance-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
