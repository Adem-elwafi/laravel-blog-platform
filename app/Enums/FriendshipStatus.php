<?php

namespace App\Enums;

// NOTE: This enum is intentionally duplicated from realtime-chat (the canonical copy).
// Keep the cases and values in sync with realtime-chat\app\Enums\FriendshipStatus.php.
// See realtime-chat\docs\decisions.md (ADR-003/ADR-005) for rationale.
enum FriendshipStatus: string
{
    case Pending = 'pending';
    case Accepted = 'accepted';
    case Declined = 'declined';
    case Blocked = 'blocked';
}