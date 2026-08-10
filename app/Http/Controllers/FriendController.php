<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class FriendController extends Controller
{
    /**
     * Display the friend-management page.
     *
     * The friend request/friend list data is rendered client-side by the
     * FriendRequests/FriendList React components, which read from the
     * realtime-chat friendship API.
     */
    public function index(): View
    {
        return view('friends.index');
    }
}
