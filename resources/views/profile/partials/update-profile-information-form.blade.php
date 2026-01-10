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

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Avatar Section -->
        <div class="sm:col-span-2">
            <x-main.input-label for="avatar" :value="__('Profile Picture')" />
            <div class="mt-2 flex items-center gap-4">
                <!-- Avatar Preview -->
                <div class="w-20 h-20 rounded-full overflow-hidden flex-shrink-0">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' .$user->avatar) }}" 
                             alt="{{ $user->name }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white font-bold text-xl">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    @endif
                </div>
                
                <!-- Upload Button -->
                <div class="flex-1">
                    <input type="file" name="avatar" id="avatar" accept="image/*" 
                           class="block w-full text-sm text-gray-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-md file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-blue-50 file:text-blue-700
                                  hover: file:bg-blue-100">
                    <p class="mt-1 text-xs text-gray-500">JPG, PNG, GIF (Max. 2MB)</p>
                </div>
            </div>
            <x-main.input-error data-turbo-temporary class="mt-2" :messages="$errors->get('avatar')" />
        </div>

        <div class="grid gap-8 grid-cols-2">
            <div>
                <x-main.input-label for="username" :value="__('Username')" />
                <div class="mt-2">
                    <x-main.text-input id="username" name="username" type="text" :value="old('username', $user->username)" required autofocus autocomplete="username" />
                </div>
                <x-main.input-error data-turbo-temporary class="mt-2" :messages="$errors->get('username')" />
            </div>

            <div>
                <x-main.input-label for="name" :value="__('Name')" />
                <div class="mt-2">
                    <x-main.text-input id="name" name="name" type="text" :value="old('name', $user->name)" required autocomplete="name" />
                </div>
                <x-main.input-error data-turbo-temporary class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-main.input-label for="email" :value="__('Email')" />
                <div class="mt-2">
                    <x-main.text-input id="email" name="email" type="email" :value="old('email', $user->email)" required autocomplete="email" />
                </div>
                <x-main.input-error data-turbo-temporary class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-3">
                        <p class="text-sm text-gray-800">
                            {{ __('Your email address is unverified.') }}

                            <button form="send-verification" class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-darkslategray">
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

            <div>
                <x-main.input-label for="phone" :value="__('Phone')" />
                <div class="mt-2">
                    <x-main.text-input id="phone" name="phone" type="text" :value="old('phone', $user->phone)" autocomplete="tel" />
                </div>
                <x-main.input-error data-turbo-temporary class="mt-2" :messages="$errors->get('phone')" />
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