<x-app-layout>
    @php
        // App-wide theme is a dark canvas → light text for elements sitting on it.
        $hasAppTheme = auth()->user()->theme_background ?? null;
    @endphp
    <div class="min-h-screen">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-6">
            <div class="mb-6">
                <h1 class="text-2xl font-bold {{ $hasAppTheme ? 'text-white' : 'text-brand-900 dark:text-white' }}">Friends</h1>
                <p class="text-sm {{ $hasAppTheme ? 'text-brand-200' : 'text-brand-500 dark:text-gray-400' }} mt-1">Accept incoming requests, track sent ones, and browse your network</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Main: requests + sent --}}
                <div class="lg:col-span-2 space-y-6">
                    <div
                        data-component="FriendRequests"
                    ></div>
                </div>

                {{-- Sidebar: friends list --}}
                <div class="space-y-6">
                    <div
                        data-component="FriendList"
                    ></div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
