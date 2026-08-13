<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Appearance') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Choose a background for the app and one for your profile cover. Both choices apply across the whole Tandem experience (blog and chat).") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.appearance') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-background-picker
                name="theme_background"
                :selected="old('theme_background', $user->theme_background)"
                :label="__('App-wide background')"
            />
            <x-input-error class="mt-2" :messages="$errors->get('theme_background')" />
        </div>

        <div>
            <x-background-picker
                name="profile_background"
                :selected="old('profile_background', $user->profile_background)"
                :label="__('Profile cover background')"
            />
            <x-input-error class="mt-2" :messages="$errors->get('profile_background')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'appearance-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
