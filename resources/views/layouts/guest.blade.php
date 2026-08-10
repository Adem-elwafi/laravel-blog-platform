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
    <body class="font-sans antialiased bg-white">
        <div class="min-h-screen grid lg:grid-cols-2">
            <!-- Brand panel: giant identity mark on near-black -->
            <div class="hidden lg:flex flex-col items-center justify-center bg-brand-900 text-white p-16">
                <x-application-logo class="w-52 h-52 fill-current text-white" />
                <h1 class="mt-10 text-4xl font-bold tracking-tight">
                    {{ config('app.name', 'Blog Platform') }}
                </h1>
                <p class="mt-4 max-w-sm text-center text-brand-300">
                    Share your stories, connect with friends, and discover what the community is reading.
                </p>
            </div>

            <!-- Form panel: constrained stack on light neutral -->
            <div class="flex items-center justify-center bg-brand-50 px-4 py-12">
                <div class="w-full max-w-sm">
                    <!-- Mobile brand mark -->
                    <div class="flex items-center justify-center lg:hidden mb-8">
                        <div class="bg-brand-900 rounded-full p-4">
                            <x-application-logo class="w-12 h-12 fill-current text-white" />
                        </div>
                    </div>

                    <div class="bg-white border border-brand-100 rounded-2xl shadow-sm p-panel">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
