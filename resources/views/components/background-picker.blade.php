@props([
    'name' => null,
    'selected' => null,
    'label' => '',
])

@php
    $presets = \App\Support\BackgroundPresets::ALL;
@endphp

<div>
    @if ($label)
        <span class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</span>
    @endif

    <div class="mt-3 flex flex-wrap gap-3">
        <label class="cursor-pointer">
            <input type="radio" name="{{ $name }}" value="" class="sr-only peer" @checked($selected === null || $selected === '')>
            <span class="block h-14 w-20 rounded-xl border-2 border-white dark:border-gray-700 shadow-sm bg-brand-50 dark:bg-gray-800 peer-checked:ring-2 peer-checked:ring-accent peer-checked:border-brand-900 dark:peer-checked:border-white"></span>
            <span class="mt-1 block text-center text-[11px] font-medium text-gray-500 dark:text-gray-400">Default</span>
        </label>

        @foreach ($presets as $key => $meta)
            <label class="cursor-pointer">
                <input type="radio" name="{{ $name }}" value="{{ $key }}" class="sr-only peer" @checked($selected === $key)>
                <span class="block h-14 w-20 rounded-xl border-2 border-white dark:border-gray-700 shadow-sm bg-backgrounds-{{ $key }} peer-checked:ring-2 peer-checked:ring-accent"></span>
                <span class="mt-1 block text-center text-[11px] font-medium text-gray-500 dark:text-gray-400">{{ $meta['label'] }}</span>
            </label>
        @endforeach
    </div>
</div>
