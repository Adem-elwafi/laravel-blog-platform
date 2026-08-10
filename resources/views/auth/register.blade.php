<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-brand-900 mb-2">Create Account</h1>
        <p class="text-brand-500">Join our community and start sharing your stories</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-4">
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input
                id="name"
                class="block mt-1 w-full px-4 py-2 border border-brand-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-900 focus:border-brand-900"
                type="text"
                name="name"
                :value="old('name')"
                required
                autofocus
                autocomplete="name"
            />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-600" />
        </div>

        <!-- Email Address -->
        <div class="mb-4">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input
                id="email"
                class="block mt-1 w-full px-4 py-2 border border-brand-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-900 focus:border-brand-900"
                type="email"
                name="email"
                :value="old('email')"
                required
                autocomplete="username"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600" />
        </div>

        <!-- Password -->
        <div class="mb-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input
                id="password"
                class="block mt-1 w-full px-4 py-2 border border-brand-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-900 focus:border-brand-900"
                type="password"
                name="password"
                required
                autocomplete="new-password"
            />

            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input
                id="password_confirmation"
                class="block mt-1 w-full px-4 py-2 border border-brand-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-900 focus:border-brand-900"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
            />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-600" />
        </div>

        <div class="flex flex-col space-y-4">
            <button
                type="submit"
                class="w-full justify-center py-2 px-4 bg-brand-900 hover:bg-brand-800 text-white font-semibold rounded-lg transition focus:outline-none focus:ring-2 focus:ring-brand-900 focus:ring-offset-2">
                {{ __('Create Account') }}
            </button>
        </div>

        <div class="mt-6 text-center text-sm text-brand-500">
            {{ __('Already registered?') }}
            <a class="text-brand-900 hover:text-brand-700 font-semibold" href="{{ route('login') }}">
                {{ __('Sign in here') }}
            </a>
        </div>
    </form>
</x-guest-layout>
