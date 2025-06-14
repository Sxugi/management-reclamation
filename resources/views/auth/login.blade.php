<x-guest-layout>
    <div class="w-full bg-whitesmoke-100 overflow-x-hidden flex flex-col items-center justify-center py-10 md:py-20 lg:py-[180px] px-4 md:px-12 lg:px-24 xl:px-[120px] box-border text-left text-whitesmoke font-outfit">
        <div class="rounded-[25px] bg-darkslategray w-full max-w-[500px] lg:max-w-lg flex flex-col items-center justify-center py-8 md:py-12 lg:py-[54px] px-4 sm:px-6 md:px-8 lg:px-[86px] box-border">
            <div class="w-full flex flex-col items-center justify-center gap-6 md:gap-8">
                <!-- Title and Description -->
                <div class="self-stretch flex flex-col items-start justify-start gap-2 md:gap-3">
                    <div class="relative text-2xl md:text-3xl lg:text-4xl leading-tight md:leading-[44px] font-semibold">Log In</div>
                    <div class="relative text-sm leading-5 text-darkgray">Enter your email / username and password to log in!</div>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('login') }}" class="self-stretch flex flex-col items-center justify-center text-sm text-gray-100 w-full">
                    @csrf
                    <div class="w-full flex flex-col items-start justify-start gap-4 md:gap-5">
                        <!-- Email Address -->
                        <div class="w-full flex flex-col items-start justify-start">
                            <div class="w-full flex flex-col items-start justify-start gap-1.5">
                                <label for="email" class="relative leading-5 font-medium">
                                    Email / Username
                                    <span class="text-tomato">*</span>
                                </label>
                                <div class="input-container w-full shadow-[0px_1px_2px_rgba(16,_24,_40,_0.05)] rounded-lg bg-white border-lightgray border-solid border-[1px] box-border h-11 overflow-hidden flex flex-row items-center py-2.5 px-4 text-gray-200">
                                    <x-main.text-input 
                                        id="email" 
                                        name="email" 
                                        type="text" 
                                        placeholder="Enter your email or username" 
                                        required autofocus 
                                        autocomplete="username" 
                                        class="flex-1 relative leading-5 bg-transparent border-none focus:ring-0 p-0 text-gray-200 w-full"
                                    />
                                </div>
                                @error('email')
                                    <span class="text-tomato text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="w-full flex flex-col items-start justify-start">
                            <div class="w-full flex flex-col items-start justify-start gap-1.5">
                                <label for="password" class="relative leading-5 font-medium">
                                    Password
                                    <span class="text-tomato">*</span>
                                </label>
                                <div class="input-container w-full shadow-[0px_1px_2px_rgba(16,_24,_40,_0.05)] rounded-lg bg-white border-lightgray border-solid border-[1px] box-border h-11 overflow-hidden flex flex-row items-center py-2.5 px-4 gap-2 text-gray-200">
                                    <x-main.text-input 
                                        id="password" 
                                        name="password" 
                                        type="password" 
                                        placeholder="Enter your password" 
                                        required 
                                        autocomplete="current-password" 
                                        class="flex-1 relative leading-5 bg-transparent border-none focus:ring-0 p-0 text-gray-200 w-full"
                                    />
                                    <img class="w-5 relative h-5 object-cover cursor-pointer toggle-password shrink-0" alt="Toggle password visibility" src="{{ asset('images/eye-enabled.svg') }}">
                                </div>
                                @error('password')
                                    <span class="text-tomato text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Remember Me and Forgot Password -->
                    <div class="w-full flex flex-row flex-wrap items-center justify-between gap-2 text-slategray-100 mt-4 font-outfit">
                        <div class="flex flex-row items-center justify-start gap-3">
                            <input id="remember_me" type="checkbox" name="remember" class="rounded border-lightgray border-solid border-[1.3px] w-5 h-5 shrink-0 focus:ring-0">
                            <label for="remember_me" class="relative leading-5">Keep me logged in</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="relative leading-5 text-mediumslateblue no-underline">Forgot password?</a>
                    </div>

                    <!-- Submit Button -->
                    <div class="w-full flex flex-col items-start justify-start mt-6">
                        <button type="submit" class="login-button w-full shadow-[0px_1px_2px_rgba(16,_24,_40,_0.05)] rounded-lg bg-slategray-200 border-slategray-200 border-solid border-[1px] overflow-hidden flex flex-row items-center justify-center py-3 px-4 font-outfit text-white hover:bg-opacity-90 transition-colors">
                            <span class="relative leading-5 font-medium">Log In</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>