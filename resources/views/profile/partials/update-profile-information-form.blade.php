<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <x-main.input-label for="username" :value="__('Username')" />
                <div class="mt-2">
                    <x-main.text-input id="username" name="username" type="text" :value="old('username', $user->username)" required autofocus autocomplete="username" />
                </div>
                <x-main.input-error class="mt-2" :messages="$errors->get('username')" />
            </div>

            <div class="sm:col-span-2">
                <x-main.input-label for="email" :value="__('Email')" />
                <div class="mt-2">
                    <x-main.text-input id="email" name="email" type="email" :value="old('email', $user->email)" required autocomplete="email" />
                </div>
                <x-main.input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-3">
                        <p class="text-sm text-gray-800">
                            {{ __('Your email address is unverified.') }}

                            <button form="send-verification" class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-darkslategray font-outfit">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="flex items-center justify-start gap-4 mt-6">
            <x-main.primary-button>{{ __('Save') }}</x-main.primary-button>

            @if (session('status') === 'profile-updated')
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