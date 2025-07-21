<x-guest-layout>
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="sm:mx-auto sm:max-w-sm flex flex-col items-center">
            <x-main.application-logo class="mx-auto h-10 w-auto"/>
            <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Reset your password</h2>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <div class="mb-4 text-sm/6 text-gray-600 text-center">
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </div>

            <!-- Session Status -->
            <x-main.auth-session-status class="mb-4" :status="session('status')" />

            <form class="space-y-6" method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm/6 font-medium text-gray-900">{{ __('Email address') }}</label>
                    <div class="mt-2">
                        <x-main.text-input type="email" name="email" id="email" :value="old('email')" required autofocus autocomplete="email" />
                    </div>
                    <x-main.input-error :messages="$errors->get('email')" data-turbo-temporary class="mt-2" />
                </div>

                <div>
                    <x-main.primary-button type="submit" class="flex w-full justify-center rounded-md px-3 py-1.5 text-sm/6 font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 font-outfit">
                        {{ __('Email Password Reset Link') }}
                    </x-main.primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>