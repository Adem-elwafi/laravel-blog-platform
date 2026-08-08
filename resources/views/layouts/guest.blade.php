<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Blog Platform') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50 dark:bg-gray-900">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <!-- Header -->
            <div class="w-full bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                        {{ config('app.name', 'Blog Platform') }}
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="w-full sm:max-w-md mt-8 px-6 py-8 bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <div class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
                <ul class="inline-flex items-center gap-4">
                    <li><a href="{{ url('/') }}" class="hover:underline">Home</a></li>
                    <li><a href="#" class="hover:underline">About</a></li>
                    <li><a href="#" class="hover:underline">Contact</a></li>
                    <li><a href="https://github.com/Adem-dev/blog-platform" target="_blank" rel="noopener" class="hover:underline">GitHub</a></li>
                </ul>
            </div>
        </div>
    </body>
</html>
