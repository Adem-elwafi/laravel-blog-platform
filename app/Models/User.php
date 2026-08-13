<?php

namespace App\Models;

use App\Enums\FriendshipStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'theme_background',
        'profile_background',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function friendRequestsSent(): HasMany
    {
        return $this->hasMany(Friendship::class, 'requester_id');
    }

    public function friendRequestsReceived(): HasMany
    {
        return $this->hasMany(Friendship::class, 'addressee_id');
    }

    public function friends(): Collection
    {
        $sent = $this->friendRequestsSent()
            ->where('status', FriendshipStatus::Accepted)
            ->with('addressee')
            ->get()
            ->pluck('addressee');

        $received = $this->friendRequestsReceived()
            ->where('status', FriendshipStatus::Accepted)
            ->with('requester')
            ->get()
            ->pluck('requester');

        return $sent->merge($received);
    }

    public function areFriends(User $other): bool
    {
        return $this->friendRequestsSent()
            ->where('addressee_id', $other->id)
            ->where('status', FriendshipStatus::Accepted)
            ->exists()
            ||
            $this->friendRequestsReceived()
            ->where('requester_id', $other->id)
            ->where('status', FriendshipStatus::Accepted)
            ->exists();
    }
}
