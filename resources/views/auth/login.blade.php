<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-brand-900 mb-2">Welcome Back</h1>
        <p class="text-brand-500">Sign in to your account to continue</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

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
                autofocus
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
                autocomplete="current-password"
            />

            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600" />
        </div>

        <!-- Remember Me -->
        <div class="block mb-4">
            <label for="remember_me" class="inline-flex items-center">
                <input
                    id="remember_me"
                    type="checkbox"
                    class="w-4 h-4 text-brand-900 bg-white border-brand-300 rounded focus:ring-brand-900 cursor-pointer"
                    name="remember">
                <span class="ms-2 text-sm text-brand-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex flex-col space-y-4">
            <button
                type="submit"
                class="w-full justify-center py-2 px-4 bg-brand-900 hover:bg-brand-800 text-white font-semibold rounded-lg transition focus:outline-none focus:ring-2 focus:ring-brand-900 focus:ring-offset-2">
                {{ __('Sign In') }}
            </button>

            @if (Route::has('password.request'))
                <a class="text-center text-sm text-brand-700 hover:text-brand-900 font-semibold" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <div class="mt-6 text-center text-sm text-brand-500">
            {{ __('Don\'t have an account?') }}
            <a class="text-brand-900 hover:text-brand-700 font-semibold" href="{{ route('register') }}">
                {{ __('Register here') }}
            </a>
        </div>
    </form>
</x-guest-layout>
