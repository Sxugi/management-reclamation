<x-guest-layout>
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="sm:mx-auto sm:max-w-sm">
            <x-main.application-logo class="mx-auto h-10 w-auto" />
            <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">{{ __('Reset Password') }}</h2>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <form class="space-y-6" method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div>
                    <x-main.input-label for="email">{{ __('Email address') }}</x-main.input-label>
                    <div class="mt-2">
                        <x-main.text-input 
                            type="email" 
                            name="email" 
                            id="email" 
                            value="{{ old('email', $request->email) }}" 
                            required 
                            autofocus 
                            autocomplete="username" 
                        />
                    </div>
                    <x-main.input-error :messages="$errors->get('email')" data-turbo-temporary class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <x-main.input-label for="password">{{ __('Password') }}</x-main.input-label>
                    <div class="mt-2">
                        <x-main.text-input 
                            type="password" 
                            name="password" 
                            id="password" 
                            required 
                            autocomplete="new-password" 
                        />
                    </div>
                    <x-main.input-error :messages="$errors->get('password')" data-turbo-temporary class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-main.input-label for="password_confirmation">{{ __('Confirm Password') }}</x-main.input-label>
                    <div class="mt-2">
                        <x-main.text-input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation" 
                            required 
                            autocomplete="new-password"
                        />
                    </div>
                    <x-main.input-error :messages="$errors->get('password_confirmation')" data-turbo-temporary class="mt-2" />
                </div>

                <div>
                    <x-main.primary-button type="submit">
                        {{ __('Reset Password') }}
                    </x-main.primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>