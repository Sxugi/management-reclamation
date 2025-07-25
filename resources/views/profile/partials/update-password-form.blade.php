<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <x-main.input-label for="current_password" :value="__('Current Password')" />
                <div class="mt-2">
                    <x-main.text-input id="current_password" name="current_password" type="password" autocomplete="current-password" />
                </div>
                <x-main.input-error :messages="$errors->updatePassword->get('current_password')" data-turbo-temporary class="mt-2" />
            </div>

            <div>
                <x-main.input-label for="password" :value="__('New Password')" />
                <div class="mt-2">
                    <x-main.text-input id="password" name="password" type="password" autocomplete="new-password" />
                </div>
                <x-main.input-error :messages="$errors->updatePassword->get('password')" data-turbo-temporary class="mt-2" />
            </div>

            <div>
                <x-main.input-label for="password_confirmation" :value="__('Confirm Password')" />
                <div class="mt-2">
                    <x-main.text-input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" />
                </div>
                <x-main.input-error :messages="$errors->updatePassword->get('password_confirmation')" data-turbo-temporary class="mt-2" />
            </div>
        </div>

        <div class="flex items-center gap-4 mt-6">
            <x-main.primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>