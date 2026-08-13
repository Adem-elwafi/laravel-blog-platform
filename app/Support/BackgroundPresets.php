<?php

namespace App\Support;

/**
 * Curated background presets for the Tandem background-customization feature.
 *
 * This file is intentionally identical in both repos (blog-platform and
 * realtime-chat). The keys must match the `theme.extend.backgroundImage
 * .backgrounds` tokens in BOTH apps' `tailwind.config.js` — that shared
 * token set is what makes a user's choice portable across the two apps.
 *
 * All presets are deep, muted tones chosen to pair with the near-black
 * `brand-900` / white card system and the `accent` green status color.
 */
class BackgroundPresets
{
    public const ALL = [
        'obsidian' => ['label' => 'Obsidian'],
        'graphite' => ['label' => 'Graphite'],
        'slate'    => ['label' => 'Slate'],
        'midnight' => ['label' => 'Midnight'],
        'ocean'    => ['label' => 'Ocean'],
        'emerald'  => ['label' => 'Emerald'],
        'plum'     => ['label' => 'Plum'],
        'ember'    => ['label' => 'Ember'],
    ];

    public static function keys(): array
    {
        return array_keys(self::ALL);
    }
}
